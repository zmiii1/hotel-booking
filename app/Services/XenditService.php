<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\TicketOrder;
use App\Models\TicketPayment;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class XenditService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('xendit.secret_key');
        $this->baseUrl = 'https://api.xendit.co';
    }

    /**
     * Create Xendit invoice
     * 
     * @param Booking|TicketOrder|array $entity
     * @return array
     */
    public function createInvoice($entity)
    {
        // Check if it's a Booking
        if ($entity instanceof Booking) {
            return $this->createBookingInvoice($entity);
        }
        
        // Check if it's a TicketOrder
        if ($entity instanceof TicketOrder) {
            return $this->createTicketInvoice($entity);
        }
        
        // Check if it's an array of params (for direct API calls)
        if (is_array($entity)) {
            return $this->createDirectInvoice($entity);
        }
        
        // Unsupported entity type
        Log::error('Unsupported entity type for createInvoice', [
            'type' => gettype($entity),
            'class' => is_object($entity) ? get_class($entity) : 'not an object'
        ]);
        
        return [
            'success' => false,
            'message' => 'Unsupported entity type for invoice creation'
        ];
    }
    
    /**
     * Create invoice for hotel booking
     * 
     * @param Booking $booking
     * @return array
     */
    private function createBookingInvoice(Booking $booking)
    {
        $externalId = 'booking-' . $booking->code . '-' . Str::random(6);
        $expiry = 1 * 60 * 60; // 1 hour in seconds
        $expiredAt = Carbon::now()->addSeconds($expiry);
        
        Log::info('Setting hotel booking invoice expiry time', [
            'booking_code' => $booking->code,
            'expiry_seconds' => $expiry,
            'expired_at' => $expiredAt->format('Y-m-d H:i:s')
        ]);
        
        $params = [
            'external_id' => $externalId,
            'amount' => $booking->total_amount,
            'payer_email' => $booking->email,
            'description' => 'Payment for hotel booking ' . $booking->code,
            'client_type' => 'INTEGRATION',
            'success_redirect_url' => config('app.url') . '/payment/success/' . $booking->code,
            'failure_redirect_url' => config('app.url') . '/payment/failed/' . $booking->code,
            'payment_methods' => [
                'BCA', 'BNI', 'BRI', 'MANDIRI', 'PERMATA',
                'BSI', 'ALFAMART', 'INDOMARET', 'OVO', 'DANA', 'LINKAJA', 'SHOPEEPAY',
                'QRIS', 'CREDIT_CARD'
            ],
            'invoice_duration' => $expiry,
        ];
        
        try {
            $response = $this->sendInvoiceRequest($params);
            
            if (!$response['success']) {
                return $response;
            }
            
            $invoice = $response['data'];
            
            // Save payment to database
            $payment = Payment::create([
                'booking_code' => $booking->code,
                'payment_id' => $invoice['id'] ?? null,
                'external_id' => $externalId,
                'payment_status' => 'pending',
                'amount' => $booking->total_amount,
                'checkout_url' => $invoice['invoice_url'],
                'expired_at' => $expiredAt,
            ]);
            
            return [
                'success' => true,
                'payment' => $payment,
                'checkout_url' => $invoice['invoice_url']
            ];
        } catch (\Exception $e) {
            Log::error('Hotel Booking Xendit Invoice Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'booking_code' => $booking->code
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Create invoice for beach ticket order
     * 
     * @param TicketOrder $order
     * @return array
     */
    private function createTicketInvoice(TicketOrder $order)
    {
        $externalId = 'ticket-' . $order->order_code . '-' . Str::random(6);
        $expiry = 1 * 60 * 60; // 1 hour in seconds
        $expiredAt = Carbon::now()->addSeconds($expiry);
        
        Log::info('Setting beach ticket invoice expiry time', [
            'order_code' => $order->order_code,
            'expiry_seconds' => $expiry,
            'expired_at' => $expiredAt->format('Y-m-d H:i:s')
        ]);
        
        $params = [
            'external_id' => $externalId,
            'amount' => $order->total_price,
            'payer_email' => $order->customer_email,
            'description' => 'Payment for beach ticket ' . $order->order_code,
            'client_type' => 'INTEGRATION',
            'success_redirect_url' => route('ticket-orders.payment.success', ['order_code' => $order->order_code]),
            'failure_redirect_url' => route('ticket-orders.payment.failed', ['order_code' => $order->order_code]),
            'payment_methods' => [
                'BCA', 'BNI', 'BRI', 'MANDIRI', 'PERMATA',
                'BSI', 'ALFAMART', 'INDOMARET', 'OVO', 'DANA', 'LINKAJA', 'SHOPEEPAY',
                'QRIS', 'CREDIT_CARD'
            ],
            'invoice_duration' => $expiry,
        ];
        
        try {
            $response = $this->sendInvoiceRequest($params);
            
            if (!$response['success']) {
                return $response;
            }
            
            $invoice = $response['data'];
            
            // Save payment to database
            $payment = TicketPayment::updateOrCreate(
                ['order_code' => $order->order_code],
                [
                    'ticket_order_id' => $order->id,
                    'payment_id' => $invoice['id'] ?? null,
                    'external_id' => $externalId,
                    'payment_status' => 'pending',
                    'amount' => $order->total_price,
                    'checkout_url' => $invoice['invoice_url'],
                    'expired_at' => $expiredAt
                ]
            );
            
            return [
                'success' => true,
                'payment' => $payment,
                'checkout_url' => $invoice['invoice_url']
            ];
        } catch (\Exception $e) {
            Log::error('Beach Ticket Xendit Invoice Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'order_code' => $order->order_code
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Create invoice directly with params array
     * 
     * @param array $params
     * @return array
     */
    private function createDirectInvoice(array $params)
    {
        try {
            $response = $this->sendInvoiceRequest($params);
            
            if (!$response['success']) {
                return $response;
            }
            
            return [
                'success' => true,
                'data' => $response['data']
            ];
        } catch (\Exception $e) {
            Log::error('Direct Xendit Invoice Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'params' => $params
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Send invoice request to Xendit API
     * 
     * @param array $params
     * @return array
     */
    private function sendInvoiceRequest(array $params)
    {
        Log::info('Creating Xendit invoice', [
            'external_id' => $params['external_id'],
            'amount' => $params['amount'],
            'api_key_exists' => !empty($this->apiKey)
        ]);
        
        $response = Http::withBasicAuth($this->apiKey, '')
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($this->baseUrl . '/v2/invoices', $params);
        
        if ($response->failed()) {
            Log::error('Xendit API Error: ' . $response->body(), [
                'status_code' => $response->status(),
                'params' => array_diff_key($params, ['description' => '']) // Don't log full description
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to create invoice: ' . $response->body()
            ];
        }
        
        $invoice = $response->json();
        
        Log::info('Xendit invoice created successfully', [
            'invoice_id' => $invoice['id'],
            'invoice_url' => $invoice['invoice_url'],
            'expiry_date' => $invoice['expiry_date'] ?? 'Not provided'
        ]);
        
        return [
            'success' => true,
            'data' => $invoice
        ];
    }

    /**
     * Get payment status from Xendit
     * 
     * @param string $paymentId
     * @return array
     */
    public function getPaymentStatus($paymentId)
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, '')
                ->get($this->baseUrl . '/v2/invoices/' . $paymentId);

            if ($response->failed()) {
                Log::error('Xendit API Error: ' . $response->body());
                return [
                    'success' => false,
                    'message' => 'Failed to get invoice: ' . $response->body()
                ];
            }

            $invoice = $response->json();
            
            return [
                'success' => true,
                'status' => $invoice['status'],
                'data' => $invoice
            ];
        } catch (\Exception $e) {
            Log::error('Xendit Get Status Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Check payment status by booking code (hotel booking)
     * 
     * @param string $bookingCode
     * @return array
     */
    public function checkPaymentStatus($bookingCode)
    {
        $payment = Payment::where('booking_code', $bookingCode)->first();
        
        if (!$payment || !$payment->payment_id) {
            return [
                'success' => false,
                'message' => 'Payment not found'
            ];
        }
        
        try {
            $response = Http::withBasicAuth($this->apiKey, '')
                ->get($this->baseUrl . '/v2/invoices/' . $payment->payment_id);

            if ($response->failed()) {
                Log::error('Xendit API Error: ' . $response->body());
                return [
                    'success' => false,
                    'message' => 'Failed to get invoice: ' . $response->body()
                ];
            }

            $invoice = $response->json();
            
            // Update payment status based on Xendit response
            if ($invoice['status'] == 'PAID' && $payment->payment_status != 'paid') {
                $payment->payment_status = 'paid';
                $payment->paid_at = now();
                $payment->save();
                
                // Update booking status
                $booking = $payment->booking;
                if ($booking) {
                    $booking->payment_status = 'paid';
                    $booking->status = 'confirmed';
                    $booking->save();
                }
            }
            
            return [
                'success' => true,
                'status' => $invoice['status'],
                'payment_status' => $payment->payment_status,
                'data' => $invoice
            ];
        } catch (\Exception $e) {
            Log::error('Xendit Check Status Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Check payment status by ticket order code
     * 
     * @param string $orderCode
     * @return array
     */
    public function checkTicketPaymentStatus($orderCode)
    {
        $payment = TicketPayment::where('order_code', $orderCode)->first();
        
        if (!$payment || !$payment->payment_id) {
            return [
                'success' => false,
                'message' => 'Ticket payment not found'
            ];
        }
        
        try {
            $response = Http::withBasicAuth($this->apiKey, '')
                ->get($this->baseUrl . '/v2/invoices/' . $payment->payment_id);

            if ($response->failed()) {
                Log::error('Xendit API Error: ' . $response->body());
                return [
                    'success' => false,
                    'message' => 'Failed to get invoice: ' . $response->body()
                ];
            }

            $invoice = $response->json();
            
            // Update payment status based on Xendit response
            if ($invoice['status'] == 'PAID' && $payment->payment_status != 'paid') {
                $payment->payment_status = 'paid';
                $payment->paid_at = now();
                $payment->save();
                
                // Update order status
                $order = $payment->order;
                if ($order) {
                    $order->payment_status = 'paid';
                    $order->paid_at = now();
                    $order->save();
                }
            }
            
            return [
                'success' => true,
                'status' => $invoice['status'],
                'payment_status' => $payment->payment_status,
                'data' => $invoice
            ];
        } catch (\Exception $e) {
            Log::error('Xendit Check Ticket Status Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle webhook callback from Xendit
     * This method will determine if it's a hotel booking or beach ticket
     * 
     * @param array $data
     * @return array
     */
    public function handleCallback($data)
    {
        try {
            Log::info('Xendit webhook received', $data);
            
            $externalId = $data['external_id'] ?? null;
            $status = $data['status'] ?? null;
            $paymentId = $data['id'] ?? null;
            
            if (!$externalId || !$status || !$paymentId) {
                Log::warning('Xendit Callback: Missing required fields', $data);
                return [
                    'success' => false,
                    'message' => 'Missing required fields in webhook data'
                ];
            }
            
            // Check if it's a hotel booking payment
            if (strpos($externalId, 'booking-') === 0) {
                return $this->handleBookingCallback($externalId, $status, $paymentId);
            }
            
            // Check if it's a beach ticket payment
            if (strpos($externalId, 'ticket-') === 0) {
                return $this->handleTicketCallback($externalId, $status, $paymentId);
            }
            
            // Unknown payment type
            Log::warning('Xendit Callback: Unknown payment type', ['external_id' => $externalId]);
            return [
                'success' => false,
                'message' => 'Unknown payment type'
            ];
        } catch (\Exception $e) {
            Log::error('Xendit Callback Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Handle callback for hotel booking payment
     * 
     * @param string $externalId
     * @param string $status
     * @param string $paymentId
     * @return array
     */
    private function handleBookingCallback($externalId, $status, $paymentId)
    {
        $payment = Payment::where('external_id', $externalId)->first();
        
        if (!$payment) {
            Log::warning('Xendit Callback: Hotel booking payment not found', ['external_id' => $externalId]);
            return [
                'success' => false,
                'message' => 'Hotel booking payment not found'
            ];
        }
        
        // Update payment status
        $payment->payment_id = $paymentId;
        $payment->payment_status = strtolower($status);
        
        if ($status == 'PAID') {
            $payment->paid_at = now();
            
            // Update booking status
            $booking = $payment->booking;
            if ($booking) {
                $booking->payment_status = 'paid';
                $booking->status = 'confirmed';
                $booking->save();
                
                Log::info('Hotel booking payment marked as paid', [
                    'booking_code' => $booking->code,
                    'payment_id' => $paymentId
                ]);
            }
        }
        
        $payment->save();
        
        return [
            'success' => true,
            'payment' => $payment
        ];
    }
    
    /**
     * Handle callback for beach ticket payment
     * 
     * @param string $externalId
     * @param string $status
     * @param string $paymentId
     * @return array
     */
    private function handleTicketCallback($externalId, $status, $paymentId)
    {
        $payment = TicketPayment::where('external_id', $externalId)->first();
        
        if (!$payment) {
            Log::warning('Xendit Callback: Beach ticket payment not found', ['external_id' => $externalId]);
            return [
                'success' => false,
                'message' => 'Beach ticket payment not found'
            ];
        }
        
        // Update payment status
        $payment->payment_id = $paymentId;
        $payment->payment_status = strtolower($status);
        
        if ($status == 'PAID') {
            $payment->paid_at = now();
            
            // Update order status
            $order = $payment->order;
            if ($order) {
                $order->payment_status = 'paid';
                $order->paid_at = now();
                $order->save();
                
                Log::info('Beach ticket payment marked as paid', [
                    'order_code' => $order->order_code,
                    'payment_id' => $paymentId
                ]);
            }
        }
        
        $payment->save();
        
        return [
            'success' => true,
            'payment' => $payment
        ];
    }
}
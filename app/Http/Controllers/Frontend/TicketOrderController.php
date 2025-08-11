<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BeachTicket;
use App\Models\TicketOrder;
use App\Models\TicketPayment;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Contracts\Mail\Mailable;

class TicketOrderController extends Controller
{
    protected $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    /**
     * Create a new order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|string',
        ]);
        
        // Get checkout data from session
        $checkoutData = Session::get('checkout_data');
        
        if (!$checkoutData) {
            return redirect()->route('beach-tickets.index')->with('error', 'No checkout data found. Please start over.');
        }
        
        // Get ticket details
        $ticket = BeachTicket::findOrFail($checkoutData['ticket_id']);
        
        // Create the order
        $order = TicketOrder::create([
            'beach_ticket_id' => $ticket->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'visit_date' => $checkoutData['visit_date'],
            'quantity' => $checkoutData['quantity'],
            'additional_request' => $checkoutData['additional_request'] ?? null,
            'total_price' => $checkoutData['total_price'],
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
        ]);
        
        // Create payment using Xendit
        $result = $this->createPayment($order);
        
        if (!$result['success']) {
            return redirect()->back()->with('error', 'Failed to create payment: ' . $result['message']);
        }
        
        // Clear session data
        Session::forget('checkout_data');
        Session::forget('selected_ticket');
        
        // Redirect to payment page
        return redirect()->route('ticket-orders.payment', ['order_code' => $order->order_code]);
    }
    
    /**
     * Show payment page
     */
    public function showPayment($orderCode)
    {
        $order = TicketOrder::with(['ticket', 'payment'])->where('order_code', $orderCode)->firstOrFail();
        $payment = $order->payment;
        
        // Check if payment exists and is still valid
        if (!$payment || ($payment->payment_status == 'pending' && now()->gt($payment->expired_at))) {
            // Create new payment
            $result = $this->createPayment($order);
            
            if (!$result['success']) {
                return redirect()->back()->with('error', 'Failed to create payment: ' . $result['message']);
            }
            
            $payment = $result['payment'];
        }
        
        return view('frontend.beach-tickets.payment', [
            'order' => $order,
            'payment' => $payment,
            'isDevelopment' => config('app.env') === 'local'
        ]);
    }
    
    /**
     * Handle payment success
     */
    public function paymentSuccess($orderCode)
    {
        $order = TicketOrder::with(['ticket', 'payment'])->where('order_code', $orderCode)->firstOrFail();
        
        if ($order->payment && $order->payment->payment_status == 'pending') {
            $result = $this->xenditService->getPaymentStatus($order->payment->payment_id);
            
            if ($result['success'] && $result['status'] == 'PAID') {
                $this->markOrderAsPaid($order);
            }
        }
        
        return redirect()->route('ticket-orders.confirmation', ['order_code' => $order->order_code]);
    }
    
    /**
     * Show confirmation page
     */
    public function showConfirmation($orderCode)
    {
        $order = TicketOrder::with(['ticket' => function($query) {
            $query->with('benefits');
        }])->where('order_code', $orderCode)->firstOrFail();
        
        return view('frontend.beach-tickets.confirmation', [
            'order' => $order
        ]);
    }
    
    /**
     * Manual update for testing (development only)
     */
    public function manualUpdate(Request $request, $orderCode)
    {
        if (config('app.env') !== 'local') {
            abort(404);
        }
        
        $order = TicketOrder::with(['payment'])->where('order_code', $orderCode)->firstOrFail();
        $payment = $order->payment;
        
        if (!$payment) {
            return redirect()->back()->with('error', 'Payment not found');
        }
        
        $status = $request->input('status', 'paid');
        
        if ($status === 'paid') {
            $this->markOrderAsPaid($order);
            
            return redirect()->route('ticket-orders.confirmation', ['order_code' => $order->order_code])
                ->with('success', 'Payment has been simulated as paid successfully!');
        } else if ($status === 'expired') {
            // Update payment status
            $payment->payment_status = 'expired';
            $payment->expired_at = now()->subMinute();
            $payment->save();
            
            // Update order status
            $order->payment_status = 'expired';
            $order->save();
            
            return redirect()->route('ticket-orders.payment', ['order_code' => $order->order_code])
                ->with('error', 'Your payment has expired. Please try again with a new payment.');
        }
        
        return redirect()->back()->with('success', 'Payment status updated to ' . $status);
    }
    
    /**
     * Handle Xendit webhook
     */
    public function webhook(Request $request)
    {
        Log::info('Ticket Xendit Webhook Received', $request->all());
        
        // Verify callback token
        $callbackToken = $request->header('X-Callback-Token');
        if ($callbackToken != config('xendit.callback_token')) {
            Log::warning('Ticket Xendit Webhook: Invalid callback token');
            return response()->json(['error' => 'Invalid callback token'], 401);
        }
        
        $externalId = $request->input('external_id');
        $status = $request->input('status');
        $paymentId = $request->input('id');
        
        // Find payment by external_id
        $payment = TicketPayment::where('external_id', $externalId)->first();
        
        if (!$payment) {
            Log::warning('Ticket Payment not found for external_id: ' . $externalId);
            return response()->json(['error' => 'Payment not found'], 404);
        }
        
        // Update payment status
        $payment->payment_id = $paymentId;
        $payment->payment_status = strtolower($status);
        
        if ($status == 'PAID') {
            $payment->paid_at = now();
            $payment->save();
            
            // Get order and update status
            $order = $payment->order;
            if ($order) {
                $this->markOrderAsPaid($order);
            }
        } else {
            $payment->save();
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Helper: Create payment for an order
     */
    private function createPayment($order)
    {
        try {
            // Call Xendit service to create payment
            $response = $this->xenditService->createInvoice($order);
            
            if (!$response['success']) {
                Log::error('Failed to create ticket payment: ' . ($response['message'] ?? 'Unknown error'));
                return [
                    'success' => false,
                    'message' => $response['message'] ?? 'Failed to create payment'
                ];
            }
            
            return [
                'success' => true,
                'payment' => $response['payment'],
                'checkout_url' => $response['checkout_url']
            ];
        } catch (\Exception $e) {
            Log::error('Error creating ticket payment: ' . $e->getMessage(), [
                'order_code' => $order->order_code,
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Helper: Mark order as paid
     */
    private function markOrderAsPaid($order)
    {
        // Update order status
        $order->payment_status = 'paid';
        $order->paid_at = now();
        $order->save();
        
        // Update payment status if exists
        if ($order->payment) {
            $order->payment->payment_status = 'paid';
            $order->payment->paid_at = now();
            $order->payment->save();
        }
        
        // Send confirmation email
        $this->sendTicketConfirmationEmail($order);
        
        Log::info('Ticket order marked as paid', [
            'order_code' => $order->order_code
        ]);
    }
    
    /**
     * Helper: Send confirmation email
     */
    private function sendTicketConfirmationEmail($order)
    {
        try {
            // Load relationships if needed
            if (!$order->relationLoaded('ticket')) {
                $order->load(['ticket' => function($query) {
                    $query->with('benefits');
                }]);
            }
            
            // Send email
            Mail::to($order->customer_email)
                ->send(new \App\Mail\TicketOrderConfirmation($order));
            
            Log::info('Ticket order confirmation email sent', [
                'order_code' => $order->order_code,
                'email' => $order->customer_email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send ticket order confirmation email: ' . $e->getMessage(), [
                'order_code' => $order->order_code,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
}
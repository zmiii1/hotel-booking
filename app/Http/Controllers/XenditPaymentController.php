<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\RoomType;
use Carbon\Carbon;

class XenditPaymentController extends Controller
{
    protected $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    /**
     * Menampilkan halaman pembayaran
     */
    public function showPayment(Request $request, $code)
    {
        Log::info('XenditPaymentController::showPayment called', [
            'code' => $code
        ]);
        
        // Ambil session room_type_name
        $sessionRoomName = Session::get('room_type_name');
        Log::info('Session room_type_name', [
            'value' => $sessionRoomName ?? 'Not set'
        ]);

        $booking = Booking::with(['room', 'roomType'])->where('code', $code)->firstOrFail();
        
        // Log untuk debugging
        Log::info('Booking data loaded', [
            'booking_id' => $booking->id,
            'code' => $booking->code,
            'has_room_type' => isset($booking->roomType),
            'room_type_id' => $booking->room_type_id ?? 'NULL',
            'room_type' => $booking->roomType ? $booking->roomType->name : 'NULL',
            'has_room' => isset($booking->room),
            'room_name' => $booking->room ? ($booking->room->name ?? 'No name') : 'NULL',
            'room_type_name_column' => $booking->room_type_name ?? 'NULL' // Log kolom room_type_name
        ]);
        
        $payment = Payment::where('booking_code', $code)->first();

        if ($booking->room_type_id) {
            $directRoomType = RoomType::find($booking->room_type_id);
            Log::info('Direct room type lookup', [
                'found' => $directRoomType ? true : false,
                'name' => $directRoomType ? $directRoomType->name : 'Not found'
            ]);
        }
        
        $roomName = null;

        if ($sessionRoomName) {
            $roomName = $sessionRoomName;
            Log::info('Using room name from session', ['name' => $roomName]);
        }
        else if ($booking->room_type_name) {
            $roomName = $booking->room_type_name;
            Log::info('Using room name from booking.room_type_name', ['name' => $roomName]);
        }
        else if ($booking->roomType) {
            $roomName = $booking->roomType->name;
            Log::info('Using room name from roomType relation', ['name' => $roomName]);
        }
        else if ($booking->room && $booking->room->name) {
            $roomName = $booking->room->name;
            Log::info('Using room name from room.name', ['name' => $roomName]);
        }
        // Default fallback
        else {
            $roomName = "Zamrud Cottage"; // Hardcode for testing
            Log::info('Using hardcoded room name', ['name' => $roomName]);
        }
        
        // Generate a new payment after failed
        if ($request->has('force') || !$payment || ($payment->payment_status == 'pending' && now()->gt($payment->expired_at))) {
            // Log jika payment sudah expired
            if ($payment && now()->gt($payment->expired_at)) {
                Log::info('Payment expired, creating new payment', [
                    'booking_code' => $code,
                    'expired_at' => $payment->expired_at->format('Y-m-d H:i:s'),
                    'current_time' => now()->format('Y-m-d H:i:s'),
                    'difference_minutes' => now()->diffInMinutes($payment->expired_at)
                ]);
            }
            
            $result = $this->xenditService->createInvoice($booking);
            Log::info('Xendit invoice creation result', [
                'success' => $result['success'] ?? false,
                'message' => $result['message'] ?? null
            ]);
            
            if ($result['success']) {
                $payment = $result['payment'];
            }
        } else {
            // Log status payment yang ada
            Log::info('Using existing payment', [
                'booking_code' => $code,
                'payment_id' => $payment->payment_id,
                'payment_status' => $payment->payment_status,
                'expired_at' => $payment->expired_at->format('Y-m-d H:i:s'),
                'current_time' => now()->format('Y-m-d H:i:s'),
                'is_expired' => now()->gt($payment->expired_at),
                'time_left_minutes' => $payment->expired_at->diffInMinutes(now())
            ]);
        }
        
        if (!$payment || !$payment->checkout_url) {
            Log::error('Failed to create or get payment', [
                'booking_code' => $code,
                'payment' => $payment
            ]);
            return redirect()->back()->with('error', 'Failed to create payment. Please try again.');
        }
        
        $isDevelopment = config('xendit.dev_mode', false);
        
        Log::info('Redirecting to Xendit payment page', [
            'booking_code' => $code,
            'checkout_url' => $payment->checkout_url,
            'room_name' => $roomName
        ]);
        
        return view('frontend.payment.xendit', [
            'booking' => $booking,
            'payment' => $payment,
            'roomName' => $roomName,
            'isDevelopment' => $isDevelopment
        ]);
    }

    /**
     * Manually update payment status (for development)
     */
    public function manualUpdate(Request $request, $code)
    {
        $booking = Booking::with(['room', 'roomType'])->where('code', $code)->firstOrFail();
        $payment = Payment::where('booking_code', $code)->first();
        
        if (!$payment) {
            return redirect()->back()->with('error', 'Payment not found');
        }
        
        $status = $request->input('status', 'paid');
        
        if ($status === 'paid') {
            // Update payment status
            $payment->payment_status = 'paid';
            $payment->paid_at = now();
            $payment->save();
            
            // Update booking status
            $booking->payment_status = 'paid';
            $booking->status = 'confirmed';
            $booking->save();
            
            // Kirim email konfirmasi
            $this->sendBookingConfirmationEmail($booking, true);
            
            // Redirect to confirmation page
            return redirect()->route('booking.confirmation', ['code' => $code])
                ->with('success', 'Payment has been simulated as paid successfully!');
        } else if ($status === 'expired') {
            // Update payment status
            $payment->payment_status = 'expired';
            $payment->expired_at = now()->subMinute();
            $payment->save();
            
            // Update booking status
            $booking->payment_status = 'expired';
            $booking->status = 'cancelled';
            $booking->save();
            
            // Redirect back with expired status
            return redirect()->route('booking.payment.xendit', ['code' => $code, 'force' => 1])
                ->with('error', 'Your payment has expired. Creating a new payment link for you.');
        }
        
        return redirect()->back()
            ->with('success', 'Payment status updated to ' . $status);
    }

    /**
     * Handle successful payment redirect
     */
    public function paymentSuccess(Request $request, $code)
    {
        $booking = Booking::with(['room', 'roomType', 'package', 'hotel', 'addons'])
            ->where('code', $code)
            ->firstOrFail();
            
        $payment = Payment::where('booking_code', $code)->first();
        
        if (!$payment) {
            return redirect()->route('booking.confirmation', ['code' => $code])
                ->with('warning', 'Your payment status is being processed.');
        }
        
        // Jika payment masih pending, periksa status terbaru dari Xendit
        if ($payment->payment_status == 'pending' && $payment->payment_id) {
            $result = $this->xenditService->getPaymentStatus($payment->payment_id);
            
            if ($result['success'] && $result['status'] == 'PAID') {
                $payment->payment_status = 'paid';
                $payment->paid_at = now();
                $payment->save();
                
                $booking->payment_status = 'paid';
                $booking->status = 'confirmed';
                $booking->save();
                
                // Kirim email konfirmasi dengan force (tambahkan true parameter)
                $this->sendBookingConfirmationEmail($booking, true);
                
                Log::info('Email confirmation sent after payment success', [
                    'booking_code' => $booking->code,
                    'email' => $booking->email
                ]);
            }
        }
        
        return redirect()->route('booking.confirmation', ['code' => $code])
            ->with('success', 'Your payment has been processed successfully!');
    }

    /**
     * Handle payment failed redirect
     */
    public function paymentFailed(Request $request, $code)
    {
        return redirect()->route('booking.payment.xendit', ['code' => $code])
            ->with('error', 'Payment failed or was cancelled. Please try again.');
    }

    /**
     * Send booking confirmation email
     */
    private function sendBookingConfirmationEmail(Booking $booking, $force = false)
    {
        try {
            // Load relationships jika belum di-load
            if (!$booking->relationLoaded('room')) {
                $booking->load(['room', 'roomType', 'package', 'hotel', 'addons']);
            }
            
            // Get room name correctly
            $roomName = Session::get('room_type_name', "Standard Room");
            if ($booking->roomType) {
                $roomName = $booking->roomType->name;
            } elseif ($booking->room) {
                if ($booking->room instanceof \Illuminate\Database\Eloquent\Collection) {
                    $roomName = $booking->room->isNotEmpty() ? $booking->room->first()->name : "Standard Room";
                } else {
                    $roomName = $booking->room->name ?? "Standard Room";
                }
            }
            
            // Log mailtrap config for debugging
            Log::info('Mailtrap config', [
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'username' => config('mail.mailers.smtp.username') ? 'Set' : 'Not Set',
                'from' => config('mail.from.address')
            ]);
            
            // Force send even if already sent before if $force is true
            Log::info('Attempting to send email confirmation to: ' . $booking->email);
            
            // Send email
            Mail::to($booking->email)
                ->bcc('reservations@tanjunglesung.com')
                ->send(new \App\Mail\BookingConfirmation($booking, $roomName));
            
            // Log success
            Log::info('Booking confirmation email sent successfully', [
                'booking_code' => $booking->code,
                'email' => $booking->email,
                'forced' => $force
            ]);
            
            return true;
        } catch (\Exception $e) {
            // Log error
            Log::error('Failed to send booking confirmation email: ' . $e->getMessage(), [
                'booking_code' => $booking->code,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }

    /**
     * Handle webhook callback from Xendit
     */
    public function webhook(Request $request)
    {
        Log::info('Xendit Webhook Received', $request->all());
        
        // Verifikasi callback token
        $callbackToken = $request->header('X-Callback-Token');
        if ($callbackToken != config('xendit.callback_token')) {
            Log::warning('Xendit Webhook: Invalid callback token');
            return response()->json(['error' => 'Invalid callback token'], 401);
        }
        
        $result = $this->xenditService->handleCallback($request->all());
        
        if ($result['success']) {
            // Jika pembayaran sukses, kirim email konfirmasi
            if (isset($result['payment']) && $result['payment']->payment_status == 'paid') {
                $booking = Booking::with(['room', 'package', 'hotel'])
                    ->where('code', $result['payment']->booking_code)
                    ->first();
                    
                if ($booking) {
                    $this->sendBookingConfirmationEmail($booking);
                }
            }
            
            return response()->json(['success' => true]);
        } else {
            Log::error('Xendit Webhook Error', ['message' => $result['message']]);
            return response()->json(['error' => $result['message']], 400);
        }
    }
}
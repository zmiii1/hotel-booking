@extends('frontend.main')

@section('styles')
<link rel="stylesheet" href="{{ asset('frontend/assets/css/beach-tickets.css') }}">
@endsection

@section('main')
<div class="container payment-page py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card payment-card">
                <div class="card-header">
                    <h3 class="card-title">Complete Your Payment</h3>
                </div>
                <div class="card-body">
                    <div class="booking-details mb-4">
                        <h4>Ticket Order Details</h4>
                        <div class="booking-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Order Code:</strong> {{ $order->order_code }}</p>
                                    <p><strong>Customer Name:</strong> {{ $order->customer_name }}</p>
                                    <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Visit Date:</strong> {{ \Carbon\Carbon::parse($order->visit_date)->format('d F Y') }}</p>
                                    <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
                                    <p><strong>Ticket:</strong> {{ $order->ticket->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="payment-details mb-4">
                        <h4>Payment Information</h4>
                        <div class="payment-amount">
                            <p class="amount-label">Total Payment:</p>
                            <p class="amount-value">Rp. {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                        <div class="payment-status">
                            <p class="status-label">Status:</p>
                            <p class="status-value">
                                <span class="badge bg-warning">Awaiting Payment</span>
                            </p>
                        </div>
                        <div class="payment-expiry">
                            <p class="expiry-label">Payment Expires:</p>
                            <p class="expiry-value" id="expiry-countdown"
                               @if($payment && $payment->expired_at && $payment->expired_at->gt(now()))
                                   data-expiry="{{ $payment->expired_at->toISOString() }}"
                               @endif>
                                @if($payment && $payment->expired_at && $payment->expired_at->gt(now()))
                                    <span>Calculating...</span>
                                @else
                                    <span class="text-danger">EXPIRED</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="payment-actions text-center">
                        <p class="mb-3">Click the button below to proceed to the payment page:</p>
                        @if($payment && $payment->checkout_url)
                            <a href="{{ $payment->checkout_url }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i> Pay Now
                            </a>
                        @else
                            <div class="alert alert-danger">
                                Payment URL not available. Please refresh the page.
                            </div>
                        @endif
                        <p class="mt-3 text-muted">You will be redirected to Xendit's secure payment page.</p>
                    </div>
                </div>
            </div>
            
            @if($isDevelopment)
            <div class="development-tools p-3 mt-4 bg-light border rounded">
                <h5>Development Tools</h5>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i> You are in development mode. Use these buttons to simulate payment status.
                </div>
                <div class="d-flex gap-2">
                    <form action="{{ route('ticket-orders.manual-update', ['order_code' => $order->order_code]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="paid">
                        <button type="submit" class="btn btn-success">Simulate Paid</button>
                    </form>
                    <form action="{{ route('ticket-orders.manual-update', ['order_code' => $order->order_code]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="expired">
                        <button type="submit" class="btn btn-danger">Simulate Expired</button>
                    </form>
                </div>
            </div>
            @endif
            
            <div class="payment-help mt-4">
                <h5>Need Help?</h5>
                <p>If you're experiencing any issues with the payment process, please contact our customer support:</p>
                <p><i class="fas fa-envelope me-2"></i> support@tanjunglesung.com</p>
                <p><i class="fas fa-phone me-2"></i> +62 123 456 7890</p>
            </div>
        </div>
    </div>
</div>

{{-- Move script to body, not push to ensure it loads --}}
<script>
// Multiple initialization methods to ensure it works
function initializeCountdown() {
    console.log('🚀 Initializing countdown...');
    
    const countdownElement = document.getElementById('expiry-countdown');
    if (!countdownElement) {
        console.error('❌ Countdown element not found');
        return false;
    }
    
    console.log('✅ Countdown element found');
    
    const expiryDateString = countdownElement.getAttribute('data-expiry');
    console.log('📅 Expiry date string:', expiryDateString);
    
    if (!expiryDateString) {
        console.log('⚠️ No expiry date found, payment might already be expired');
        return false;
    }
    
    const expiryDate = new Date(expiryDateString).getTime();
    const now = new Date().getTime();
    
    console.log('🕐 Expiry date (timestamp):', expiryDate);
    console.log('🕐 Current time (timestamp):', now);
    console.log('⏰ Time difference (ms):', expiryDate - now);
    console.log('⏰ Time difference (minutes):', Math.round((expiryDate - now) / (1000 * 60)));
    
    // Validate expiry date
    if (isNaN(expiryDate)) {
        console.error('❌ Invalid expiry date:', expiryDateString);
        countdownElement.innerHTML = "<span class='text-danger'>INVALID DATE</span>";
        return false;
    }
    
    // Check if already expired
    if (now > expiryDate) {
        console.log('⏰ Payment already expired');
        countdownElement.innerHTML = "<span class='text-danger'>EXPIRED</span>";
        
        // Reload the page after 3 seconds to generate new payment
        setTimeout(function() {
            console.log('🔄 Payment expired, reloading page...');
            window.location.reload();
        }, 3000);
        
        return false;
    }
    
    console.log('✅ Starting countdown timer...');
    
    // Function to update countdown display
    function updateCountdown() {
        const currentTime = new Date().getTime();
        const distance = expiryDate - currentTime;
        
        // If countdown finished
        if (distance < 0) {
            console.log('⏰ Countdown finished, payment expired');
            countdownElement.innerHTML = "<span class='text-danger'>EXPIRED</span>";
            return false; // Stop the interval
        }
        
        // Time calculations
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        // Display countdown with better formatting
        let displayTime = '';
        
        if (hours > 0) {
            displayTime = `${hours}h ${minutes}m ${seconds}s`;
        } else if (minutes > 0) {
            displayTime = `${minutes}m ${seconds}s`;
        } else {
            displayTime = `${seconds}s`;
            // Add urgency styling when less than 1 minute
            if (!countdownElement.classList.contains('text-danger')) {
                countdownElement.classList.add('text-danger');
            }
        }
        
        countdownElement.innerHTML = displayTime;
        return true; // Continue the interval
    }
    
    // Initial update
    updateCountdown();
    
    // Start countdown timer
    const countdownTimer = setInterval(function() {
        if (!updateCountdown()) {
            clearInterval(countdownTimer);
            // Reload the page after 3 seconds when expired
            setTimeout(function() {
                console.log('🔄 Payment expired, reloading page...');
                window.location.reload();
            }, 3000);
        }
    }, 1000);
    
    console.log('✅ Countdown initialized successfully');
    return true;
}

// Try multiple initialization approaches
console.log('🎯 Script loaded, attempting to initialize countdown...');

// Method 1: Immediate execution
if (document.readyState === 'loading') {
    console.log('📄 Document still loading, waiting for DOMContentLoaded...');
    document.addEventListener('DOMContentLoaded', initializeCountdown);
} else {
    console.log('📄 Document already loaded, initializing immediately...');
    initializeCountdown();
}

// Method 2: Backup with timeout
setTimeout(function() {
    console.log('⏰ Backup initialization after 500ms...');
    if (document.getElementById('expiry-countdown') && 
        document.getElementById('expiry-countdown').innerHTML.includes('Calculating')) {
        initializeCountdown();
    }
}, 500);

// Method 3: Window load backup
window.addEventListener('load', function() {
    console.log('🌐 Window fully loaded, backup initialization...');
    if (document.getElementById('expiry-countdown') && 
        document.getElementById('expiry-countdown').innerHTML.includes('Calculating')) {
        initializeCountdown();
    }
});
</script>
@endsection
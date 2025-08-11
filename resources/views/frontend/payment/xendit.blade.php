@extends('frontend.main')

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
                        <h4>Booking Details</h4>
                        <div class="booking-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Booking Code:</strong> {{ $booking->code }}</p>
                                    <p><strong>Guest Name:</strong> {{ $booking->first_name }} {{ $booking->last_name }}</p>
                                    <p><strong>Email:</strong> {{ $booking->email }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->format('D, d M Y') }}</p>
                                    <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out)->format('D, d M Y') }}</p>
                                    <p><strong>Room:</strong> {{ $roomName }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="payment-details mb-4">
                        <h4>Payment Information</h4>
                        <div class="payment-amount">
                            <p class="amount-label">Total Payment:</p>
                            <p class="amount-value">Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="payment-status">
                            <p class="status-label">Status:</p>
                            <p class="status-value">
                                <span class="badge bg-warning">Awaiting Payment</span>
                            </p>
                        </div>
                        <div class="payment-expiry">
                            <p class="expiry-label">Payment Expires:</p>
                            <p class="expiry-value" id="expiry-countdown">
                                @if($payment->expired_at && $payment->expired_at->gt(now()))
                                    <span>Calculating...</span>
                                @else
                                    <span>EXPIRED</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="payment-actions text-center">
                        <p class="mb-3">Click the button below to proceed to the payment page:</p>
                        <a href="{{ $payment->checkout_url }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-credit-card me-2"></i> Pay Now
                        </a>
                        <p class="mt-3 text-muted">You will be redirected to Xendit's secure payment page.</p>
                    </div>
                </div>
            </div>                           
            <!-- TAMBAHKAN TOMBOL DEVELOPMENT DI SINI -->
            @if(config('xendit.dev_mode', false) || config('app.env') === 'local')
            <div class="development-tools p-3 mt-4 bg-light border rounded">
                <h5>Development Tools</h5>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i> You are in development mode. Use these buttons to simulate payment status.
                </div>
                <div class="d-flex gap-2">
                    <form action="{{ route('payment.manual.update', ['code' => $booking->code]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="paid">
                        <button type="submit" class="btn btn-success">Simulate Paid</button>
                    </form>
                    <form action="{{ route('payment.manual.update', ['code' => $booking->code]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="expired">
                        <button type="submit" class="btn btn-danger">Simulate Expired</button>
                    </form>
                </div>
            </div>
            @endif
            <!-- AKHIR TOMBOL DEVELOPMENT -->  
            <div class="payment-help mt-4">
                <h5>Need Help?</h5>
                <p>If you're experiencing any issues with the payment process, please contact our customer support:</p>
                <p><i class="fas fa-envelope me-2"></i> support@tanjunglesung.com</p>
                <p><i class="fas fa-phone me-2"></i> +62 123 456 7890</p>
            </div>
        </div>
    </div>
</div>

<style>
    .payment-page {
        background-color: #f8f9fa;
    }
    
    .payment-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .card-header {
        background-color: #4a90e2;
        color: white;
        padding: 15px 20px;
    }
    
    .card-title {
        margin-bottom: 0;
        font-size: 22px;
        font-weight: 600;
    }
    
    .booking-details,
    .payment-details {
        padding: 15px;
        border: 1px solid #eee;
        border-radius: 8px;
        background-color: #fff;
    }
    
    .booking-details h4,
    .payment-details h4 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 1px solid #eee;
    }
    
    .payment-amount,
    .payment-status,
    .payment-expiry {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed #eee;
    }
    
    .payment-expiry {
        border-bottom: none;
    }
    
    .amount-value {
        font-size: 20px;
        font-weight: 700;
        color: #333;
    }
    
    .payment-actions {
        margin-top: 25px;
    }
    
    .btn-primary {
        background-color: #4a90e2;
        border-color: #4a90e2;
        padding: 10px 25px;
        font-weight: 600;
    }
    
    .payment-help {
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #eee;
    }
    
    .payment-help h5 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hanya gunakan satu timer untuk countdown
    @if($payment->expired_at && $payment->expired_at->gt(now()))
        // Simpan waktu expired ke variabel JavaScript
        const expiryDate = new Date('{{ $payment->expired_at->toIso8601String() }}').getTime();
        
        // Log untuk debugging
        console.log('Expiry date:', '{{ $payment->expired_at->toIso8601String() }}');
        console.log('Current time:', '{{ now()->toIso8601String() }}');
        console.log('Difference (minutes):', Math.floor((expiryDate - new Date().getTime()) / (1000 * 60)));
        
        // Mulai countdown timer
        const countdownTimer = setInterval(function() {
            // Hitung selisih waktu
            const now = new Date().getTime();
            const distance = expiryDate - now;
            
            // Konversi ke jam, menit, detik
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Update elemen countdown
            const countdownElement = document.getElementById('expiry-countdown');
            if (countdownElement) {
                countdownElement.innerHTML = hours + "h " + minutes + "m " + seconds + "s";
            }
            
            // Jika waktu sudah habis
            if (distance < 0) {
                clearInterval(countdownTimer);
                if (countdownElement) {
                    countdownElement.innerHTML = "EXPIRED";
                }
                
                // Reload halaman untuk regenerate payment link
                setTimeout(function() {
                    window.location.href = '{{ route("booking.payment.xendit", ["code" => $booking->code, "force" => 1]) }}';
                }, 3000);
            }
        }, 1000);
    @else
        // Jika payment sudah expired
        console.log('Payment already expired');
        const countdownElement = document.getElementById('expiry-countdown');
        if (countdownElement) {
            countdownElement.innerHTML = "EXPIRED";
        }
        
        // Redirect ke halaman dengan payment baru
        setTimeout(function() {
            window.location.href = '{{ route("booking.payment.xendit", ["code" => $booking->code, "force" => 1]) }}';
        }, 3000);
    @endif
});
</script>
@endsection
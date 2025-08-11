@extends('frontend.main')

@section('main')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="m-0">Payment Options</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5>Booking Information</h5>
                        <p><strong>Booking Code:</strong> {{ $booking->code }}</p>
                        <p><strong>Room:</strong> {{ $roomName }}</p>
                        <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->format('D, d M Y') }}</p>
                        <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out)->format('D, d M Y') }}</p>
                        <p><strong>Total Amount:</strong> Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</p>
                    </div>
                    
                    <form action="{{ route('booking.process.payment', ['code' => $booking->code]) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <h5 class="mb-3">Select Payment Method</h5>
                            
                            <div class="payment-option mb-3">
                                <input type="radio" class="btn-check" name="payment_method" id="payment-xendit" value="xendit" checked>
                                <label class="btn btn-outline-primary w-100 text-start p-3" for="payment-xendit">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-credit-card fa-2x text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Online Payment</h6>
                                            <p class="small mb-0 text-muted">Credit Card, Bank Transfer, E-wallet, QRIS</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="payment-option mb-3">
                                <input type="radio" class="btn-check" name="payment_method" id="payment-manual" value="manual_transfer">
                                <label class="btn btn-outline-primary w-100 text-start p-3" for="payment-manual">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-university fa-2x text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Manual Bank Transfer</h6>
                                            <p class="small mb-0 text-muted">Transfer manually and upload receipt</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="payment-option mb-3">
                                <input type="radio" class="btn-check" name="payment_method" id="payment-hotel" value="pay_at_hotel">
                                <label class="btn btn-outline-primary w-100 text-start p-3" for="payment-hotel">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-hotel fa-2x text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Pay at Hotel</h6>
                                            <p class="small mb-0 text-muted">Pay during check-in</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100">Continue to Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-option label {
    border-radius: 8px;
    transition: all 0.2s ease;
}

.payment-option label:hover {
    background-color: #f8f9fa;
}

.btn-check:checked + label {
    background-color: #f0f8ff !important;
    border-color: #0d6efd !important;
}
</style>
@endsection
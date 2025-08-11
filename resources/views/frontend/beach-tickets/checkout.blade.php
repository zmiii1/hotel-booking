@extends('frontend.main')

@section('main')

<link rel="stylesheet" href="{{ asset('frontend/assets/css/beach-tickets.css') }}">

<div class="btc-container">
    <a href="{{ url()->previous() }}" class="btc-return-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Return to ticket
    </a>

    <div class="btc-row">
        <div class="btc-col btc-col-7">
            <div class="btc-form-container">
                <h3 class="btc-title">Customer Information</h3>
                <form action="{{ route('ticket-orders.store') }}" method="POST" id="checkoutForm">
                    @csrf
                    <div class="btc-form-group">
                        <input type="text" class="btc-form-control @error('customer_name') is-invalid @enderror" 
                               placeholder="Name" id="customerName" name="customer_name" value="{{ old('customer_name') }}" required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="btc-form-group">
                        <input type="tel" class="btc-form-control @error('customer_phone') is-invalid @enderror" 
                               placeholder="Phone Number" id="customerPhone" name="customer_phone" value="{{ old('customer_phone') }}" required>
                        @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="btc-form-group">
                        <input type="email" class="btc-form-control @error('customer_email') is-invalid @enderror" 
                               placeholder="Email Address" id="customerEmail" name="customer_email" value="{{ old('customer_email') }}" required>
                        @error('customer_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="btc-payment-info">
                        <h3 class="btc-title">Payment Method</h3>
                        <p>You'll be redirected to Xendit's secure payment page to complete your payment.</p>
                        
                        <input type="hidden" name="payment_method" value="xendit">
                        
                        <div class="btc-payment-box">
                            <div class="btc-payment-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <path d="M3 10h18"/>
                                </svg>
                            </div>
                            <div class="btc-payment-text">
                                <strong>Xendit Payment Gateway</strong>
                                <span>Pay with credit card, bank transfer, e-wallet or QRIS</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="btc-col btc-col-5">
            <div class="btc-summary">
                <h3 class="btc-summary-title">Order Summary</h3>
                <div class="btc-order-item">
                    <!-- PERBAIKAN: Gunakan image_url dari model $ticket seperti di halaman index dan show -->
                    <img src="{{ $ticket->image_url }}" class="btc-order-img" alt="{{ $ticket->name }}">
                    <div>
                        <h5>{{ $ticket->name }}</h5>
                        <p>{{ $ticket->formatted_price }}</p>
                        <p><strong>Benefits:</strong></p>
                        <ul class="btc-benefits-list">
                            @foreach($ticket->benefits as $benefit)
                                <li>{{ $benefit->benefit_name }}</li>
                            @endforeach
                        </ul>
                        <p class="small"><strong>Date of your visit:</strong> {{ \Carbon\Carbon::parse($visitDate)->format('d F Y') }}</p>
                        <p class="small"><strong>Quantity:</strong> {{ $quantity }}</p>
                    </div>
                </div>
                
                <div class="btc-price-row">
                    <span>Subtotal</span>
                    <span>Rp. {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
                
                <div class="btc-total-row">
                    <span>Total</span>
                    <span>Rp. {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
                
                <button class="btc-btn" onclick="document.getElementById('checkoutForm').submit()">
                    Process Payment
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkoutForm');
    
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(event) {
            const customerName = document.getElementById('customerName').value;
            const customerPhone = document.getElementById('customerPhone').value;
            const customerEmail = document.getElementById('customerEmail').value;
            
            if (!customerName || !customerPhone || !customerEmail) {
                event.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    }
});
</script>
@endpush
@endsection
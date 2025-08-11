@extends('frontend.main')

@section('styles')
<link rel="stylesheet" href="{{ asset('frontend/assets/css/beach-tickets.css') }}">
@endsection

@section('main')
<div class="container booking-confirmation py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card confirmation-card">
                <div class="card-header bg-success text-white">
                    <h3 class="confirmation-title mb-0">
                        <i class="fas fa-check-circle me-2"></i> Ticket Order Confirmed!
                    </h3>
                </div>
                <div class="card-body">
                    <div class="confirmation-message">
                        <p class="lead">Thank you for your order, {{ $order->customer_name }}.</p>
                        <p>Your beach ticket order has been confirmed. We've sent a confirmation email to <strong>{{ $order->customer_email }}</strong> with all the details.</p>
                        <div class="booking-reference">
                            <span>Order Reference:</span>
                            <strong>{{ $order->order_code }}</strong>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="booking-info-section">
                                <h4>Ticket Information</h4>
                                <div class="info-grid">
                                    <div class="info-row">
                                        <div class="info-label">Ticket:</div>
                                        <div class="info-value">{{ $order->ticket->name }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Visit Date:</div>
                                        <div class="info-value">{{ \Carbon\Carbon::parse($order->visit_date)->format('d F Y') }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Quantity:</div>
                                        <div class="info-value">{{ $order->quantity }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="booking-info-section">
                                <h4>Customer Information</h4>
                                <div class="info-grid">
                                    <div class="info-row">
                                        <div class="info-label">Name:</div>
                                        <div class="info-value">{{ $order->customer_name }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Email:</div>
                                        <div class="info-value">{{ $order->customer_email }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Phone:</div>
                                        <div class="info-value">{{ $order->customer_phone }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="booking-details mt-4">
                        <h4>Benefits</h4>
                        <div class="benefits-list">
                            <ul>
                                @foreach($order->ticket->benefits as $benefit)
                                    <li>{{ $benefit->benefit_name }}</li>
                                @endforeach
                            </ul>
                        </div>
                        
                        @if($order->additional_request)
                        <div class="special-request mt-3">
                            <h6>Special Requests:</h6>
                            <p class="text-muted">{{ $order->additional_request }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Price Details Section -->
                    <div class="booking-details mt-4">
                        <h4>Price Details</h4>
                        <div class="price-details">
                            <div class="price-row">
                                <div class="price-label">Ticket Price ({{ $order->quantity }}x)</div>
                                <div class="price-value">Rp. {{ number_format($order->total_price, 0, ',', '.') }}</div>
                            </div>
                            
                            <div class="price-row total-row">
                                <div class="price-label">Total</div>
                                <div class="price-value">Rp. {{ number_format($order->total_price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="booking-details mt-4">
                        <h4>Payment Information</h4>
                        <div class="payment-details">
                            <div class="info-grid">
                                <div class="info-row">
                                    <div class="info-label">Payment Method:</div>
                                    <div class="info-value">{{ ucfirst($order->payment_method) }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Payment Status:</div>
                                    <div class="info-value">
                                        <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Call to Action Buttons -->
                    <div class="cta-buttons mt-4 text-center">
                        <a href="{{ route('beach-tickets.index') }}" class="btn btn-primary me-2">
                            <i class="fas fa-home me-1"></i> Back to Tickets
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
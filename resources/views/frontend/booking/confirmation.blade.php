@extends('frontend.main')

@section('main')

<link rel="stylesheet" href="{{ asset('frontend/assets/css/confirmation.css') }}">

<div class="container booking-confirmation py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card confirmation-card">
                <div class="card-header bg-success text-white">
                    <h3 class="confirmation-title mb-0">
                        <i class="fas fa-check-circle me-2"></i> Booking Confirmed!
                    </h3>
                </div>
                <div class="card-body">
                    <div class="confirmation-message">
                        <p class="lead">Thank you for your booking at {{ $booking->hotel->name ?? 'Tanjung Lesung' }}.</p>
                        <p>Your booking has been confirmed. We've sent a confirmation email to <strong>{{ $booking->email }}</strong> with all the details.</p>
                        <div class="booking-reference">
                            <span>Booking Reference:</span>
                            <strong>{{ $booking->code }}</strong>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="booking-info-section">
                                <h4>Stay Information</h4>
                                <div class="info-grid">
                                    <div class="info-row">
                                        <div class="info-label">Check-in:</div>
                                        <div class="info-value">{{ \Carbon\Carbon::parse($booking->check_in)->format('D, d M Y') }} <span class="text-muted">(from 14:00)</span></div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Check-out:</div>
                                        <div class="info-value">{{ \Carbon\Carbon::parse($booking->check_out)->format('D, d M Y') }} <span class="text-muted">(until 11:00)</span></div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Duration:</div>
                                        <div class="info-value">{{ $booking->total_night }} {{ $booking->total_night > 1 ? 'nights' : 'night' }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Guests:</div>
                                        <div class="info-value">{{ $booking->adults }} {{ $booking->adults > 1 ? 'adults' : 'adult' }}{{ $booking->child > 0 ? ' + ' . $booking->child . ' ' . ($booking->child > 1 ? 'children' : 'child') : '' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="booking-info-section">
                                <h4>Guest Information</h4>
                                <div class="info-grid">
                                    <div class="info-row">
                                        <div class="info-label">Name:</div>
                                        <div class="info-value">{{ $booking->first_name }} {{ $booking->last_name }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Email:</div>
                                        <div class="info-value">{{ $booking->email }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label">Phone:</div>
                                        <div class="info-value">{{ $booking->phone }}</div>
                                    </div>
                                    @if($booking->country)
                                    <div class="info-row">
                                        <div class="info-label">Country:</div>
                                        <div class="info-value">{{ $booking->country }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="booking-details mt-4">
                        <h4>Room & Services</h4>
                        <div class="room-details">
                            <div class="room-info">
                                <h5>{{ $roomName ?? $booking->room->name ?? 'Standard Room' }}</h5>
                                <div class="package-badge">{{ $booking->package->name ?? 'Standard Package' }}</div>
                            </div>

                            <!-- Tampilkan addon dengan benar -->
                            @if($booking->addons->count() > 0)
                            <div class="addons-list mt-3">
                                <h6>Additional Services:</h6>
                                <ul class="list-unstyled">
                                    @foreach($booking->addons as $addon)
                                    <li>
                                        <i class="fas fa-check text-success me-2"></i>
                                        {{ $addon->name }}
                                        @if($addon->pivot->quantity > 1)
                                        <span class="badge bg-secondary">x{{ $addon->pivot->quantity }}</span>
                                        @endif
                                        <span class="text-muted">(Rp{{ number_format($addon->pivot->total_price, 0, ',', '.') }})</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            
                            @if($booking->additional_request)
                            <div class="special-request mt-3">
                                <h6>Special Requests:</h6>
                                <p class="text-muted">{{ $booking->additional_request }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Price Details Section -->
                    <div class="booking-details mt-4">
                        <h4>Price Details</h4>
                        <div class="price-details">
                            <div class="price-row">
                                <div class="price-label">Room Rate ({{ $booking->total_night }} {{ $booking->total_night > 1 ? 'nights' : 'night' }})</div>
                                <div class="price-value">Rp{{ number_format($booking->actual_price, 0, ',', '.') }}</div>
                            </div>
                            
                            @if($booking->package_price > 0)
                            <div class="price-row">
                                <div class="price-label">Package: {{ $booking->package->name }}</div>
                                <div class="price-value">Rp{{ number_format($booking->package_price, 0, ',', '.') }}</div>
                            </div>
                            @endif
                            
                            @if($booking->addons->count() > 0)
                            <div class="price-row">
                                <div class="price-label">Additional Services</div>
                                <div class="price-value">
                                    @php
                                        $addonTotal = $booking->addons->sum(function($addon) {
                                            return $addon->pivot->total_price;
                                        });
                                    @endphp
                                    Rp{{ number_format($addonTotal, 0, ',', '.') }}
                                </div>
                            </div>
                            @endif
                            
                            @if($booking->discount > 0)
                            <div class="price-row discount-row">
                                <div class="price-label">
                                    Discount 
                                    @if($booking->promoCode)
                                        (Promo: {{ $booking->promoCode->code }})
                                    @endif
                                </div>
                                <div class="price-value text-success">-Rp{{ number_format($booking->discount, 0, ',', '.') }}</div>
                            </div>
                            @endif
                            
                            <div class="price-row total-row">
                                <div class="price-label">Total</div>
                                <div class="price-value">Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</div>
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
                                    <div class="info-value">{{ ucfirst($booking->payment_method) }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Payment Status:</div>
                                    <div class="info-value">
                                        <span class="badge bg-{{ $booking->payment_status == 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                    </div>
                                </div>
                                @if($booking->transaction_id && $booking->transaction_id != '0')
                                <div class="info-row">
                                    <div class="info-label">Transaction ID:</div>
                                    <div class="info-value">{{ $booking->transaction_id }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Call to Action Buttons -->
                    <div class="cta-buttons mt-4 text-center">
                        <a href="{{ route('home') }}" class="btn btn-primary me-2">
                            <i class="fas fa-home me-1"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
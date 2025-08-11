<link rel="stylesheet" href="{{ asset('frontend/assets/css/print_summary.css') }}">
    <title>Booking Summary - {{ $roomType->name }}</title>

    <div class="container">
        <div class="print-header">
            <img src="{{ asset('frontend/assets/img/logotl1.png') }}" alt="Tanjung Lesung" class="print-logo">
        </div>

        <div class="booking-info-print">
            <div class="info-row-print">
                <div class="info-label-print">Check-in Date</div>
                <div class="info-value-print">{{ Carbon\Carbon::parse($check_in)->format('d M Y') }}</div>
            </div>
            <div class="info-row-print">
                <div class="info-label-print">Check-out Date</div>
                <div class="info-value-print">{{ Carbon\Carbon::parse($check_out)->format('d M Y') }}</div>
            </div>
            <div class="info-row-print">
                <div class="info-label-print">Guests</div>
                <div class="info-value-print">{{ $adults }} {{ $adults <= 1 ? 'adult' : 'adults' }}{{ $children > 0 ? ' + ' . $children . ' ' . ($children == 1 ? 'child' : 'children') : '' }}</div>
            </div>
        </div>
        
        <h2 class="print-title">Booking Summary</h2>
        
        <div class="booking-details-print">
            <div class="room-details-print">
                <div class="room-name-print">Room: {{ $roomType->name }}</div>
                <div class="package-name-print">Package: {{ $package->name }}</div>
                
                <div class="price-table-print">
                    <div class="price-row-print">
                        <div class="price-label-print">Room Rate ({{ $nights }} {{ $nights == 1 ? 'night' : 'nights' }})</div>
                        <div class="price-value-print">
                            @if($discountAmount > 0)
                                <span class="original-price">Rp{{ number_format($roomPrice * $nights, 0, ',', '.') }}</span>
                                Rp{{ number_format(($roomPrice - $discountAmount) * $nights, 0, ',', '.') }}
                            @else
                                Rp{{ number_format($roomPrice * $nights, 0, ',', '.') }}
                            @endif
                        </div>
                    </div>
                    
                    <!-- Package price if not 0 -->
                    @if($packagePrice > 0)
                    <div class="price-row-print">
                        <div class="price-label-print">Package price</div>
                        <div class="price-value-print">Rp{{ number_format($packagePrice * $nights, 0, ',', '.') }}</div>
                    </div>
                    @endif
                    
                    <!-- services/addons -->
                    <div class="services-section-print">
                        <div class="services-header-print">Add-Ons</div>
                        
                        <!-- Package included addons -->
                        @foreach($package->addons as $addon)
                        <div class="price-row-print">
                            <div class="price-label-print">{{ $addon->name }}</div>
                            <div class="price-value-print included-print">Included</div>
                        </div>
                        @endforeach
                        
                        <!-- Selected addons -->
                        @if(session('selected_addons'))
                            @foreach(session('selected_addons') as $addonId => $quantity)
                                @php
                                    $addon = App\Models\RoomAddOns::find($addonId);
                                @endphp
                                @if($addon && !in_array($addon->id, $package->addons->pluck('id')->toArray()))
                                <div class="price-row-print">
                                    <div class="price-label-print">
                                        {{ $addon->name }}
                                        @if($quantity > 1)
                                        <span class="quantity-print">× {{ $quantity }}</span>
                                        @endif
                                    </div>
                                    <div class="price-value-print">
                                        Rp{{ number_format($addon->price_type == 'per_night' ? $addon->price * $quantity * $nights : $addon->price * $quantity, 0, ',', '.') }}
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <!-- Additional services cost if any -->
                    @if($addonTotal > 0)
                    <div class="price-row-print">
                        <div class="price-label-print">Additional services</div>
                        <div class="price-value-print">Rp{{ number_format($addonTotal, 0, ',', '.') }}</div>
                    </div>
                    @endif
                    
                    <!-- Discount if any -->
                    @if($discountAmount > 0)
                    <div class="price-row-print discount-row">
                        <div class="price-label-print">
                            Discount
                            @if($promoCode)
                                <span class="promo-tag">Promo: {{ $promoCode->code }}</span>
                            @endif
                        </div>
                        <div class="price-value-print" style="color: #28a745;">-Rp{{ number_format($discountAmount * $nights, 0, ',', '.') }}</div>
                    </div>
                    @endif
                    
                    <!-- Total -->
                    <div class="price-row-print total-row-print">
                        <div class="price-label-print">Total</div>
                        <div class="price-value-print">Rp{{ number_format($totalPrice, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-print">
            <p>&copy; {{ date('Y') }} PT. Banten West Java Tourism Development</p>
        </div>
        
        <div class="print-button">
            <button class="print-btn" onclick="window.print()">Print</button>
        </div>
    </div>
    
<script>
    // Auto print when page loads (optional)
    window.addEventListener('load', function() {
        // Uncomment to auto-print when page loads
        // setTimeout(function() { window.print(); }, 500);
    });
</script>

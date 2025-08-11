@extends('frontend.main')

@section('main')

<link rel="stylesheet" href="{{ asset('frontend/assets/css/room_addons.css') }}">

<div class="top-bar">
    <div class="container">
        <div class="booking-controls">
            <div class="control-group">
                <label class="control-label">Selected Hotel:</label>
                <select class="control-input" disabled>
                    <option selected>{{ $roomType->hotel->name }}</option>
                </select>
            </div>
            
            <div class="control-group">
                <label class="control-label">Check-in Date</label>
                <input type="text" class="control-input date-input" value="{{ Carbon\Carbon::parse($check_in)->format('d M Y') }}" readonly>
            </div>
            <div class="control-group">
                <label class="control-label">Check-out Date</label>
                <input type="text" class="control-input date-input" value="{{ Carbon\Carbon::parse($check_out)->format('d M Y') }}" readonly>
            </div>
            
            <div class="control-group">
                <label class="control-label">Guests</label>
                <select class="control-input" disabled>
                    <option selected>
                        {{ $adults }} adult{{ $adults > 1 ? 's' : '' }}
                        {{ $children > 0 ? ', ' . $children . ' children' : '' }}
                    </option>
                </select>
            </div>
            @if($promoCode)
            <div class="promo-code-applied">
                <i class="fas fa-tag"></i>
                <span>Promo Code Applied: {{ $promoCode->code }} ({{ $promoCode->discount_type === 'percentage' ? $promoCode->discount_value.'%' : 'Rp'.number_format($promoCode->discount_value, 0) }} off)</span>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="main-content">
    <div class="container-fluid px-0">
        <div class="navigation-header">
            <div class="container d-flex justify-content-between align-items-center py-3">
                <a href="{{ route('room.package', [
                    'room_type_id' => $roomType->id,
                    'check_in' => $check_in ?? now()->format('d-m-Y'),
                    'check_out' => $check_out ?? now()->addDay()->format('d-m-Y'),
                    'adults' => $adults ?? 1,
                    'children' => $children ?? 0,
                    'promo_code' => $promo_code ?? ''
                ]) }}" class="back-btn">
                    <i class="fas fa-chevron-left"></i>
                    Back to room package
                </a>

                <h1 class="page-title mb-0">Room services</h1>
                <a href="{{ route('booking.details', [
                    'room_type_id' => $roomType->id,
                    'package_type' => $package->code,
                    'check_in' => $check_in ?? now()->format('d-m-Y'),
                    'check_out' => $check_out ?? now()->addDay()->format('d-m-Y'),
                    'adults' => $adults ?? 1,
                    'children' => $children ?? 0,
                    'promo_code' => $promo_code ?? '',
                    'selected_addons' => session('selected_addons', [])
                ]) }}" class="continue-btn">
                    Continue booking <i class="fas fa-chevron-right ms-2"></i>
                </a>
            </div>
            <div class="booking-step">
                <span class="step-number completed">1</span>
                <span class="step-text">Room selection</span>
                <span class="step-separator"></span>
                <span class="step-number completed">2</span>
                <span class="step-text">Packages</span>
                <span class="step-separator"></span>
                <span class="step-number active">3</span>
                <span class="step-text">Services</span>
                <span class="step-separator"></span>
                <span class="step-number ">4</span>
                <span class="step-text">Details</span>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <!-- Room booking Information -->
                    <div class="room-card">
                        <div class="room-header" style="display: flex; gap: 20px; align-items: flex-start;">
                            <!-- Image di kiri -->
                            <div class="room-image-container" style="flex-shrink: 0;">
                                @if($roomType->room->image)
                                <img src="{{ asset('upload/rooming/'.$roomType->room->image) }}" alt="{{ $roomType->name }}" class="room-image" style="width: 200px; height: 150px; object-fit: cover; border-radius: 8px;">
                                @else
                                <div class="no-image" style="width: 200px; height: 150px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 8px;">No Image</div>
                                @endif
                            </div>

                            <!-- Content di kanan -->
                            <div style="flex: 1;">
                                <!-- Title -->
                                <h2 class="room-title" style="margin: 0 0 5px 0; font-size: 28px; font-weight: bold; color: #e91e63;">{{ $roomType->name }}</h2>
                                
                                <!-- Price di bawah nama (sesuai permintaan) -->
                                <div style="margin-bottom: 15px;">
                                    @if($discountAmount > 0)
                                        <div class="original-price" style="text-decoration: line-through; color: #999; font-size: 16px;">Rp{{ number_format($roomPrice, 0, ',', '.') }}</div>
                                        <div class="discounted-price" style="font-size: 22px; font-weight: bold; color: #333; margin-top: 2px;">Rp{{ number_format($roomPrice - $discountAmount, 0, ',', '.') }}</div>
                                        <div class="discount-label" style="font-size: 12px; color: #e91e63; margin-top: 2px;">{{ $promoCode ? 'Promo: ' . $promoCode->code : 'Discount' }}</div>
                                    @else
                                        <div style="font-size: 22px; font-weight: bold; color: #333;">Rp{{ number_format($roomPrice, 0, ',', '.') }}</div>
                                    @endif
                                </div>

                                <!-- Room specs -->
                                <div class="room-specs" style="display: flex; gap: 20px; margin-bottom: 15px; color: #666;">
                                    <span><i class="fas fa-users" style="margin-right: 5px;"></i> up to {{ $roomType->room->guests_total }} guests</span>
                                    <span><i class="fas fa-expand" style="margin-right: 5px;"></i> {{ $roomType->room->size ?? 'N/A' }}</span>
                                </div>
                                
                                <!-- Amenities -->
                                <div class="amenities-grid" style="display: flex; flex-wrap: wrap; gap: 15px;">
                                    @foreach($roomType->room->facilities->take(5) as $facility)
                                    <div class="amenity-item" style="display: flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-check" style="color: #4caf50; font-size: 12px;"></i>
                                        <span style="font-size: 14px; color: #666;">{{ $facility->facilities_name }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Loop through addon categories -->
                    @foreach($addonsByCategory as $category => $addons)
                        <h2 class="section-title">{{ \App\Models\RoomAddOns::CATEGORIES[$category] ?? Str::title($category) }}</h2>
                        
                        <div class="addon-list">
                            @foreach($addons as $addon)
                            <div class="addon-card {{ in_array($addon->id, $includedAddonIds) ? 'included' : '' }} {{ isset(session('selected_addons', [])[$addon->id]) ? 'selected' : '' }}">
                                <div class="addon-card-inner">
                                    <div class="addon-image-container">
                                        @if($addon->image)
                                        <img src="{{ asset($addon->image) }}" alt="{{ $addon->name }}" class="addon-image">
                                        @else
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                        </div>
                                        @endif
                                        
                                        @if($addon->for_guests_type == 'family')
                                        <div class="addon-badge family-badge">
                                            <i class="fas fa-users"></i> Family with children
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="addon-content">
                                        <div class="addon-header">
                                            @if(in_array($addon->id, $includedAddonIds))
                                            <div class="included-badge">
                                                <i class="fas fa-check-circle"></i> Included in package
                                            </div>
                                            @endif
                                            
                                            <h3 class="addon-title">{{ $addon->name }}</h3>
                                            
                                            @if($addon->is_prepayment_required && !in_array($addon->id, $includedAddonIds))
                                            <div class="prepayment-badge">
                                                <i class="fas fa-credit-card"></i>
                                                <span>Prepayment required</span>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        @if($addon->description)
                                        <p class="addon-description">{{ $addon->description }}</p>
                                        @endif
                                        
                                        <div class="addon-meta">
                                            @if($addon->for_guests_type != 'all' || $addon->guest_count)
                                            <div class="guest-info">
                                                <i class="fas fa-user"></i>
                                                <span>
                                                    @if($addon->for_guests_type == 'specific' && $addon->guest_count)
                                                        For {{ $addon->guest_count }} {{ $addon->guest_count == 1 ? 'guest' : 'guests' }}
                                                    @else
                                                        {{ $addon->getGuestTypeTextAttribute() }}
                                                    @endif
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="addon-actions">
                                        @if(in_array($addon->id, $includedAddonIds))
                                            <div class="addon-price included-price">
                                                Service included
                                                @if($addon->price_type == 'per_night')
                                                <small>{{ $nights }} {{ $nights == 1 ? 'night' : 'nights' }}</small>
                                                @endif
                                            </div>
                                        @else
                                            <div class="addon-price">
                                                <span class="price-amount">Rp{{ number_format($addon->price, 0, ',', '.') }}</span>
                                                @if($addon->price_type == 'per_night')
                                                <small>{{ $nights }} {{ $nights == 1 ? 'night' : 'nights' }}</small>
                                                @endif
                                            </div>
                                            
                                            @php
                                                $addonQuantity = session('selected_addons', [])[$addon->id] ?? 0;
                                            @endphp
                                            
                                            <div class="addon-add">
                                                @if($addonQuantity > 0)
                                                    <div class="quantity-controls">
                                                        <form action="{{ route('room.addon.update') }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="addon_id" value="{{ $addon->id }}">
                                                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                                                            <input type="hidden" name="package_type" value="{{ $package->code }}">
                                                            <input type="hidden" name="check_in" value="{{ $check_in }}">
                                                            <input type="hidden" name="check_out" value="{{ $check_out }}">
                                                            <input type="hidden" name="adults" value="{{ $adults }}">
                                                            <input type="hidden" name="children" value="{{ $children }}">
                                                            <input type="hidden" name="promo_code" value="{{ $promo_code ?? '' }}">
                                                            <input type="hidden" name="quantity" value="{{ $addonQuantity - 1 }}">
                                                            <button type="submit" class="quantity-btn minus-btn">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </form>
                                                        
                                                        <span class="quantity">{{ $addonQuantity }}</span>
                                                        
                                                        <form action="{{ route('room.addon.update') }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="addon_id" value="{{ $addon->id }}">
                                                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                                                            <input type="hidden" name="package_type" value="{{ $package->code }}">
                                                            <input type="hidden" name="check_in" value="{{ $check_in }}">
                                                            <input type="hidden" name="check_out" value="{{ $check_out }}">
                                                            <input type="hidden" name="adults" value="{{ $adults }}">
                                                            <input type="hidden" name="children" value="{{ $children }}">
                                                            <input type="hidden" name="promo_code" value="{{ $promo_code ?? '' }}">
                                                            <input type="hidden" name="quantity" value="{{ $addonQuantity + 1 }}">
                                                            <button type="submit" class="quantity-btn plus-btn">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <form action="{{ route('room.addon.add') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="addon_id" value="{{ $addon->id }}">
                                                        <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                                                        <input type="hidden" name="package_type" value="{{ $package->code }}">
                                                        <input type="hidden" name="check_in" value="{{ $check_in }}">
                                                        <input type="hidden" name="check_out" value="{{ $check_out }}">
                                                        <input type="hidden" name="adults" value="{{ $adults }}">
                                                        <input type="hidden" name="children" value="{{ $children }}">
                                                        <input type="hidden" name="promo_code" value="{{ $promo_code ?? '' }}">
                                                        <button type="submit" class="add-btn">Add</button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                
                <!-- My Booking Sidebar -->
                <div class="col-lg-3">
                    <div class="booking-summary">
                        <div class="booking-summary-header">
                            <h2 class="booking-title">My booking</h2>
                        </div>
                        
                        <div class="booking-summary-content">
                            <!-- Nights count and check-in/check-out dates -->
                            <div class="booking-dates-card">
                                <div class="nights-count">{{ $nights }} {{ $nights == 1 ? 'night' : 'nights' }}</div>
                                <div class="dates-display">
                                    <div class="date-column">
                                        <div class="date">
                                            <span class="day">{{ Carbon\Carbon::parse($check_in)->format('d') }}</span>
                                            <span class="month">{{ Carbon\Carbon::parse($check_in)->format('M') }}</span>
                                        </div>
                                        <div class="day-name">{{ Carbon\Carbon::parse($check_in)->format('l') }}</div>
                                        <div class="time">from 14:00</div>
                                    </div>
                                    <div class="date-separator">—</div>
                                    <div class="date-column">
                                        <div class="date">
                                            <span class="day">{{ Carbon\Carbon::parse($check_out)->format('d') }}</span>
                                            <span class="month">{{ Carbon\Carbon::parse($check_out)->format('M') }}</span>
                                        </div>
                                        <div class="day-name">{{ Carbon\Carbon::parse($check_out)->format('l') }}</div>
                                        <div class="time">till 11:00</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Room details and pricing -->
                            <div class="booking-details-card">
                                <div class="room-summary">
                                    <div class="room-summary-header" data-bs-toggle="collapse" data-bs-target="#roomDetails">
                                        <div class="room-summary-title">Room: <span class="room-name">{{ $roomType->name }}</span></div>
                                        <div class="price">Rp{{ number_format($pricePerNight * $nights, 0, ',', '.') }}</div>
                                    </div>
                                    
                                    <div id="roomDetails" class="collapse">
                                        <div class="occupancy-info">
                                            <div>{{ $adults }} {{ $adults == 1 ? 'adult' : 'adults' }}{{ $children > 0 ? ' + ' . $children . ' ' . ($children == 1 ? 'child' : 'children') : '' }}</div>
                                        </div>
                                        
                                        <div class="room-rate-info">
                                            <div>Room rate</div>
                                            <div class="price">Rp{{ number_format($roomPrice, 0, ',', '.') }}/night</div>
                                        </div>
                                        
                                        <div class="package-info">
                                            <div>{{ $package->name }}</div>
                                            <div class="price">{{ $packagePrice > 0 ? '+' : '' }}Rp{{ number_format($packagePrice, 0, ',', '.') }}</div>
                                        </div>
                                        
                                        @if($totalDiscount > 0)
                                        <div class="discount-info">
                                            <div>Discount</div>
                                            <div class="price text-success">-Rp{{ number_format($totalDiscount, 0, ',', '.') }}</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="services-summary">
                                    <h3 class="services-heading">Services</h3>
                                    <div class="services-list">
                                        @foreach($package->addons as $addon)
                                        <div class="service-item">
                                            <div class="service-name">{{ $addon->name }}</div>
                                            <div class="service-status">Included</div>
                                        </div>
                                        @endforeach
                                        
                                        @if(session('selected_addons'))
                                            @foreach(session('selected_addons') as $addonId => $quantity)
                                                @php
                                                    $addon = \App\Models\RoomAddOns::find($addonId);
                                                @endphp
                                                @if($addon)
                                                <div class="service-item">
                                                    <div class="service-name">
                                                        {{ $addon->name }}
                                                        @if($quantity > 1)
                                                        <span class="quantity-label">x{{ $quantity }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="service-price-container">
                                                        <div class="service-price">
                                                            Rp{{ number_format($addon->price_type == 'per_night' ? $addon->price * $quantity * $nights : $addon->price * $quantity, 0, ',', '.') }}
                                                        </div>
                                                        <form action="{{ route('room.addon.update') }}" method="POST" class="d-inline ms-2">
                                                            @csrf
                                                            <input type="hidden" name="addon_id" value="{{ $addon->id }}">
                                                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                                                            <input type="hidden" name="package_type" value="{{ $package->code }}">
                                                            <input type="hidden" name="check_in" value="{{ $check_in }}">
                                                            <input type="hidden" name="check_out" value="{{ $check_out }}">
                                                            <input type="hidden" name="adults" value="{{ $adults }}">
                                                            <input type="hidden" name="children" value="{{ $children }}">
                                                            <input type="hidden" name="promo_code" value="{{ $promo_code ?? '' }}">
                                                            <input type="hidden" name="quantity" value="0">
                                                            <button type="submit" class="btn-remove" title="Remove">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                                @if($addon->price_type == 'per_night')
                                                <div class="service-detail">
                                                    <small class="text-muted">Rp{{ number_format($addon->price * $quantity, 0, ',', '.') }} × {{ $nights }} {{ $nights == 1 ? 'night' : 'nights' }}</small>
                                                </div>
                                                @endif
                                                @endif
                                            @endforeach
                                        @else
                                            @if($package->addons->isEmpty())
                                            <div class="service-item no-services">
                                                <em>No services selected</em>
                                            </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Subtotal and discount summary -->
                            <div class="price-summary">
                                <div class="price-row">
                                    <div class="price-label">Room ({{ $nights }} {{ $nights == 1 ? 'night' : 'nights' }})</div>
                                    <div class="price-value">Rp{{ number_format($pricePerNight * $nights, 0, ',', '.') }}</div>
                                </div>
                                
                                @if($addonTotal > 0)
                                <div class="price-row">
                                    <div class="price-label">Additional services</div>
                                    <div class="price-value">Rp{{ number_format($addonTotal, 0, ',', '.') }}</div>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Total price and continue button -->
                            <div class="booking-total">
                                <div class="total-label">Total</div>
                                <div class="total-price">Rp{{ number_format($totalPrice, 0, ',', '.') }}</div>
                            </div>
                            
                            <div class="booking-actions">
                                <a href="{{ route('booking.details', [
                                    'room_type_id' => $roomType->id,
                                    'package_type' => $package->code,
                                    'check_in' => $check_in,
                                    'check_out' => $check_out,
                                    'adults' => $adults,
                                    'children' => $children,
                                    'promo_code' => $promo_code ?? '',
                                    'selected_addons' => session('selected_addons', [])
                                ]) }}" class="continue-booking-btn">
                                    Continue <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle collapse icon rotation
        const roomDetailsTrigger = document.querySelector('.room-summary-header');
        const roomDetailsCollapse = document.getElementById('roomDetails');
        const toggleIcon = document.querySelector('.toggle-icon');
        
        if (roomDetailsTrigger && roomDetailsCollapse && toggleIcon) {
            roomDetailsCollapse.addEventListener('show.bs.collapse', function() {
                toggleIcon.style.transform = 'rotate(180deg)';
            });
            
            roomDetailsCollapse.addEventListener('hide.bs.collapse', function() {
                toggleIcon.style.transform = 'rotate(0)';
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Toggle collapse for room details
        const roomDetailsCollapse = document.getElementById('roomDetails');
        const roomSummaryHeader = document.querySelector('.room-summary-header');
        
        if (roomDetailsCollapse && roomSummaryHeader) {
            roomDetailsCollapse.addEventListener('show.bs.collapse', function() {
                roomSummaryHeader.setAttribute('aria-expanded', 'true');
            });
            
            roomDetailsCollapse.addEventListener('hide.bs.collapse', function() {
                roomSummaryHeader.setAttribute('aria-expanded', 'false');
            });
        }
    });
</script>
@endsection
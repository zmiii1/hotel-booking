@extends('frontend.main')
@section('main')

<link rel="stylesheet" href="{{ asset('frontend/assets/css/room_package.css') }}">

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
    <div class="container">
        <a href="{{ route('room.reservation') }}" class="back-btn">
            <i class="fas fa-chevron-left"></i>
            Back to rooms
        </a>

        <h1 class="page-title">Select a room package</h1>

        <div class="booking-step">
            <span class="step-number completed">1</span>
            <span class="step-text">Room selection</span>
            <span class="step-separator"></span>
            <span class="step-number active">2</span>
            <span class="step-text">Packages</span>
            <span class="step-separator"></span>
            <span class="step-number">3</span>
            <span class="step-text">Services</span>
            <span class="step-separator"></span>
            <span class="step-number ">4</span>
            <span class="step-text">Details</span>
        </div>

        <div class="divider"></div>
        
        <div class="room-container">
            <div class="room-header" style="display: flex; gap: 20px; align-items: flex-start;">
                <div class="room-image-container">
                    <img src="{{asset('upload/rooming/'.$roomType->room->image)}}" alt="{{ $roomType->name }}" class="room-image">
                    {{-- <div class="photo-count">
                        <i class="fas fa-camera"></i>
                        {{ count($roomType->room->multiImages) + 1 }} photos
                    </div> --}}
                </div>
                
                <div class="room-details">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h2 class="room-title">{{ $roomType->name }}</h2>
                            
                            <div class="room-specs">
                                <span><i class="fas fa-users"></i> up to {{ $roomType->room->guests_total }} guests</span>
                                <span><i class="fas fa-expand"></i> {{ $roomType->room->size ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="amenities-grid">
                                @foreach($roomType->room->facilities->take(5) as $facility)
                                <div class="amenity-item">
                                    <i class="fas fa-check"></i>
                                    <span>{{ $facility->facilities_name }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="package-list">
                @foreach($roomPackages as $package)
                <!-- {{ $package->name }} Package -->
                <div class="package-card">
                    <div class="package-header">
                        <div class="package-info">
                            <div class="package-type">
                                <i class="fas {{ $package->code == 'ROOM_BREAKFAST' ? 'fa-utensils' : 'fa-bed' }} package-icon"></i>
                                <span class="package-name">{{ $package->name }}</span>
                                <a href="#" class="package-show-more" onclick="togglePackageDetails(event, '{{ Str::slug($package->code) }}')">
                                    Show more <i class="fas fa-chevron-down"></i>
                                </a>
                            </div>
                            
                            @if($package->code == 'ROOM_BREAKFAST')
                            <div class="breakfast-included">
                                <i class="fas fa-utensils"></i>
                                <span>Breakfast INCLUDED</span>
                            </div>
                            @endif
                            
                            <div class="included-items">
                                <i class="fas fa-gift"></i>
                                <span>INCLUDED: 
                                    @php
                                        $inclusions = $package->getInclusionsArrayAttribute();
                                        echo implode(', ', array_slice($inclusions, 0, 3));
                                        if (count($inclusions) > 3) {
                                            echo ' and more';
                                        }
                                    @endphp
                                </span>
                            </div>
                        </div>
                        
                        <div class="price-section">
                            <div class="price-label">Price for 1 night</div>
                            
                            @if($promoCode || ($package->code == 'ROOM_BREAKFAST'))
                            <div class="discount-info">
                                @if($package->code == 'ROOM_BREAKFAST')
                                <span class="discount-badge">-{{ round((1 - $package->final_price / ($package->original_price + $package->price_adjustment)) * 100) }}%</span>
                                @endif
                                <span class="original-price">Rp{{ number_format($package->original_price + ($package->code == 'ROOM_BREAKFAST' ? $package->price_adjustment : 0), 0, ',', '.') }}</span>
                            </div>
                            @endif
                            
                            <div class="current-price">
                                <i class="fas fa-users"></i>
                                <span class="price-amount">Rp{{ number_format($package->final_price, 0, ',', '.') }}</span>
                                <i class="fas fa-info-circle info-icon"></i>
                            </div>
                            
                            <a href="{{ route('room.addons', [
                                'room_type_id' => $roomType->id,
                                'package_type' => $package->code,
                                'check_in' => $check_in,
                                'check_out' => $check_out,
                                'adults' => $adults,
                                'children' => $children,
                                'promo_code' => $promo_code ?? ''
                            ]) }}" class="select-btn">Select</a>
                        </div>
                    </div>
                    
                    <div class="package-details" id="{{ Str::slug($package->code) }}-details">
                        <div class="details-section">
                            <div class="details-title">The price included:</div>
                            <ul class="details-list">
                                @foreach($package->getInclusionsArrayAttribute() as $inclusion)
                                <li>{{ $inclusion }}</li>
                                @endforeach
                            </ul>
                        </div>
                        
                        {{-- <div class="details-section">
                            <div class="details-title">Amenities</div>
                            <ul class="details-list">
                                @foreach($package->getAmenitiesArrayAttribute() as $amenity)
                                <li>{{ $amenity }}</li>
                                @endforeach
                            </ul>
                        </div> --}}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    function toggleRoomDetails(e) {
        e.preventDefault();
        const icon = e.target.querySelector('i') || e.target.parentElement.querySelector('i');
        if (icon.classList.contains('fa-chevron-down')) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
            e.target.innerHTML = 'Show less <i class="fas fa-chevron-up"></i>';
        } else {
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
            e.target.innerHTML = 'Show more <i class="fas fa-chevron-down"></i>';
        }
    }
    
    function togglePackageDetails(e, packageId) {
        e.preventDefault();
        const details = document.getElementById(packageId + '-details');
        const icon = e.target.querySelector('i') || e.target.parentElement.querySelector('i');
        
        if (details.classList.contains('show')) {
            details.classList.remove('show');
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
            e.target.innerHTML = 'Show more <i class="fas fa-chevron-down"></i>';
        } else {
            details.classList.add('show');
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
            e.target.innerHTML = 'Show less <i class="fas fa-chevron-up"></i>';
        }
    }
</script>
@endsection
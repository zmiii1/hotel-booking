@extends('frontend.main')
@section('main')

<link rel="stylesheet" href="{{ asset('frontend/assets/css/room_reservation.css') }}">

<!-- Hero Section -->
<section class="hero-section" id="home">
    <div class="hero-content">
        <h1 class="background-image">Room Reservation</h1>
        <p class="lead">Book your perfect stay with us</p>
    </div>
</section>

<!-- Booking Card -->
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <form id="reservationForm" action="{{ route('check.availability') }}" method="GET">
            <div class="booking-card">
                <h3 class="text-center mb-4">Detail of the reservation</h3>
                <div class="row g-3">
                    @if(isset($hotels) && $hotels->count() > 0)
                    <div class="col-md-3">
                        <label class="form-label">Select Hotel</label>
                        <select class="form-select" name="hotel_slug" id="hotelSelect" required>
                            <option value="">Select a Hotel</option>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->slug }}" 
                                    {{ (isset($form_data['hotel_slug']) && $form_data['hotel_slug'] == $hotel->slug) ? 'selected' : '' }}>
                                    {{ $hotel->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    <div class="col-md-2">
                        <label class="form-label">Check-In Date</label>
                        <div class="date-picker">
                            <input autocomplete="off" type="text" required name="check_in" 
                                class="form-control dt_picker" placeholder="dd-mm-yy" id="checkin"
                                value="{{ isset($form_data['check_in']) ? $form_data['check_in'] : '' }}">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Check-Out Date</label>
                        <div class="date-picker">
                            <input autocomplete="off" type="text" required name="check_out" 
                                class="form-control dt_picker" placeholder="dd-mm-yy" id="checkout"
                                value="{{ isset($form_data['check_out']) ? $form_data['check_out'] : '' }}">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Guests</label>
                        <div class="guests-field">
                            <div class="guests-display form-control d-flex justify-content-between align-items-center" id="guestsDisplay">
                                <span id="guestsText">
                                    {{ isset($form_data['adults']) ? $form_data['adults'] : 1 }} 
                                    {{ isset($form_data['adults']) && $form_data['adults'] == 1 ? 'adult' : 'adults' }}, 
                                    {{ isset($form_data['children']) ? $form_data['children'] : 0 }} 
                                    {{ isset($form_data['children']) && $form_data['children'] == 1 ? 'child' : 'children' }}
                                </span>
                                <i class="fa-solid fa-user-group" style="color: #DC1C6C;"></i>
                            </div>
                            <div class="guest-dropdown" id="guestDropdown">
                                <div class="guest-modal-header">
                                    <h5 class="guest-modal-title">Guests</h5>
                                    <hr class="modal-title-divider">
                                </div>
                                
                                <div id="guestRoomsContainer">
                                    <div class="guest-room">
                                        <div class="guest-inputs-container">
                                            <div class="guest-input-group">
                                                <label>Adults</label>
                                                <div class="counter-control-box">
                                                    <button class="counter-btn" data-room="1" data-type="adults" data-action="decrement">−</button>
                                                    <span class="counter-value" id="adultsCount-1">{{ isset($form_data['adults']) ? $form_data['adults'] : 1 }}</span>
                                                    <button class="counter-btn" data-room="1" data-type="adults" data-action="increment">+</button>
                                                </div>
                                            </div>
                                            
                                            <div class="guest-input-group">
                                                <label>Child</label>
                                                <div class="counter-control-box">
                                                    <button class="counter-btn" data-room="1" data-type="children" data-action="decrement">−</button>
                                                    <span class="counter-value" id="childrenCount-1">{{ isset($form_data['children']) ? $form_data['children'] : 0 }}</span>
                                                    <button class="counter-btn" data-room="1" data-type="children" data-action="increment">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="modal-footer-custom">
                                    <button class="done-btn" id="guestDoneBtn">Done</button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="adults" id="adultsInput" value="{{ isset($form_data['adults']) ? $form_data['adults'] : 1 }}">
                        <input type="hidden" name="children" id="childrenInput" value="{{ isset($form_data['children']) ? $form_data['children'] : 0 }}">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Promo Code</label>
                        <input type="text" name="promo_code" class="form-control" placeholder="Enter promo code"
                               value="{{ isset($form_data['promo_code']) ? $form_data['promo_code'] : '' }}">
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn-check-availability w-100">
                            <i class="bi bi-search"></i>
                            <span>Check Availability</span>
                        </button>
                    </div>
                    <div class="booking-step">
                        <span class="step-number active">1</span>
                        <span class="step-text">Room selection</span>
                        <span class="step-separator"></span>
                        <span class="step-number">2</span>
                        <span class="step-text">Packages</span>
                        <span class="step-separator"></span>
                        <span class="step-number">3</span>
                        <span class="step-text">Services</span>
                        <span class="step-separator"></span>
                        <span class="step-number ">4</span>
                        <span class="step-text">Details</span>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Room Selection -->
<div class="container mt-5">
    <h2 class="text-center mb-5">Select Room</h2>
    
    @if(isset($roomTypes) && $roomTypes->count() > 0)
        <div class="row" id="roomContainer">
            @foreach($roomTypes as $index => $roomType)
                @if($roomType->room)
                    <div class="col-12 mb-4">
                        <!-- Simple Bootstrap Card -->
                        <div class="card border shadow-sm">
                            <div class="row g-0">
                                <!-- Image Column -->
                                <div class="col-md-4">
                                    @if($roomType->room->image)
                                        <img src="{{ asset('upload/rooming/'.$roomType->room->image) }}" 
                                             class="img-fluid rounded-start h-100" 
                                             style="object-fit: cover; min-height: 300px;" 
                                             alt="{{ $roomType->name }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded-start" 
                                             style="height: 300px;">
                                            <span class="text-muted">No Image</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Content Column -->
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <!-- Room Title -->
                                        <h5 class="card-title text-primary">{{ $roomType->name }}</h5>
                                        <h6 class="card-subtitle mb-2 text-muted">
                                            <i class="bi bi-building"></i> {{ $roomType->hotel->name ?? 'No Hotel' }}
                                        </h6>
                                        
                                        <!-- Room Description -->
                                        <p class="card-text">{{ $roomType->room->description ?? 'No description available' }}</p>
                                        
                                        <!-- Room Specifications -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <ul class="list-unstyled">
                                                    <li><i class="bi bi-people text-primary"></i> <strong>Up to:</strong> {{ $roomType->room->guests_total ?? 'N/A' }} guests</li>
                                                    <li><i class="bi bi-rulers text-primary"></i> <strong>Size:</strong> {{ $roomType->room->size ?? 'N/A' }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <!-- Facilities Preview -->
                                        @if($roomType->room->facilities && $roomType->room->facilities->count() > 0)
                                        <div class="mb-3">
                                            <h6><i class="bi bi-gear text-primary"></i> Facilities:</h6>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($roomType->room->facilities->take(6) as $facility)
                                                    <span class="badge bg-light text-dark border">{{ $facility->facilities_name }}</span>
                                                @endforeach
                                                @if($roomType->room->facilities->count() > 6)
                                                    <span class="badge bg-secondary">+{{ $roomType->room->facilities->count() - 6 }} more</span>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <!-- Price and Select Button -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if(isset($promo_code) && $roomType->room->promo_discount > 0)
                                                    <!-- Show discounted price with promo code -->
                                                    <span class="text-decoration-line-through text-muted">Rp{{ number_format($roomType->room->price, 0, ',', '.') }}</span><br>
                                                    <span class="h5 text-success mb-0">Rp{{ number_format($roomType->room->price - $roomType->room->promo_discount, 0, ',', '.') }}</span>
                                                    <small class="text-success d-block">Promo: {{ $promo_code->code }}</small>
                                                @elseif($roomType->room->discount > 0)
                                                    <!-- Show regular discount -->
                                                    <span class="text-decoration-line-through text-muted">Rp{{ number_format($roomType->room->price, 0, ',', '.') }}</span><br>
                                                    <span class="h5 text-success mb-0">Rp{{ number_format($roomType->room->price - $roomType->room->discount, 0, ',', '.') }}</span>
                                                @else
                                                    <!-- Show regular price -->
                                                    <span class="h5 text-primary mb-0">Rp{{ number_format($roomType->room->price ?? 0, 0, ',', '.') }}</span>
                                                @endif
                                                <small class="text-muted d-block">/per night</small>
                                            </div>
                                            
                                            <button type="button" class="btn btn-primary btn-lg px-4" 
                                                onclick="selectRoom('{{ $roomType->id }}')">
                                                <i class="bi bi-check-circle me-2"></i>SELECT ROOM
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <div class="col-12">
            <div class="text-center p-5">
                <div class="mb-4">
                    <i class="bi bi-house-x display-1 text-muted"></i>
                </div>
                <h4 class="text-muted">No rooms available</h4>
                <p class="text-muted">Please select your search criteria above to view available rooms.</p>
            </div>
        </div>
    @endif
</div>

<!-- Form for room selection -->
<form id="roomSelectionForm" action="{{ route('room.package') }}" method="GET" style="display: none;">
    <input type="hidden" name="hotel_slug" value="{{ isset($form_data['hotel_slug']) ? $form_data['hotel_slug'] : '' }}">
    <input type="hidden" name="check_in" value="{{ isset($form_data['check_in']) ? $form_data['check_in'] : '' }}">
    <input type="hidden" name="check_out" value="{{ isset($form_data['check_out']) ? $form_data['check_out'] : '' }}">
    <input type="hidden" name="adults" value="{{ isset($form_data['adults']) ? $form_data['adults'] : 1 }}">
    <input type="hidden" name="children" value="{{ isset($form_data['children']) ? $form_data['children'] : 0 }}">
    <input type="hidden" name="promo_code" value="{{ isset($form_data['promo_code']) ? $form_data['promo_code'] : '' }}">
    <input type="hidden" name="room_type_id" id="selectedRoomTypeId">
</form>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Guest counter functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Handle guest counter buttons
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('counter-btn')) {
                e.preventDefault();
                const btn = e.target;
                const room = btn.getAttribute('data-room');
                const type = btn.getAttribute('data-type');
                const action = btn.getAttribute('data-action');
                const counter = document.getElementById(`${type}Count-${room}`);
                const hiddenInput = document.getElementById(`${type}Input`);
                
                let value = parseInt(counter.textContent);
                
                if (action === 'increment') {
                    value++;
                } else if (action === 'decrement' && value > (type === 'adults' ? 1 : 0)) {
                    value--;
                }
                
                counter.textContent = value;
                hiddenInput.value = value;
                
                // Update guests text
                const adults = parseInt(document.getElementById('adultsInput').value);
                const children = parseInt(document.getElementById('childrenInput').value);
                document.getElementById('guestsText').textContent = 
                    `${adults} ${adults === 1 ? 'adult' : 'adults'}, ${children} ${children === 1 ? 'child' : 'children'}`;
            }
            
            // Close guest dropdown when clicking outside
            if (!e.target.closest('.guests-field')) {
                const dropdown = document.getElementById('guestDropdown');
                if (dropdown) {
                    dropdown.style.display = 'none';
                }
            }
        });
        
        // Toggle guest dropdown
        const guestsDisplay = document.getElementById('guestsDisplay');
        if (guestsDisplay) {
            guestsDisplay.addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdown = document.getElementById('guestDropdown');
                if (dropdown) {
                    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                }
            });
        }
        
        // Close dropdown when done button is clicked
        const guestDoneBtn = document.getElementById('guestDoneBtn');
        if (guestDoneBtn) {
            guestDoneBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const dropdown = document.getElementById('guestDropdown');
                if (dropdown) {
                    dropdown.style.display = 'none';
                }
            });
        }
    });

    function selectRoom(roomTypeId) {
        console.log('Selecting room:', roomTypeId);
        
        // Set room type ID in the hidden form
        document.getElementById('selectedRoomTypeId').value = roomTypeId;
        
        // Submit the form
        document.getElementById('roomSelectionForm').submit();
    }
    // Add this to your existing JavaScript
    $(document).ready(function() {
        // Format promo code to uppercase
        $('input[name="promo_code"]').on('input', function() {
            $(this).val($(this).val().toUpperCase());
        });
        
        // Validate form before submission
        $('form').on('submit', function(e) {
            // Make sure dates are valid if they're filled
            var checkin = $(this).find('input[name="check_in"]').val();
            var checkout = $(this).find('input[name="check_out"]').val();
            
            if (checkin && checkout) {
                var checkinDate = new Date(checkin.split('-').reverse().join('-'));
                var checkoutDate = new Date(checkout.split('-').reverse().join('-'));
                
                if (checkinDate >= checkoutDate) {
                    e.preventDefault();
                    alert('Check-out date must be after check-in date');
                    return false;
                }
            }
        });
    });
</script>

@endsection
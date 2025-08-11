@extends('frontend.main')
@section('main')

    <!-- Demo banner area -->
    <div class="banner-area">
        <div class="owl-carousel owl-theme banner-slider">
            <div class="item">
                <img src="{{ asset('frontend/assets/img/slider/DSC09913.jpg') }}" alt="img1" />
            </div>
            <div class="item">
                <img src="{{ asset('frontend/assets/img/slider/DSC09925.jpg') }}" alt="img2" />
            </div>
            <div class="item">
                <img src="{{ asset('frontend/assets/img/slider/DSC09871.jpg') }}" alt="img3" />
            </div>
            <div class="item">
                <img src="{{ asset('frontend/assets/img/slider/DSC09949.jpg') }}" alt="img3" />
            </div>
        </div>
    </div>
    <!-- Banner Form Section -->
    <section class="banner-form" id="bannerFormSection">
        <div class="container-fluid">
            <form id="bannerReservationForm" action="{{ route('check.availability') }}" method="GET">
                <div class="row g-3">
                    <!-- Hotel Field -->
                    <div class="col-lg-2 col-md-6 col-12">
                        <label class="form-label text-black">Hotel</label>
                        <select class="form-select" name="hotel_slug" id="hotelSelect">
                            @foreach(\App\Models\Hotel::all() as $hotel)
                                <option value="{{ $hotel->slug }}" 
                                    {{ $hotel->slug == 'tanjung-lesung-beach' ? 'selected' : '' }}>
                                    {{ $hotel->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Check-in Field -->
                    <div class="col-lg-2 col-md-6 col-12">
                        <label class="form-label text-black">Check-in</label>
                        <div class="date-picker">
                            <input autocomplete="off" 
                                type="text" 
                                name="check_in" 
                                class="form-control dt_picker" 
                                placeholder="Select date"
                                readonly>
                            <i class="fa fa-calendar" style="color: #DC1C6C;"></i>
                        </div>
                    </div>
                    
                    <!-- Check-out Field -->
                    <div class="col-lg-2 col-md-6 col-12">
                        <label class="form-label text-black">Check-out</label>
                        <div class="date-picker">
                            <input autocomplete="off" 
                                type="text" 
                                name="check_out" 
                                class="form-control dt_picker" 
                                placeholder="Select date"
                                readonly>
                            <i class="fa fa-calendar" style="color: #DC1C6C;"></i>
                        </div>
                    </div>
                    
                    <!-- Guests Field -->
                    <div class="col-lg-2 col-md-6 col-12">
                        <label class="form-label text-black">Guests</label>
                        <div class="guests-field">
                            <div class="guests-display" id="guestsDisplay">
                                <span id="guestsText">1 adult, 0 children</span>
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
                                                    <span class="counter-value" id="adultsCount-1">1</span>
                                                    <button class="counter-btn" data-room="1" data-type="adults" data-action="increment">+</button>
                                                </div>
                                            </div>
                                            
                                            <div class="guest-input-group">
                                                <label>Child</label>
                                                <div class="counter-control-box">
                                                    <button class="counter-btn" data-room="1" data-type="children" data-action="decrement">−</button>
                                                    <span class="counter-value" id="childrenCount-1">0</span>
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
                        <input type="hidden" name="adults" id="adultsInput" value="1">
                        <input type="hidden" name="children" id="childrenInput" value="0">
                    </div>
                    
                    <!-- Promo Code -->
                    <div class="col-lg-2 col-md-6 col-12">
                        <label class="form-label text-black">Promo Code</label>
                        <div class="promo-code-container">
                            <div class="promo-code-input">
                                <input type="text" name="promo_code" class="form-control" placeholder="Enter code">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Check Availability Button -->
                    <div class="col-lg-2 col-md-12 col-12">
                        <label class="form-label" style="visibility: hidden;">Button</label>
                        <button type="submit" class="btn-primary">
                            <i class="fa fa-search me-2"></i>Check Availability
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Enhanced Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    
    <script>
        // Guest counter functionality (unchanged)
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
                    document.getElementById('guestDropdown').style.display = 'none';
                }
            });
            
            // Toggle guest dropdown
            document.getElementById('guestsDisplay').addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdown = document.getElementById('guestDropdown');
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            });
            
            // Close dropdown when done button is clicked
            document.getElementById('guestDoneBtn').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('guestDropdown').style.display = 'none';
            });
        });

        // Initialize Owl Carousel
        $(document).ready(function(){
            $('.banner-slider').owlCarousel({
                items: 1,
                loop: true,
                autoplay: true,
                autoplayTimeout: 5000,
                nav: false,
                dots: true,
                animateOut: 'fadeOut',
                animateIn: 'fadeIn'
            });
        });
    </script>

    @include('frontend.body.chatbot')
    <!-- Destination Area -->
    @include('frontend.body.destination')
    <!-- Destination Area -->
    
@endsection

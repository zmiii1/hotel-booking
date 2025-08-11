@extends('frontend.main')
@section('main')

<link rel="stylesheet" href="{{ asset('frontend/assets/css/hotelgroup.css') }}">

<!-- Hero Section -->
<section class="tl-hero-section lalassabc">
    <div class="tl-hero-content">
        <h1>LALASSA BEACH CLUB</h1>
        <p>Experience Paradise on the Pristine Shores of West Java</p>
    </div>
</section>

<!-- About Section -->
<section id="about" class="tl-about-section">
    <div class="container">
        <h2 class="tl-section-title">Welcome to Lalassa Beach Club</h2>
        <div class="tl-about-content">
            <div class="tl-about-text">
                <h3>Discover the Ultimate Beach Experience</h3>
                <p>Lalassa Beach Club provides container cabins and option Beach Camp which are attractive lodging options for those of you who like exploration or backpackers on the beach and there is a campfire area and integrated with water activities. Please contact our reservations for more information on room availability and rates.</p>
                <p> The lalassa beach club offers trips to Liwungan Island, Oar Island, Umang Island, Panaitan Island and Mount Krakatau with its beautiful charm. You can also explore the famous geopark of Ujung Kulon National Park.
With its stunning natural beauty, peaceful beach atmosphere, and various activities and facilities offered.</p>
                {{-- <div class="tl-about-features">
                    <div class="tl-feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Beachfront Location</span>
                    </div>
                    <!-- Other features... -->
                </div> --}}
            </div>
            <div class="tl-about-image lalassabc"></div>
        </div>
    </div>
</section>

<!-- Room Types Section -->
<section class="tl-rooms-section">
    <div class="container">
        <h2 class="tl-section-title mb-5">Our Room Types</h2>
        
        @php
            $hotel = App\Models\Hotel::where('slug', 'lalassabc')->first();
            $roomTypes = App\Models\RoomType::with(['room.facilities', 'room.multiImages'])
                ->where('hotel_id', $hotel->id)
                ->orderBy('id', 'desc')
                ->get();
        @endphp

        @foreach($roomTypes as $index => $roomType)
            <div class="tl-room-card {{ $index % 2 == 1 ? 'reverse' : '' }}">
                <div class="tl-room-image-wrapper">
                    <div class="tl-room-slider">
                        <div class="tl-slider-container">
                            <div class="tl-slides">
                                <!-- Main image -->
                                <img src="{{ asset('upload/rooming/'.$roomType->room->image) }}" class="tl-room-image active" alt="{{ $roomType->name }}">
                                
                                <!-- Gallery images -->
                                @foreach($roomType->room->multiImages as $image)
                                    <img src="{{ asset('upload/rooming/multi_img/'.$image->multi_images) }}" class="tl-room-image" alt="{{ $roomType->name }}">
                                @endforeach
                            </div>
                            <button class="tl-slider-arrow prev">
                                <i class="bx bx-chevron-left"></i>
                            </button>
                            <button class="tl-slider-arrow next">
                                <i class="bx bx-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="tl-room-info">
                    <h3 class="tl-room-name">{{ $roomType->name }}</h3>
                    <p class="tl-room-description">
                        {{ $roomType->room->description }}
                    </p>
                    <div class="tl-room-features">
                        @foreach($roomType->room->facilities->take(4) as $facility)
                            <span class="tl-feature-badge"><i class="bx bx-check"></i>{{ $facility->facilities_name }}</span>
                        @endforeach
                    </div>
                    <a href="{{ route('room.reservation', ['hotel' => 'lalassabc']) }}" class="tl-view-more-btn">VIEW DETAILS</a>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Facilities Section -->
<section id="facilities" class="facilities-section">
    <div class="container">
        <h2 class="tl-section-title">Resort Facilities</h2>
        <div class="facility-grid">
            <div class="facility-card">
                <i class="fas fa-swimming-pool facility-icon"></i>
                <h4>Lalassa Beach</h4>
                <p>Enjoy our infinity pool overlooking the ocean</p>
            </div>
            <div class="facility-card">
                <i class="fas fa-utensils facility-icon"></i>
                <h4>Candini Restaurant & Bar</h4>
                <p>Savor local and international cuisine</p>
            </div>
            <div class="facility-card">
                <i class="fas fa-ship facility-icon"></i>
                <h4>Water Sports</h4>
                <p>Explore exciting marine activities</p>
            </div>
            <div class="facility-card">
                <i class="fas fa-volleyball-ball facility-icon"></i>
                <h4>Beach Activities</h4>
                <p>Fun games and sports on the beach</p>
            </div>
            <div class="facility-card">
                <i class="fas fa-users facility-icon"></i>
                <h4>Event Spaces</h4>
                <p>Perfect venues for your special occasions</p>
            </div>
        </div>
    </div>
</section>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all sliders on the page
    const sliders = document.querySelectorAll('.tl-room-slider');
    
    sliders.forEach(slider => {
        const slides = slider.querySelectorAll('.tl-room-image');
        const prevButton = slider.querySelector('.tl-slider-arrow.prev');
        const nextButton = slider.querySelector('.tl-slider-arrow.next');
        let currentIndex = 0;
        
        // Show the first slide
        updateSlider();
        
        // Previous button click
        prevButton.addEventListener('click', function() {
            currentIndex = (currentIndex - 1 + slides.length) % slides.length;
            updateSlider();
        });
        
        // Next button click
        nextButton.addEventListener('click', function() {
            currentIndex = (currentIndex + 1) % slides.length;
            updateSlider();
        });
        
        function updateSlider() {
            slides.forEach((slide, index) => {
                if (index === currentIndex) {
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });
        }
    });

    // Animate room cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all room cards
    document.querySelectorAll('.tl-room-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.8s ease';
        observer.observe(card);
    });
});
</script>
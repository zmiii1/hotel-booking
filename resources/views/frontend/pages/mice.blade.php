@extends('frontend.main')
@section('main')

<link rel="stylesheet" href="{{ asset('frontend/assets/css/meeting.css') }}">

<!-- Meeting & Wedding Section -->
<div id="meeting" class="meeting-section meeting-page">
    <div class="container">

        <!-- MEETING ROOM SECTION -->
        <h1 class="meeting-section-title mb-4">MEETING ROOM</h1>
        <p>Improve your team's collaboration and productivity</p>

        <div class="row justify-content-center g-4">
            <div class="col-md-6">
                <div class="meeting-option fade-in">
                    <h3>MEETING ROOM ONLY</h3>
                    <h4 class="price-amount">IDR. 3.000.000,-</h4>
                    <ul>
                        <li>Capacity 50-300 pax</li>
                        <li>Standard Amenities</li>
                        <li>Standard Meeting Facilities</li>
                        <li>6 Hours</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="meeting-option fade-in">
                    <h3>MEETING ROOM</h3>
                    <h4 class="price-amount">IDR. 250.000,-/PAX</h4>
                    <ul>
                        <li>Min 20 pax</li>
                        <li>Standard Amenities</li>
                        <li>Standard Meeting Facilities</li>
                        <li>1x Lunch & Coffee Break</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Meeting Room Image -->
        <div class="text-center my-5">
            <img src="{{asset('frontend/assets/img/MICE/IMG_2682-scaled.png')}}" alt="Meeting Room" class="img-fluid rounded meeting-img">
            <a href="{{ \App\Helpers\WhatsAppHelper::generateMeetingRoomLink('Meeting Room Only', 'IDR. 3.000.000,-', '50-300 pax') }}" 
                class="btn-primary" target="_blank">RESERVE</a>
        </div>

        <!-- WEDDING SECTION -->
        <h1 class="section-title mt-5 mb-4">PREWEDDING & WEDDING</h1>

        <!-- Wedding Carousel -->
        <div id="weddingCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{asset('frontend/assets/img/MICE/prewed image slider/MG_2615-scaled.jpg')}}" class="d-block w-100 rounded" alt="Wedding Photo 1">
                </div>
                <div class="carousel-item">
                    <img src="{{asset('frontend/assets/img/MICE/prewed image slider/MG_2642-scaled.jpg')}}" class="d-block w-100 rounded" alt="Wedding Photo 2">
                </div>
                <div class="carousel-item">
                    <img src="{{asset('frontend/assets/img/MICE/prewed image slider/MG_2642-scaled.jpg')}}" class="d-block w-100 rounded" alt="Wedding Photo 3">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#weddingCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#weddingCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>

        <!-- Wedding Packages -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="room-card fade-in text-center">
                    <h5 class="room-title">Beach Wedding</h5>
                    <p class="room-description">Magical ceremony on pristine beaches with sunset view.</p>
                    <div class="price-amount">IDR 35.000.000,-</div>
                    <a href="{{ \App\Helpers\WhatsAppHelper::generateWeddingLink('Beach Wedding', 'IDR 35.000.000,-') }}" 
                        class="btn-primary" target="_blank">More Details</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="room-card fade-in text-center">
                    <h5 class="room-title">Garden Wedding</h5>
                    <p class="room-description">Vows surrounded by tropical gardens and ocean view.</p>
                    <div class="price-amount">IDR 25.000.000,-</div>
                    <a href="{{ \App\Helpers\WhatsAppHelper::generateWeddingLink('Beach Wedding', 'IDR 35.000.000,-') }}" 
                        class="btn-primary" target="_blank">More Details</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="room-card fade-in text-center">
                    <h5 class="room-title">Ballroom Wedding</h5>
                    <p class="room-description">Elegant indoor setting with modern amenities.</p>
                    <div class="price-amount">IDR 45.000.000,-</div>
                    <a href="{{ \App\Helpers\WhatsAppHelper::generateWeddingLink('Beach Wedding', 'IDR 35.000.000,-') }}" 
                        class="btn-primary" target="_blank">More Details</a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Optional: Add JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Fade-in animation
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });
});
</script>

@endsection

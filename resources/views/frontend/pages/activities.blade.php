@extends('frontend.main')
@section('main')

<link rel="stylesheet" href="{{ asset('frontend/assets/css/activities.css') }}">

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Tanjung Lesung Adventures</h1>
            <p>Discover the pristine beauty of Banten's coastline with stunning beaches, crystal-clear waters, vibrant coral reefs, and rich cultural heritage that creates unforgettable memories.</p>
        </div>
        <div class="scroll-indicator">
            Scroll to explore
        </div>
    </section>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Land Activities Section -->
        <section class="section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Land Adventures</h2>
                    <p class="section-subtitle">Explore diverse terrains and experience thrilling land-based activities</p>
                     <a href="{{ \App\Helpers\WhatsAppHelper::generateLandActivityLink('Adult MTB Bike', '1 Hour', '65K IDR') }}" 
                        class="cta-button" target="_blank">More Info</a>
                </div>
                
                <div class="activities-grid">
                    <div class="activity-card">
                        <div class="activity-badge">Adult MTB Bike</div>
                        <div class="activity-duration">1 Hour</div>
                        <div class="activity-price">65K IDR</div>
                        <div class="activity-desc">Perfect for a quick adventure through scenic trails and coastal paths</div>
                    </div>
                    
                    <div class="activity-card">
                        <div class="activity-badge">Adult MTB Bike</div>
                        <div class="activity-duration">3 Hours</div>
                        <div class="activity-price">100K IDR</div>
                        <div class="activity-desc">Extended exploration of hidden beaches and forest trails</div>
                    </div>
                    
                    <div class="activity-card">
                        <div class="activity-badge">Adult MTB Bike</div>
                        <div class="activity-duration">12 Hours</div>
                        <div class="activity-price">120K IDR</div>
                        <div class="activity-desc">Full-day adventure covering multiple destinations and viewpoints</div>
                    </div>
                    
                    <div class="activity-card">
                        <div class="activity-badge">Adult MTB Bike</div>
                        <div class="activity-duration">24 Hours</div>
                        <div class="activity-price">130K IDR</div>
                        <div class="activity-desc">Ultimate multi-day experience with overnight camping options</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Golf Section -->
        <section class="section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Salaka Golf</h2>
                    <p class="section-subtitle">Perfect your swing in paradise with our premium golf facilities</p>
                </div>
                
                <div class="golf-cards">
                    <div class="golf-card">
                        <div class="activity-badge">Driving Range</div>
                        <div class="golf-icon">🏌️</div>
                        <div class="golf-main">50 Holes</div>
                        <div class="golf-desc">Experience our longest drive and putting green with panoramic ocean views</div>
                        <a href="{{ \App\Helpers\WhatsAppHelper::generateGolfLink('50 Holes', 'Salaka Golf') }}" 
                            class="golf-btn" target="_blank">Book Now</a>
                    </div>
                    
                    <div class="golf-card">
                        <div class="activity-badge">Driving Range</div>
                        <div class="golf-icon">🎯</div>
                        <div class="golf-main">9 Holes</div>
                        <div class="golf-desc">Challenge yourself with precision targets and varying difficulty levels</div>
                        <a href="{{ \App\Helpers\WhatsAppHelper::generateGolfLink('50 Holes', 'Salaka Golf') }}" 
                            class="golf-btn" target="_blank">Book Now</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Water Activities Section -->
        <section class="section">
            <div class="container">
                <div class="water-section">
                    <div class="section-header">
                        <h2 class="section-title">Lalassa Beach</h2>
                        <p class="section-subtitle">Dive into crystal-clear waters and explore marine wonders</p>
                    </div>
                    
                    <div class="water-activities-grid">
                        <div class="water-activity-card">
                            <div class="water-activity-image">
                                <img src="{{ asset('frontend/assets/img/Activities/watersport/SNORKELING.png') }}" alt="Snorkeling"> 
                            </div>
                            <div class="water-activity-label">Snorkeling</div>
                        </div>
                        
                        <div class="water-activity-card">
                            <div class="water-activity-image">
                                <img src="{{ asset('frontend/assets/img/Activities/watersport/jetski.png') }}" alt="Jetski">
                            </div>
                            <div class="water-activity-label">Jetski</div>
                        </div>
                        
                        <div class="water-activity-card">
                            <div class="water-activity-image">
                                <img src="{{ asset('frontend/assets/img/Activities/watersport/slider-boat.png') }}" alt="Slider Boat">
                            </div>
                            <div class="water-activity-label">Slider Boat</div>
                        </div>
                        
                        <div class="water-activity-card">
                            <div class="water-activity-image">
                                <img src="{{ asset('frontend/assets/img/Activities/watersport/fishing.png') }}" alt="Fishing">
                            </div>
                            <div class="water-activity-label">Fishing</div>
                        </div>
                    </div>
                    
                    <a href="{{ \App\Helpers\WhatsAppHelper::generateWaterActivityLink('Snorkeling') }}" 
                        class="cta-button" target="_blank">Explore Water Activity</a>
                </div>
            </div>
        </section>

        <!-- Trip Packages Section -->
        <section class="section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Trip Packages</h2>
                    <p class="section-subtitle">Curated experiences combining the best of land and sea adventures</p>
                </div>
                
                <div class="packages-scroll">
                    <div class="packages-grid">
                        <div class="package-card">
                            <div class="package-image">
                                <img src="{{ asset('frontend/assets/img/destination/liwungan.jpeg') }}" alt="Liwungan Island">
                            </div>
                            <div class="package-content">
                                <div class="package-title">Liwungan Island Tour</div>
                                
                                <a href="{{ \App\Helpers\WhatsAppHelper::generatePackageLink('Liwungan Island Tour', 'Beautiful island getaway with snorkeling and beach activities') }}" 
                                    class="package-btn" target="_blank">More Details</a>
                            </div>
                        </div>
                        
                        <div class="package-card">
                            <div class="package-image">
                                <img src="{{ asset('frontend/assets/img/destination/umangisland.png') }}" alt="Umang Island">
                            </div>
                            <div class="package-content">
                                <div class="package-title">Umang Island Tour</div>
                                
                                <a href="{{ \App\Helpers\WhatsAppHelper::generatePackageLink('Liwungan Island Tour', 'Beautiful island getaway with snorkeling and beach activities') }}" 
           class="package-btn" target="_blank">More Details</a>
                            </div>
                        </div>
                        
                        <div class="package-card">
                            <div class="package-image">
                                <img src="{{ asset('frontend/assets/img/destination/oarisland.jpg') }}" alt="Oar Badul Mangir Tour">
                            </div>
                            <div class="package-content">
                                <div class="package-title">Oar, Badul & Mangir Tour</div>
                                
                                <a href="{{ \App\Helpers\WhatsAppHelper::generatePackageLink('Liwungan Island Tour', 'Beautiful island getaway with snorkeling and beach activities') }}" 
           class="package-btn" target="_blank">More Details</a>
                            </div>
                        </div>
                        
                        <div class="package-card">
                            <div class="package-image">
                                <img src="{{ asset('frontend/assets/img/destination/panaitan.jpg') }}" alt="Panaitan Island">
                            </div>
                            <div class="package-content">
                                <div class="package-title">Panaitan Island Tour</div>
                                
                                <a href="{{ \App\Helpers\WhatsAppHelper::generatePackageLink('Liwungan Island Tour', 'Beautiful island getaway with snorkeling and beach activities') }}" 
           class="package-btn" target="_blank">More Details</a>
                            </div>
                        </div>
                        
                        <div class="package-card">
                            <div class="package-image">
                                <img src="{{ asset('frontend/assets/img/destination/krakatauchild.jpg') }}" alt="Krakatau Child Mountain">
                            </div>
                            <div class="package-content">
                                <div class="package-title">Krakatau Child Mountain Tour</div>
                                
                                <a href="{{ \App\Helpers\WhatsAppHelper::generatePackageLink('Liwungan Island Tour', 'Beautiful island getaway with snorkeling and beach activities') }}" 
           class="package-btn" target="_blank">More Details</a>
                            </div>
                        </div>
                        
                        <div class="package-card">
                            <div class="package-image">
                                <img src="{{ asset('frontend/assets/img/destination/tlbeach.jpg') }}" alt="Tanjung Lesung Beach">
                            </div>
                            <div class="package-content">
                                <div class="package-title">Tanjung Lesung Beach Tour</div>
                                
                                <a href="{{ \App\Helpers\WhatsAppHelper::generatePackageLink('Liwungan Island Tour', 'Beautiful island getaway with snorkeling and beach activities') }}" 
           class="package-btn" target="_blank">More Details</a>
                            </div>
                        </div>
                        
                        <div class="package-card">
                            <div class="package-image">
                                <img src="{{ asset('frontend/assets/img/destination/batu-hideung.jpeg') }}" alt="Batu Hideung Beach">
                            </div>
                            <div class="package-content">
                                <div class="package-title">Batu Hideung Beach Tour</div>
                                
                                <a href="{{ \App\Helpers\WhatsAppHelper::generatePackageLink('Liwungan Island Tour', 'Beautiful island getaway with snorkeling and beach activities') }}" 
           class="package-btn" target="_blank">More Details</a>
                            </div>
                        </div>
                        
                        <div class="package-card">
                            <div class="package-image">
                                <img src="{{ asset('frontend/assets/img/destination/coral-reef-transplantation.jpg') }}" alt="Coral Reef Education">
                            </div>
                            <div class="package-content">
                                <div class="package-title">Coral Reef Education Tour</div>
                                
                                <a href="{{ \App\Helpers\WhatsAppHelper::generatePackageLink('Liwungan Island Tour', 'Beautiful island getaway with snorkeling and beach activities') }}" 
           class="package-btn" target="_blank">More Details</a>
                            </div>
                        </div>
                        
                        <div class="package-card">
                            <div class="package-image">
                                <img src="{{ asset('frontend/assets/img/destination/curug-carita.jpg') }}" alt="Green Canyon & Curug">
                            </div>
                            <div class="package-content">
                                <div class="package-title">Green Canyon & Curug Tour</div>
                                
                                <a href="{{ \App\Helpers\WhatsAppHelper::generatePackageLink('Liwungan Island Tour', 'Beautiful island getaway with snorkeling and beach activities') }}" 
           class="package-btn" target="_blank">More Details</a>
                            </div>
                        </div>
                        
                        <div class="package-card">
                            <div class="package-image">
                                <img src="{{ asset('frontend/assets/img/destination/turtleedu.jpeg') }}" alt="Turtle Education">
                            </div>
                            <div class="package-content">
                                <div class="package-title">Turtle Education Tour</div>
                                
                                <a href="{{ \App\Helpers\WhatsAppHelper::generatePackageLink('Liwungan Island Tour', 'Beautiful island getaway with snorkeling and beach activities') }}" 
           class="package-btn" target="_blank">More Details</a>
                            </div>
                        </div>
                        
                        <div class="package-card">
                            <div class="package-image">
                                <img src="{{ asset('frontend/assets/img/destination/mangrove.jpg') }}" alt="Mangrove Education">
                            </div>
                            <div class="package-content">
                                <div class="package-title">Mangrove Education Tour</div>
                                
                                <a href="{{ \App\Helpers\WhatsAppHelper::generatePackageLink('Liwungan Island Tour', 'Beautiful island getaway with snorkeling and beach activities') }}" 
           class="package-btn" target="_blank">More Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

<script>
    // Carousel functionality
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.getElementById('packagesGrid');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const dotsContainer = document.getElementById('carouselDots');
        
        const cards = document.querySelectorAll('.package-card');
        const cardWidth = 320 + 32; // card width + gap
        const visibleCards = window.innerWidth >= 768 ? 3 : 1;
        const maxSlides = Math.ceil(cards.length / visibleCards);
        
        let currentSlide = 0;
        
        // Create dots
        for (let i = 0; i < maxSlides; i++) {
            const dot = document.createElement('button');
            dot.classList.add('carousel-dot');
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(i));
            dotsContainer.appendChild(dot);
        }
        
        const dots = document.querySelectorAll('.carousel-dot');
        
        function updateCarousel() {
            const translateX = -currentSlide * cardWidth * visibleCards;
            carousel.style.transform = `translateX(${translateX}px)`;
            
            // Update dots
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
            
            // Update button states
            prevBtn.disabled = currentSlide === 0;
            nextBtn.disabled = currentSlide === maxSlides - 1;
        }
        
        function goToSlide(slide) {
            currentSlide = Math.max(0, Math.min(slide, maxSlides - 1));
            updateCarousel();
        }
        
        function nextSlide() {
            if (currentSlide < maxSlides - 1) {
                currentSlide++;
                updateCarousel();
            }
        }
        
        function prevSlide() {
            if (currentSlide > 0) {
                currentSlide--;
                updateCarousel();
            }
        }
        
        prevBtn.addEventListener('click', prevSlide);
        nextBtn.addEventListener('click', nextSlide);
        
        // Initialize
        updateCarousel();
        
        // Auto-slide (optional)
        setInterval(() => {
            if (currentSlide < maxSlides - 1) {
                nextSlide();
            } else {
                goToSlide(0); // Loop back to start
            }
        }, 5000);
    });
</script>
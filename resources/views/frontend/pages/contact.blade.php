@extends('frontend.main')
@section('main')

<link rel="stylesheet" href="{{ asset('frontend/assets/css/contactus.css') }}">

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <h1 class="contact-title">Contact Us</h1>
                <p class="hero-subtitle">We're here to help make your Tanjung Lesung experience unforgettable</p>
            </div>
            <div class="col-lg-6 hero-image text-center">
                <div class="position-relative">
                    <img src="{{asset('frontend/assets/img/contact.png')}}" class="receptionist-img" alt="Contact Us">
                    <div class="hero-overlay-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Info Section -->
<section class="info-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="info-content">
                    <p class="info-description">
                        Book your stay at one of the most comfortable resorts at Tanjung Lesung Beach Resort. 
                        Located in the Tanjung Lesung Special Economic Zone in Banten- Indonesia, 
                        you can reach 4 hours drive from Jakarta.
                    </p>
                    {{-- <p class="contact-invitation">
                        <strong>If you have any questions, please feel free to contact us.</strong>
                    </p> --}}
                </div>
            </div>
        </div>
        
        <!-- Contact Methods -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="contact-methods">
                    <div class="row">
                        <!-- WhatsApp Contact -->
                        <div class="col-md-4 mb-4">
                            <div class="contact-method whatsapp-method">
                                <div class="method-icon">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <h4>WhatsApp</h4>
                                <p>Quick response guaranteed</p>
                                <a href="{{ \App\Helpers\WhatsAppHelper::generateGeneralInquiry('resort services') }}" 
                                   class="whatsapp-btn whatsapp-btn-block" target="_blank">
                                    <i class="fab fa-whatsapp"></i> Chat Now
                                </a>
                            </div>
                        </div>
                        
                        <!-- Phone Contact -->
                        <div class="col-md-4 mb-4">
                            <div class="contact-method phone-method">
                                <div class="method-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <h4>Call Us</h4>
                                <p>Customer Service</p>
                                <a href="tel:+6281187800100" class="btn-phone">
                                    <i class="fas fa-phone"></i> Call Now
                                </a>
                            </div>
                        </div>
                        
                        <!-- Email Contact -->
                        <div class="col-md-4 mb-4">
                            <div class="contact-method email-method">
                                <div class="method-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <h4>Email Us</h4>
                                <p>Detailed inquiries welcome</p>
                                <a href="mailto:info@tanjunglesung.com" class="btn-email">
                                    <i class="fas fa-envelope"></i> Send Email
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Information Card -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="contact-info-card">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="contact-info-group">
                                <h5><i class="fas fa-envelope me-2 text-primary"></i>Email</h5>
                                <div class="contact-info-item">
                                    <a href="mailto:info@tanjunglesung.com">info@tanjunglesung.com</a>
                                </div>
                                
                                <h5><i class="fas fa-phone me-2 text-primary"></i>Phone Numbers</h5>
                                <div class="contact-info-item">
                                    <strong>Hotline:</strong> 
                                    <a href="tel:+6281187800100">{{ \App\Helpers\WhatsAppHelper::formatPhoneForDisplay('6281187800100') }}</a>
                                </div>
                                <div class="contact-info-item">
                                    <strong>Available 24 Hours:</strong>
                                    <a href="tel:+6281119290005">{{ \App\Helpers\WhatsAppHelper::formatPhoneForDisplay('6281119290005') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-info-group">
                                <h5><i class="fas fa-map-marker-alt me-2 text-primary"></i>Location</h5>
                                <div class="contact-info-item">
                                    <strong>Address:</strong><br>
                                    Special Economic Zone Tanjung Lesung
                                </div>
                                <div class="contact-info-item">
                                    <strong>City:</strong><br>
                                    Pandeglang, Banten, Indonesia
                                </div>
                                
                                <h5><i class="fas fa-clock me-2 text-primary"></i>Response Time</h5>
                                <div class="contact-info-item">
                                    <strong>WhatsApp:</strong> Instant reply<br>
                                    <strong>Email:</strong> Within 24 hours<br>
                                    <strong>Phone:</strong> Immediate assistance
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Form and Map Section -->
<section class="form-map-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="contact-form-wrapper">
                    <div class="form-header">
                        <h3><i class="fas fa-paper-plane me-2"></i>Send us a Message</h3>
                        <p>Fill out the form below and we'll get back to you soon</p>
                    </div>
                    
                    <div class="contact-form">
                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        <form id="contactForm" method="POST" action="{{ route('contact.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i>Full Name
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       placeholder="Enter your full name" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>Email Address
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="Enter your email address" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>Phone Number (Optional)
                                </label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}"
                                       placeholder="Enter your phone number">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="message" class="form-label">
                                    <i class="fas fa-comment me-1"></i>Your Message
                                </label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" 
                                          name="message" 
                                          rows="5" 
                                          placeholder="Tell us how we can help you..."
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <span id="charCount">0</span>/5000 characters
                                </div>
                            </div>
                            
                            <div class="form-buttons">
                                <button type="submit" class="btn btn-send">
                                    <span class="btn-text">
                                        <i class="fas fa-paper-plane me-1"></i>SEND MESSAGE
                                    </span>
                                    <span class="btn-loading" style="display: none;">
                                        <i class="fas fa-spinner"></i>Sending...
                                    </span>
                                </button>
                            </div>
                        </form>
                        
                        <!-- Alternative Contact Options -->
                        <div class="alternative-contact">
                            <div class="divider">
                                <span>OR</span>
                            </div>
                            <div class="quick-contact-buttons">
                                <a href="{{ \App\Helpers\WhatsAppHelper::generateGeneralInquiry('website contact form') }}" 
                                   class="whatsapp-btn whatsapp-btn-small" target="_blank">
                                    <i class="fab fa-whatsapp"></i> WhatsApp Us
                                </a>
                                <a href="tel:+6281187800100" class="btn-phone-small">
                                    <i class="fas fa-phone"></i> Call Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="map-section">
                    <div class="map-header">
                        <h3><i class="fas fa-map-marker-alt me-2"></i>Find Us Here</h3>
                        <p>Visit our beautiful resort in Tanjung Lesung</p>
                    </div>
                    
                    <div class="map-container">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63501.47484277847!2d105.6537012!3d-6.4944045!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e426d96b3d2ed95%3A0xbfc90a93497c2dc8!2sTanjung%20Lesung%20Special%20Economic%20Zone!5e0!3m2!1sen!2sid!4v1735027200000!5m2!1sen!2sid"
                            width="100%" 
                            height="400" 
                            style="border:0; border-radius: 15px;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Tanjung Lesung Location">
                        </iframe>
                        
                        <!-- Map Fallback -->
                        <div class="map-fallback" style="display: none;">
                            <div class="map-placeholder">
                                <i class="fas fa-map-marker-alt"></i>
                                <h5>Tanjung Lesung Resort</h5>
                                <p><strong>Address:</strong><br>Special Economic Zone Tanjung Lesung<br>Pandeglang, Banten, Indonesia</p>
                                <p><strong>Coordinates:</strong><br>-6.4944045, 105.6537012</p>
                                <a href="https://maps.google.com/?q=Tanjung+Lesung+Special+Economic+Zone" 
                                   target="_blank" class="btn-map-link">
                                    <i class="fas fa-external-link-alt"></i> Open in Google Maps
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Location Details -->
                    <div class="location-details">
                        <div class="row">
                            <div class="col-6">
                                <div class="location-item">
                                    <i class="fas fa-car"></i>
                                    <span>3 hours from Jakarta</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="location-item">
                                    <i class="fas fa-plane"></i>
                                    <span>Nearest Airport: CGK</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- WhatsApp Floating Button -->
<div class="whatsapp-float">
    <a href="{{ \App\Helpers\WhatsAppHelper::generateGeneralInquiry('customer support') }}" 
       target="_blank" class="whatsapp-float-btn" title="Chat with us on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
</div>

<!-- Enhanced Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form handling with enhanced validation
    const contactForm = document.getElementById('contactForm');
    const btnSend = contactForm.querySelector('.btn-send');
    const btnText = btnSend.querySelector('.btn-text');
    const btnLoading = btnSend.querySelector('.btn-loading');
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    
    // Character counter
    messageTextarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;
        
        if (count > 5000) {
            charCount.style.color = '#dc3545';
            this.classList.add('is-invalid');
        } else {
            charCount.style.color = '#6c757d';
            this.classList.remove('is-invalid');
        }
    });
    
    // Form submission with loading state
    contactForm.addEventListener('submit', function(e) {
        // Show loading state
        btnSend.disabled = true;
        btnSend.classList.add('loading');
        btnText.style.display = 'none';
        btnLoading.style.display = 'inline-block';
        
        // Validate form
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const message = document.getElementById('message').value.trim();
        
        if (!name || !email || !message) {
            e.preventDefault();
            showAlert('Please fill in all required fields', 'error');
            resetButton();
            return;
        }
        
        if (message.length < 10) {
            e.preventDefault();
            showAlert('Message must be at least 10 characters long', 'error');
            resetButton();
            return;
        }
        
        if (message.length > 5000) {
            e.preventDefault();
            showAlert('Message cannot exceed 5000 characters', 'error');
            resetButton();
            return;
        }
        
        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            showAlert('Please enter a valid email address', 'error');
            resetButton();
            return;
        }
    });
    
    function resetButton() {
        btnSend.disabled = false;
        btnSend.classList.remove('loading');
        btnText.style.display = 'inline-block';
        btnLoading.style.display = 'none';
    }
    
    // Enhanced alert system
    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alert = document.createElement('div');
        alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        alert.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;';
        alert.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas ${icon} me-2"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alert);
        
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
    
    // Auto-dismiss existing alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.click();
            }
        });
    }, 8000);
    
    // Map error handling
    const iframe = document.querySelector('.map-container iframe');
    const fallback = document.querySelector('.map-fallback');
    
    if (iframe && fallback) {
        iframe.addEventListener('error', function() {
            iframe.style.display = 'none';
            fallback.style.display = 'flex';
        });
    }
    
    // Smooth animations on scroll
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
    
    // Observe elements for animation
    document.querySelectorAll('.contact-info-card, .contact-form-wrapper, .map-section, .contact-method').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
    
    // Phone number formatting
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = '62' + value.substring(1);
            }
            if (value.length > 0 && !value.startsWith('62')) {
                value = '62' + value;
            }
            this.value = value;
        });
    }
});
</script>

<!-- Enhanced Styles -->
<style>
/* Contact Methods */
.contact-methods {
    margin-bottom: 3rem;
}

.contact-method {
    text-align: center;
    padding: 2rem 1.5rem;
    border-radius: 15px;
    background: white;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
}

.contact-method:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.method-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.8rem;
    color: white;
}

.whatsapp-method .method-icon {
    background: linear-gradient(135deg, #25D366, #128C7E);
}

.phone-method .method-icon {
    background: linear-gradient(135deg, #007bff, #0056b3);
}

.email-method .method-icon {
    background: linear-gradient(135deg, #dc3545, #b02a37);
}

.contact-method h4 {
    margin-bottom: 0.5rem;
    color: #333;
    font-weight: 600;
}

.contact-method p {
    color: #666;
    margin-bottom: 1.5rem;
}

/* Phone and Email Buttons */
.btn-phone, .whatsapp-btn,  .btn-email, .btn-phone-small {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.2rem;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.whatsapp-btn {
    background: linear-gradient(135deg, #25D366, #128C7E);
    color: white;
}

.whatsapp-btn:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    transform: translateY(-2px);
    color: white;
}

.btn-phone {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
}

.btn-phone:hover, .btn-phone-small:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    transform: translateY(-2px);
    color: white;
}

.btn-email {
    background: linear-gradient(135deg, #dc3545, #b02a37);
    color: white;
}

.btn-email:hover {
    background: linear-gradient(135deg, #b02a37, #8b1e2b);
    transform: translateY(-2px);
    color: white;
}

.btn-phone-small {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 0.4rem 1rem;
    font-size: 0.875rem;
}

/* Contact Info Groups */
.contact-info-group h5 {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 1rem;
    border-bottom: 2px solid var(--primary-color);
    padding-bottom: 0.5rem;
}

.contact-info-item {
    margin-bottom: 1rem;
    line-height: 1.6;
}

.contact-info-item a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.contact-info-item a:hover {
    text-decoration: underline;
}

/* Form Enhancements */
.form-header {
    text-align: center;
    margin-bottom: 2rem;
}

.form-header h3 {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.form-header p {
    color: #666;
}

.form-label {
    font-weight: 500;
    color: #333;
    margin-bottom: 0.5rem;
}

.form-label i {
    color: var(--primary-color);
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
}

.form-text {
    text-align: right;
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

/* Alternative Contact */
.alternative-contact {
    margin-top: 2rem;
    text-align: center;
}

.divider {
    position: relative;
    margin: 2rem 0;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #ddd;
}

.divider span {
    background: white;
    padding: 0 1rem;
    color: #666;
    font-weight: 500;
}

.quick-contact-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Map Section */
.map-header {
    text-align: center;
    margin-bottom: 1.5rem;
}

.map-header h3 {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.location-details {
    margin-top: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.location-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #666;
}

.location-item i {
    color: var(--primary-color);
    width: 16px;
}

/* Loading state */
.btn-send.loading {
    pointer-events: none;
    opacity: 0.7;
}

/* Map fallback */
.map-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 400px;
    background: #f8f9fa;
    border-radius: 15px;
    text-align: center;
}

.map-placeholder h5 {
    color: var(--primary-color);
    margin: 1rem 0;
}

.btn-map-link {
    display: inline-block;
    background: var(--primary-gradient);
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    margin-top: 15px;
    transition: transform 0.3s ease;
}

.btn-map-link:hover {
    transform: translateY(-2px);
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .contact-method {
        margin-bottom: 2rem;
    }
    
    .quick-contact-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .whatsapp-btn, .btn-phone-small {
        width: 100%;
        max-width: 250px;
        justify-content: center;
    }
    
    .hero-content h1 {
        font-size: 2rem;
    }
    
    .location-details .col-6 {
        margin-bottom: 1rem;
    }
}

/* Alert Improvements */
.alert {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border: none;
    border-radius: 10px;
    backdrop-filter: blur(10px);
}

.alert-success {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.05));
    border-left: 4px solid #28a745;
}

.alert-danger {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05));
    border-left: 4px solid #dc3545;
}
</style>

@endsection
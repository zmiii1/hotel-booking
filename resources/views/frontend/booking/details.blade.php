@extends('frontend.main')

@section('main')
<link rel="stylesheet" href="{{ asset('frontend/assets/css/booking_details.css') }}">

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
                <a href="{{ route('room.addons', [
                    'room_type_id' => $roomType->id,
                    'package_type' => $package->code,
                    'check_in' => $check_in ?? now()->format('d-m-Y'),
                    'check_out' => $check_out ?? now()->addDay()->format('d-m-Y'),
                    'adults' => $adults ?? 1,
                    'children' => $children ?? 0,
                    'promo_code' => $promo_code ?? ''
                ]) }}" class="back-btn">
                    <i class="fas fa-chevron-left"></i>
                    Back to room services
                </a>
                <h1 class="page-title mb-0">Details of your stay</h1>
            </div>
            <div class="booking-step">
                <span class="step-number completed">1</span>
                <span class="step-text">Room selection</span>
                <span class="step-separator"></span>
                <span class="step-number completed">2</span>
                <span class="step-text">Packages</span>
                <span class="step-separator"></span>
                <span class="step-number completed">3</span>
                <span class="step-text">Services</span>
                <span class="step-separator"></span>
                <span class="step-number active">4</span>
                <span class="step-text">Details</span>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <form action="{{ route('booking.create') }}" method="POST" class="booking-form">
                        @csrf
                        
                        <!-- Hidden fields for booking data -->
                        <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                        <input type="hidden" name="package_type" value="{{ $package->code }}">
                        <input type="hidden" name="check_in" value="{{ $check_in }}">
                        <input type="hidden" name="check_out" value="{{ $check_out }}">
                        <input type="hidden" name="adults" value="{{ $adults }}">
                        <input type="hidden" name="children" value="{{ $children }}">
                        <input type="hidden" name="promo_code" value="{{ $promo_code ?? '' }}">
                        
                        <!-- Customer Information -->
                        <div class="form-section">
                            <h2 class="section-title">Guest Detail Information</h2>
                            {{-- <div class="login-option">
                                <button type="button" class="btn-google-auth">
                                    <img src="{{ asset('frontend/assets/img/google-icon.svg') }}" alt="Google" class="google-icon">
                                    Select your preferred login option to autofill your data
                                </button>
                                <span class="or-divider">or enter your data manually</span>
                            </div> --}}
                            
                            <div class="privacy-notice">
                                <p>By filling the guest information below , you give your <a href="#" class="text-link">Consent to personal data processing</a> </p>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') ?? Auth::user()->name ?? '' }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last name</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}">
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" pattern="[0-9\+\-\(\) ]+" title="Phone number must contain only numbers and symbols +, -, (, )" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- <small class="text-muted">Numbers and symbols +, -, (, ) only</small> --}}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') ?? Auth::user()->email ?? '' }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">e.g. name@example.com</small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <select class="form-select @error('country') is-invalid @enderror" id="country" name="country">
                                        <option value="">Select country</option>
                                        <option value="Indonesia" selected>Indonesia</option>
                                        <option value="Singapore">Singapore</option>
                                        <option value="Malaysia">Malaysia</option>
                                        <option value="United States">United States</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <!-- Add more countries as needed -->
                                    </select>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" id="consent_marketing" name="consent_marketing" checked>
                                <label class="form-check-label" for="consent_marketing">
                                    I give my <a href="#" class="text-link">Consent</a> to receive news and information about special offers
                                </label>
                            </div>
                        </div>
                        
                        <!-- Additional Information -->
                        <div class="form-section">
                            <h2 class="section-title">Additional information</h2>
                            
                            <div class="mb-3">
                                <label for="additional_request" class="form-label">Personal request</label>
                                <textarea class="form-control" id="additional_request" name="additional_request" rows="4" placeholder="If you have any special needs, please feel free to share them with us. We'll do our best to help you"></textarea>
                            </div>
                        </div>
                        
                        <!-- Ganti bagian Payment Methods yang ada dengan kode ini -->
                        <div class="form-section">
                            <h2 class="section-title">Payment methods</h2>
                            
                            <div class="payment-methods-container">
                                <div class="payment-method-option mb-3">
                                    <input type="radio" class="payment-radio" name="payment_method" id="payment-xendit" value="xendit" checked>
                                    <label for="payment-xendit" class="payment-method-label">
                                        <div class="payment-method-content">
                                            <div class="payment-method-left">
                                                <h4 class="payment-method-title">E-payment</h4>
                                                <p class="payment-method-description">Visa, MasterCard</p>
                                                
                                                <div class="payment-details">
                                                    <p class="mb-2">The <strong>total</strong> is paid in full.</p>
                                                    <p class="mb-2"><strong>We Accept Payment</strong>:</p>
                                                    <ul class="payment-list">
                                                        <li>BANK TRANSFER VIA VIRTUAL ACCOUNT: BNI, BRI, MANDIRI, BSI, CIMB, BJB, and Other Bank.</li>
                                                    </ul>
                                                    <p class="mb-2">Click 'Complete booking' button to choose the payment method</p>
                                                    <p class="payment-processor">Payment processing is performed by <a href="#" class="text-primary">Xendit</a>.</p>
                                                </div>
                                            </div>
                                            <div class="payment-method-right">
                                                <div class="payment-method-logos">
                                                    <img src="{{ asset('frontend/assets/img/payments/visa.png') }}" alt="Visa" class="payment-logo">
                                                    <img src="{{ asset('frontend/assets/img/payments/mastercard.png') }}" alt="MasterCard" class="payment-logo">
                                                </div>
                                                <div class="payment-amount">
                                                    <div class="payment-label">Pay now</div>
                                                    <div class="payment-value">Rp{{ number_format($totalPrice, 0, ',', '.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="payment-notice">
                                <p>By proceeding with the booking, you give your <a href="" class="text-link">Consent to personal data processing</a> and confirm that you have read the <a href="#" class="text-link">Online booking rules</a> and the <a href="#" class="text-link">Privacy policy</a></p>
                            </div>
                            
                            <button type="submit" class="btn-complete-booking">Complete booking</button>
                        </div>
                    </form>
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
                                            @if($discountAmount > 0)
                                                <div class="price">
                                                    <span class="original-price">Rp{{ number_format($roomPrice, 0, ',', '.') }}</span>
                                                    <span class="discounted-price">Rp{{ number_format($roomPrice - $discountAmount, 0, ',', '.') }}</span>/night
                                                </div>
                                            @else
                                                <div class="price">Rp{{ number_format($roomPrice, 0, ',', '.') }}/night</div>
                                            @endif
                                        </div>
                                        
                                        <div class="package-info">
                                            <div>{{ $package->name }}</div>
                                            <div class="price">{{ $packagePrice > 0 ? '+' : '' }}Rp{{ number_format($packagePrice, 0, ',', '.') }}</div>
                                        </div>
                                        
                                        @if($totalDiscount > 0)
                                        <div class="discount-info">
                                            <div>Discount {{ $promoCode ? '(Promo: ' . $promoCode->code . ')' : '' }}</div>
                                            <div class="price text-success">-Rp{{ number_format($totalDiscount, 0, ',', '.') }}</div>
                                        </div>
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
                                
                                @if(isset($addonTotal) && $addonTotal > 0)
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

                            <!-- Di bagian bawah booking summary -->
                            <div class="booking-actions">
                                <a href="{{ route('booking.print.summary', [
                                    'room_type_id' => $roomType->id,
                                    'package_type' => $package->code,
                                    'check_in' => $check_in,
                                    'check_out' => $check_out,
                                    'adults' => $adults,
                                    'children' => $children,
                                    'promo_code' => $promo_code ?? ''
                                ]) }}" target="_blank" class="booking-summary-btn">
                                    <i class="fas fa-file-alt me-2"></i> Booking summary
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
    // Handle booking summary button
    const summaryBtn = document.getElementById('booking-summary-btn');
    if (summaryBtn) {
        summaryBtn.addEventListener('click', function() {
            window.print();
        });
    }
    
    // Toggle room details collapse
    const roomDetailsHeader = document.querySelector('.room-summary-header');
    const roomDetailsCollapse = document.getElementById('roomDetails');
    
    if (roomDetailsHeader && roomDetailsCollapse) {
        roomDetailsHeader.addEventListener('click', function() {
            const bsCollapse = new bootstrap.Collapse(roomDetailsCollapse);
            if (roomDetailsCollapse.classList.contains('show')) {
                bsCollapse.hide();
            } else {
                bsCollapse.show();
            }
        });
    }
    
    // Auto-fill form data for authenticated users
   @if(Auth::check())
   const firstNameInput = document.getElementById('first_name');
   const lastNameInput = document.getElementById('last_name');
   const emailInput = document.getElementById('email');
   
   if (firstNameInput && "{{ Auth::user()->name }}") {
       // Try to split the full name into first and last name
       const nameParts = "{{ Auth::user()->name }}".split(' ');
       if (nameParts.length > 1) {
           firstNameInput.value = nameParts[0];
           lastNameInput.value = nameParts.slice(1).join(' ');
       } else {
           firstNameInput.value = "{{ Auth::user()->name }}";
       }
   }
   
   if (emailInput && "{{ Auth::user()->email }}") {
       emailInput.value = "{{ Auth::user()->email }}";
   }
   @endif

    document.addEventListener('DOMContentLoaded', function() {
        // Tombol booking summary untuk menampilkan modal
        window.printBookingSummary = function() {
            var printModal = new bootstrap.Modal(document.getElementById('printSummaryModal'));
            printModal.show();
        };
        
        // Khusus untuk print
        window.addEventListener('beforeprint', function() {
            // Persiapan sebelum print
            document.querySelectorAll('.modal').forEach(function(modal) {
                modal.classList.add('show');
                modal.style.display = 'block';
            });
        });
        
        window.addEventListener('afterprint', function() {
            // Restore tampilan setelah print
            document.querySelectorAll('.modal').forEach(function(modal) {
                if (!modal.classList.contains('keep-open')) {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                }
            });
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.querySelector('.booking-form');
    
    if (bookingForm) {
        // Fungsi untuk menampilkan popup error
        function showErrorPopup(title, message) {
            // Cek apakah popup sudah ada
            if (document.getElementById('error-popup')) {
                document.getElementById('error-popup').remove();
            }
            
            // Buat popup container
            const popupContainer = document.createElement('div');
            popupContainer.id = 'error-popup';
            popupContainer.className = 'error-popup';
            
            // Buat content popup
            popupContainer.innerHTML = `
                <div class="error-popup-content">
                    <div class="error-popup-header">
                        <h4><i class="fas fa-exclamation-circle me-2"></i> ${title}</h4>
                        <button type="button" class="error-popup-close">&times;</button>
                    </div>
                    <div class="error-popup-body">
                        ${message}
                    </div>
                    <div class="error-popup-footer">
                        <button type="button" class="btn btn-secondary error-popup-dismiss">OK</button>
                    </div>
                </div>
            `;
            
            // Tambahkan ke body
            document.body.appendChild(popupContainer);
            
            // Tampilkan popup dengan animasi
            setTimeout(() => {
                popupContainer.classList.add('show');
            }, 10);
            
            // Event listener untuk tombol close
            const closeBtn = popupContainer.querySelector('.error-popup-close');
            const dismissBtn = popupContainer.querySelector('.error-popup-dismiss');
            
            closeBtn.addEventListener('click', () => {
                popupContainer.classList.remove('show');
                setTimeout(() => {
                    popupContainer.remove();
                }, 300);
            });
            
            dismissBtn.addEventListener('click', () => {
                popupContainer.classList.remove('show');
                setTimeout(() => {
                    popupContainer.remove();
                }, 300);
            });
            
            // Close popup jika user klik di luar popup
            popupContainer.addEventListener('click', (e) => {
                if (e.target === popupContainer) {
                    popupContainer.classList.remove('show');
                    setTimeout(() => {
                        popupContainer.remove();
                    }, 300);
                }
            });
        }
        
        // Highlight field dengan error
        function highlightErrorField(field, message) {
            field.classList.add('is-invalid', 'shake-error');
            
            // Cari parent yang tepat berdasarkan jenis field
            let parent = field.parentNode;
            if (parent.classList.contains('input-group')) {
                // Untuk field dengan input-group (seperti phone dan email)
            } else {
                // Untuk field biasa seperti first_name
                parent = field;
            }
            
            // Hapus feedback lama jika ada
            let existingFeedback = parent.querySelector('.invalid-feedback');
            if (existingFeedback) {
                existingFeedback.remove();
            }
            
            // Tambahkan pesan error
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.style.display = 'block'; // Pastikan terlihat
            errorDiv.innerText = message;
            parent.appendChild(errorDiv);
            
            // Hapus class shake-error setelah animasi selesai
            setTimeout(() => {
                field.classList.remove('shake-error');
            }, 600);
        }
        
        // Validasi form sebelum submit
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            let errorMessages = [];
            
            // Reset semua field
            document.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
                const feedback = field.parentNode.querySelector('.invalid-feedback');
                if (feedback) feedback.remove();
            });
            
            // Validasi nama depan (harus diisi)
            const firstNameInput = document.getElementById('first_name');
            if (!firstNameInput.value.trim()) {
                highlightErrorField(firstNameInput, 'First name is required');
                hasError = true;
                errorMessages.push('First name is required');
            }
            
            // Validasi nomor telepon (harus berupa angka dan simbol yang diizinkan)
            const phoneInput = document.getElementById('phone');
            const phonePattern = /^[0-9\+\-\(\) ]+$/;
            
            if (!phoneInput.value.trim()) {
                highlightErrorField(phoneInput, 'Phone number is required');
                hasError = true;
                errorMessages.push('Phone number is required');
            } else if (!phonePattern.test(phoneInput.value)) {
                highlightErrorField(phoneInput, 'Phone number must contain only numbers and symbols +, -, (, )');
                hasError = true;
                errorMessages.push('Phone number contains invalid characters');
            }
            
            // Validasi email (harus mengikuti format email yang benar)
            const emailInput = document.getElementById('email');
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            
            if (!emailInput.value.trim()) {
                highlightErrorField(emailInput, 'Email is required');
                hasError = true;
                errorMessages.push('Email is required');
            } else if (!emailPattern.test(emailInput.value)) {
                highlightErrorField(emailInput, 'Please enter a valid email address (e.g., name@example.com)');
                hasError = true;
                errorMessages.push('Invalid email format');
            }
            
            // Cek apakah metode pembayaran dipilih
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethod) {
                hasError = true;
                errorMessages.push('Payment method is not selected');
            }
            
            // Jika ada error, tampilkan popup
            if (hasError) {
                // Buat pesan error
                let errorMessage = '<ul class="error-list">';
                errorMessages.forEach(msg => {
                    errorMessage += `<li>${msg}</li>`;
                });
                errorMessage += '</ul>';
                
                // Tampilkan popup error
                showErrorPopup('Please fix the following errors', errorMessage);
                
                // Scroll ke elemen error pertama
                const firstError = document.querySelector('.is-invalid');
                if (firstError) {
                    firstError.focus();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                return false;
            }
            
            // Jika tidak ada error, tambahkan loading state pada tombol
            const submitButton = document.querySelector('.btn-complete-booking');
            if (submitButton) {
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing...';
                submitButton.disabled = true;
            }
            
            // Submit form
            this.submit();
        });
        
        // Real-time validation untuk nomor telepon
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                const phonePattern = /^[0-9\+\-\(\) ]+$/;
                
                if (this.value && !phonePattern.test(this.value)) {
                    this.classList.add('is-invalid');
                    
                    // Hapus feedback lama jika ada
                    let existingFeedback = this.parentNode.querySelector('.invalid-feedback');
                    if (existingFeedback) {
                        existingFeedback.remove();
                    }
                    
                    // Tambahkan feedback baru
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.style.display = 'block'; // Pastikan terlihat
                    errorDiv.innerText = 'Phone number must contain only numbers and symbols +, -, (, )';
                    this.parentNode.appendChild(errorDiv);
                    
                    // Sembunyikan teks bantuan saat menampilkan error
                    const helpText = this.parentNode.nextElementSibling;
                    if (helpText && helpText.classList.contains('text-muted')) {
                        helpText.style.display = 'none';
                    }
                } else {
                    this.classList.remove('is-invalid');
                    
                    // Hapus feedback jika ada
                    let existingFeedback = this.parentNode.querySelector('.invalid-feedback');
                    if (existingFeedback) {
                        existingFeedback.remove();
                    }
                    
                    // Tampilkan kembali teks bantuan
                    const helpText = this.parentNode.nextElementSibling;
                    if (helpText && helpText.classList.contains('text-muted')) {
                        helpText.style.display = '';
                    }
                }
            });
        }
        
        // Real-time validation untuk email
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('input', function() {
                const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                
                if (this.value && !emailPattern.test(this.value)) {
                    this.classList.add('is-invalid');
                    
                    // Hapus feedback lama jika ada
                    let existingFeedback = this.parentNode.querySelector('.invalid-feedback');
                    if (existingFeedback) {
                        existingFeedback.remove();
                    }
                    
                    // Tambahkan feedback baru
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.style.display = 'block'; // Pastikan terlihat
                    errorDiv.innerText = 'Please enter a valid email address (e.g., name@example.com)';
                    this.parentNode.appendChild(errorDiv);
                    
                    // Sembunyikan teks bantuan saat menampilkan error
                    const helpText = this.parentNode.nextElementSibling;
                    if (helpText && helpText.classList.contains('text-muted')) {
                        helpText.style.display = 'none';
                    }
                } else {
                    this.classList.remove('is-invalid');
                    
                    // Hapus feedback jika ada
                    let existingFeedback = this.parentNode.querySelector('.invalid-feedback');
                    if (existingFeedback) {
                        existingFeedback.remove();
                    }
                    
                    // Tampilkan kembali teks bantuan
                    const helpText = this.parentNode.nextElementSibling;
                    if (helpText && helpText.classList.contains('text-muted')) {
                        helpText.style.display = '';
                    }
                }
            });
            
            // Validasi saat blur (ketika pengguna meninggalkan field)
            emailInput.addEventListener('blur', function() {
                const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                
                if (this.value && !emailPattern.test(this.value)) {
                    showErrorPopup('Invalid Email Format', 'Please enter a valid email address (e.g., name@example.com)');
                }
            });
        }
        
        // Validasi saat blur untuk phone
        const phoneInputBlur = document.getElementById('phone');
        if (phoneInputBlur) {
            phoneInputBlur.addEventListener('blur', function() {
                const phonePattern = /^[0-9\+\-\(\) ]+$/;
                
                if (this.value && !phonePattern.test(this.value)) {
                    showErrorPopup('Invalid Phone Format', 'Phone number must contain only numbers and symbols +, -, (, )');
                }
            });
        }
        
        // Toggle radio button saat label diklik
        const paymentLabels = document.querySelectorAll('.payment-method-label');
        paymentLabels.forEach(label => {
            label.addEventListener('click', function() {
                const radio = document.querySelector(`#${this.getAttribute('for')}`);
                if (radio) {
                    radio.checked = true;
                }
            });
        });
    }
});
</script>
@endsection
@extends('admin.admin_dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">

<link href="{{ asset('backend/assets/css/pos-index.css') }}" rel="stylesheet">

<div class="page-content">
    {{-- Breadcrumb Section --}}
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">
            <i class="bx bx-store me-2"></i>Beach Ticket POS
        </div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('backend.beach-tickets.dashboard') }}">Beach Tickets</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">POS System</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('backend.beach-tickets.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i>Back to Dashboard
            </a>
        </div>
    </div>

    {{-- Welcome Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card pos-header-card border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-2 text-white fw-bold">
                                <i class="bx bx-store-alt me-2"></i>Beach Ticket Point of Sale
                            </h4>
                            <p class="mb-0 text-white-50">
                                Process ticket sales and manage orders • 
                                <span class="badge bg-light text-pink fw-semibold px-3 py-2">{{ Auth::user()->getRoleNames()->first() }}</span>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end text-white-50">
                            <div class="d-flex flex-column align-items-md-end">
                                <div class="mb-1">
                                    <i class="bx bx-calendar me-1"></i>
                                    {{ \Carbon\Carbon::now('Asia/Jakarta')->format('l, d F Y') }}
                                </div>
                                <div>
                                    <i class="bx bx-time me-1"></i>
                                    {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }} WIB
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main POS Interface --}}
    <div class="row g-4">
        {{-- Product Listing Section --}}
        <div class="col-lg-8">
            <div class="card pos-products-card border-0 shadow-sm h-100">
                <div class="card-header pos-card-header">
                    <h6 class="mb-0 fw-semibold text-white d-flex align-items-center">
                        <i class="bx bx-ticket me-2"></i>Available Tickets
                    </h6>
                </div>
                <div class="card-body p-4">
                    {{-- Beach Tabs --}}
                    <ul class="nav nav-pills pos-nav-pills mb-4" id="beach-tabs" role="tablist">
                        <li class="nav-item me-2" role="presentation">
                            <button class="nav-link active pos-nav-link" id="lalassa-tab" data-bs-toggle="pill" 
                                data-bs-target="#lalassa" type="button" role="tab" aria-controls="lalassa" aria-selected="true">
                                <i class="bx bx-map me-2"></i>Lalassa Beach Club
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link pos-nav-link" id="bodur-tab" data-bs-toggle="pill" 
                                data-bs-target="#bodur" type="button" role="tab" aria-controls="bodur" aria-selected="false">
                                <i class="bx bx-map me-2"></i>Bodur Beach
                            </button>
                        </li>
                    </ul>

                    {{-- Ticket Content --}}
                    <div class="tab-content" id="beach-tab-content">
                        {{-- Lalassa Beach Tickets --}}
                        <div class="tab-pane fade show active" id="lalassa" role="tabpanel" aria-labelledby="lalassa-tab">
                            {{-- Regular Tickets --}}
                            <div class="mb-5">
                                <h6 class="text-pink fw-semibold mb-3 section-title">
                                    <i class="bx bx-ticket me-2"></i>Regular Tickets
                                </h6>
                                <div class="row g-3">
                                    @forelse($groupedTickets['lalassa']['regular'] as $ticket)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card ticket-card h-100 border-0 shadow-sm">
                                            @if($ticket->image)
                                            <div class="ticket-image-container">
                                                <img src="{{ asset('storage/' . $ticket->image) }}" 
                                                     class="card-img-top ticket-image" alt="{{ $ticket->name }}">
                                            </div>
                                            @endif
                                            <div class="card-body p-3">
                                                <h6 class="card-title text-dark fw-semibold mb-2 ticket-name">{{ $ticket->name }}</h6>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span class="ticket-price text-success fw-bold">Rp{{ number_format($ticket->price, 0, ',', '.') }}</span>
                                                </div>
                                                <button type="button" class="btn btn-pink btn-sm w-100 add-cart-btn" 
                                                    data-id="{{ $ticket->id }}" 
                                                    data-name="{{ $ticket->name }}" 
                                                    data-price="{{ $ticket->price }}">
                                                    <i class="bx bx-cart-add me-1"></i>Add to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-12">
                                        <div class="empty-state text-center py-5">
                                            <i class="bx bx-ticket empty-icon"></i>
                                            <p class="text-muted mt-3 mb-0">No regular tickets available for Lalassa Beach Club</p>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                            
                            {{-- Bundling Tickets --}}
                            <div>
                                <h6 class="text-pink fw-semibold mb-3 section-title">
                                    <i class="bx bx-package me-2"></i>Bundling Tickets
                                </h6>
                                <div class="row g-3">
                                    @forelse($groupedTickets['lalassa']['bundling'] as $ticket)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card ticket-card h-100 border-0 shadow-sm">
                                            @if($ticket->image)
                                            <div class="ticket-image-container">
                                                <img src="{{ asset('storage/' . $ticket->image) }}" 
                                                     class="card-img-top ticket-image" alt="{{ $ticket->name }}">
                                            </div>
                                            @endif
                                            <div class="card-body p-3">
                                                <h6 class="card-title text-dark fw-semibold mb-2 ticket-name">{{ $ticket->name }}</h6>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span class="ticket-price text-success fw-bold">Rp{{ number_format($ticket->price, 0, ',', '.') }}</span>
                                                </div>
                                                <button type="button" class="btn btn-pink btn-sm w-100 add-cart-btn" 
                                                    data-id="{{ $ticket->id }}" 
                                                    data-name="{{ $ticket->name }}" 
                                                    data-price="{{ $ticket->price }}">
                                                    <i class="bx bx-cart-add me-1"></i>Add to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-12">
                                        <div class="empty-state text-center py-5">
                                            <i class="bx bx-package empty-icon"></i>
                                            <p class="text-muted mt-3 mb-0">No bundling tickets available for Lalassa Beach Club</p>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        
                        {{-- Bodur Beach Tickets --}}
                        <div class="tab-pane fade" id="bodur" role="tabpanel" aria-labelledby="bodur-tab">
                            {{-- Regular Tickets --}}
                            <div class="mb-5">
                                <h6 class="text-pink fw-semibold mb-3 section-title">
                                    <i class="bx bx-ticket me-2"></i>Regular Tickets
                                </h6>
                                <div class="row g-3">
                                    @forelse($groupedTickets['bodur']['regular'] as $ticket)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card ticket-card h-100 border-0 shadow-sm">
                                            @if($ticket->image)
                                            <div class="ticket-image-container">
                                                <img src="{{ asset('storage/' . $ticket->image) }}" 
                                                     class="card-img-top ticket-image" alt="{{ $ticket->name }}">
                                            </div>
                                            @endif
                                            <div class="card-body p-3">
                                                <h6 class="card-title text-dark fw-semibold mb-2 ticket-name">{{ $ticket->name }}</h6>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span class="ticket-price text-success fw-bold">Rp{{ number_format($ticket->price, 0, ',', '.') }}</span>
                                                </div>
                                                <button type="button" class="btn btn-pink btn-sm w-100 add-cart-btn" 
                                                    data-id="{{ $ticket->id }}" 
                                                    data-name="{{ $ticket->name }}" 
                                                    data-price="{{ $ticket->price }}">
                                                    <i class="bx bx-cart-add me-1"></i>Add to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-12">
                                        <div class="empty-state text-center py-5">
                                            <i class="bx bx-ticket empty-icon"></i>
                                            <p class="text-muted mt-3 mb-0">No regular tickets available for Bodur Beach</p>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                            
                            {{-- Bundling Tickets --}}
                            <div>
                                <h6 class="text-pink fw-semibold mb-3 section-title">
                                    <i class="bx bx-package me-2"></i>Bundling Tickets
                                </h6>
                                <div class="row g-3">
                                    @forelse($groupedTickets['bodur']['bundling'] as $ticket)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card ticket-card h-100 border-0 shadow-sm">
                                            @if($ticket->image)
                                            <div class="ticket-image-container">
                                                <img src="{{ asset('storage/' . $ticket->image) }}" 
                                                     class="card-img-top ticket-image" alt="{{ $ticket->name }}">
                                            </div>
                                            @endif
                                            <div class="card-body p-3">
                                                <h6 class="card-title text-dark fw-semibold mb-2 ticket-name">{{ $ticket->name }}</h6>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span class="ticket-price text-success fw-bold">Rp{{ number_format($ticket->price, 0, ',', '.') }}</span>
                                                </div>
                                                <button type="button" class="btn btn-pink btn-sm w-100 add-cart-btn" 
                                                    data-id="{{ $ticket->id }}" 
                                                    data-name="{{ $ticket->name }}" 
                                                    data-price="{{ $ticket->price }}">
                                                    <i class="bx bx-cart-add me-1"></i>Add to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-12">
                                        <div class="empty-state text-center py-5">
                                            <i class="bx bx-package empty-icon"></i>
                                            <p class="text-muted mt-3 mb-0">No bundling tickets available for Bodur Beach</p>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Cart/Checkout Section --}}
        <div class="col-lg-4">
            <div class="card pos-cart-card border-0 shadow-sm h-100">
                <div class="card-header pos-card-header">
                    <h6 class="mb-0 fw-semibold text-white d-flex align-items-center">
                        <i class="bx bx-cart me-2"></i>Order Details
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form id="pos-form" action="{{ route('backend.pos.process') }}" method="POST">
                        @csrf
                        
                        {{-- Cart Items --}}
                        <div class="cart-section mb-4">
                            <div id="cart-items">
                                <div class="text-center py-5" id="empty-cart-message">
                                    <div class="empty-cart-icon mb-3">
                                        <i class="bx bx-cart"></i>
                                    </div>
                                    <p class="text-muted mb-0">No items in cart</p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Cart Summary --}}
                        <div id="cart-summary" class="mb-4" style="display: none;">
                            <div class="cart-summary-card p-3">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="fw-medium">Subtotal:</span>
                                    <span id="cart-subtotal" class="fw-bold text-pink">Rp 0</span>
                                </div>
                                
                                <div class="promo-section mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="promo-code" name="promo_code" 
                                               placeholder="Enter promo code">
                                        <input type="hidden" id="selected-ticket-id" name="selected_ticket_id" value="">
                                        <button class="btn btn-outline-pink" type="button" id="apply-promo">Apply</button>
                                    </div>
                                </div>
                                
                                <div id="discount-row" class="d-flex justify-content-between mb-3" style="display: none;">
                                    <span class="fw-medium text-success">Discount:</span>
                                    <span id="cart-discount" class="fw-bold text-success">Rp 0</span>
                                </div>
                                
                                <hr class="my-3">
                                
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold fs-6">Total:</span>
                                    <span id="cart-total" class="fw-bold text-pink fs-5">Rp 0</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Simplified Order Information --}}
                        <div class="customer-section">
                            <h6 class="section-title text-pink fw-semibold mb-3">
                                <i class="bx bx-receipt me-2"></i>Order Information
                            </h6>
                            
                            <div class="mb-3">
                                <label for="customer_name" class="form-label fw-medium">Customer Name (Optional)</label>
                                <input type="text" name="customer_name" id="customer_name" class="form-control" 
                                       placeholder="Leave blank for 'Guest'">
                                <small class="text-muted">Enter customer name for personalized receipt</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="visit_date" class="form-label fw-medium">Visit Date <span class="text-danger">*</span></label>
                                <input type="date" name="visit_date" id="visit_date" class="form-control" required min="{{ date('Y-m-d') }}">
                            </div>
                            
                            <div class="mb-4">
                                <label for="additional_notes" class="form-label fw-medium">Additional Notes</label>
                                <textarea name="additional_notes" id="additional_notes" class="form-control" rows="2" 
                                          placeholder="Special requests or notes..."></textarea>
                            </div>
                        </div>
                        
                        {{-- Cashier Information Section --}}
                        <div class="cashier-section">
                            <h6 class="section-title text-pink fw-semibold mb-3">
                                <i class="bx bx-user-circle me-2"></i>Cashier Information
                            </h6>
                            
                            <div class="cashier-info-card p-3 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="cashier-avatar me-3">
                                        <i class="bx bx-user-circle text-pink" style="font-size: 2.5rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ Auth::user()->name }}</h6>
                                        <small class="text-muted">{{ Auth::user()->getRoleNames()->first() ?? 'Cashier' }}</small>
                                        <div class="mt-1">
                                            <small class="text-pink">
                                                <i class="bx bx-time me-1"></i>
                                                {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }} WIB
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Payment Section --}}
                        <div class="payment-section">
                            <h6 class="section-title text-pink fw-semibold mb-3">
                                <i class="bx bx-credit-card me-2"></i>Payment Information
                            </h6>
                            
                            <div class="mb-3">
                                <label for="payment_method" class="form-label fw-medium">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-control" required>
                                    <option value="cash" selected>Cash Payment</option>
                                    <option value="card">Card Payment</option>
                                </select>
                            </div>
                            
                            {{-- Cash Payment Section --}}
                            <div class="mb-4" id="cash-payment-section">
                                <label for="amount_tendered" class="form-label fw-medium">
                                    Amount Tendered 
                                    <span class="text-danger" id="amount-required-indicator">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                        name="amount_tendered" 
                                        id="amount_tendered" 
                                        class="form-control" 
                                        min="0" 
                                        step="0.01"
                                        placeholder="Enter cash amount">
                                </div>
                                <div class="text-end mt-2">
                                    <small class="text-muted">
                                        Change: <span id="change-amount" class="fw-bold text-success">Rp 0</span>
                                    </small>
                                </div>
                            </div>
                        
                        {{-- Action Buttons --}}
                        <div class="action-buttons">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg fw-semibold" id="checkout-btn" disabled>
                                    <i class="bx bx-check-circle me-2"></i>Process Payment
                                </button>
                                <button type="button" class="btn btn-outline-danger" id="clear-cart-btn" disabled>
                                    <i class="bx bx-trash me-2"></i>Clear Cart
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Promo Error Modal --}}
<div class="modal fade" id="promo-error-modal" tabindex="-1" aria-labelledby="promo-error-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-pink" id="promo-error-modal-label">
                    <i class="bx bx-error-circle me-2"></i>Promo Code Error
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="promo-error-message">
                Invalid or expired promo code
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    let cart = [];
    let subtotal = 0;
    let discount = 0;
    let total = 0;
    let appliedPromoCode = null;
    
    function init() {
        console.log('🏪 Initializing Enhanced POS System...');
        
        // Get DOM elements
        const cartItemsContainer = document.getElementById('cart-items');
        const emptyCartMessage = document.getElementById('empty-cart-message');
        const cartSummary = document.getElementById('cart-summary');
        const cartSubtotalElement = document.getElementById('cart-subtotal');
        const cartDiscountElement = document.getElementById('cart-discount');
        const discountRow = document.getElementById('discount-row');
        const cartTotalElement = document.getElementById('cart-total');
        const checkoutBtn = document.getElementById('checkout-btn');
        const clearCartBtn = document.getElementById('clear-cart-btn');
        const amountTenderedInput = document.getElementById('amount_tendered');
        const changeAmountElement = document.getElementById('change-amount');
        const paymentMethodSelect = document.getElementById('payment_method');
        const cashPaymentSection = document.getElementById('cash-payment-section');
        const promoCodeInput = document.getElementById('promo-code');
        const applyPromoBtn = document.getElementById('apply-promo');
        const visitDateInput = document.getElementById('visit_date');
        
        // Set default visit date to today
        if (visitDateInput) {
            visitDateInput.valueAsDate = new Date();
        }
        
        // Add to cart functionality
        const addButtons = document.querySelectorAll('.add-cart-btn');
        console.log('🎯 Found', addButtons.length, 'add to cart buttons');
        
        addButtons.forEach((button, index) => {
            button.addEventListener('click', function() {
                console.log('🛒 Add to cart clicked for button', index);
                
                const ticketId = this.getAttribute('data-id');
                const ticketName = this.getAttribute('data-name');
                const ticketPrice = parseFloat(this.getAttribute('data-price'));
                
                console.log('📝 Ticket data:', { ticketId, ticketName, ticketPrice });
                
                // Add button animation
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
                
                // Set selected ticket ID for promo validation
                if (document.getElementById('selected-ticket-id')) {
                    document.getElementById('selected-ticket-id').value = ticketId;
                }
                
                // Check if ticket already in cart
                const existingItemIndex = cart.findIndex(item => item.id === ticketId);
                
                if (existingItemIndex !== -1) {
                    // Increase quantity if already in cart
                    cart[existingItemIndex].quantity += 1;
                    cart[existingItemIndex].total = cart[existingItemIndex].quantity * cart[existingItemIndex].price;
                    console.log('➕ Increased quantity for existing item');
                    
                    // Show feedback
                    showToast('Item quantity increased!', 'success');
                } else {
                    // Add new item to cart
                    cart.push({
                        id: ticketId,
                        name: ticketName,
                        price: ticketPrice,
                        quantity: 1,
                        total: ticketPrice
                    });
                    console.log('🆕 Added new item to cart');
                    
                    // Show feedback
                    showToast('Item added to cart!', 'success');
                }
                
                console.log('🛒 Current cart:', cart);
                updateCart();
            });
        });
        
        // Remove item from cart - using event delegation
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item') || 
                (e.target.parentElement && e.target.parentElement.classList.contains('remove-item'))) {
                e.preventDefault();
                
                const button = e.target.classList.contains('remove-item') ? e.target : e.target.parentElement;
                const index = parseInt(button.getAttribute('data-index'));
                
                console.log('🗑️ Removing item at index:', index);
                
                // Add animation
                const cartItem = button.closest('.cart-item');
                if (cartItem) {
                    cartItem.style.transform = 'translateX(-100%)';
                    cartItem.style.opacity = '0';
                    setTimeout(() => {
                        cart.splice(index, 1);
                        updateCart();
                        showToast('Item removed from cart', 'info');
                    }, 300);
                } else {
                    cart.splice(index, 1);
                    updateCart();
                    showToast('Item removed from cart', 'info');
                }
            }
        });
        
        // Update quantity - using event delegation
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('item-quantity')) {
                const index = parseInt(e.target.getAttribute('data-index'));
                const quantity = parseInt(e.target.value);
                
                console.log('🔢 Updating quantity for item', index, 'to', quantity);
                
                if (quantity > 0 && quantity <= 99) {
                    cart[index].quantity = quantity;
                    cart[index].total = cart[index].price * quantity;
                    updateCart();
                    showToast('Quantity updated', 'info');
                } else {
                    e.target.value = cart[index].quantity; // Reset to previous value
                    showToast('Invalid quantity. Please enter 1-99', 'warning');
                }
            }
        });
        
        // Clear cart
        if (clearCartBtn) {
            clearCartBtn.addEventListener('click', function() {
                if (cart.length === 0) return;
                
                if (confirm('Are you sure you want to clear the cart?')) {
                    console.log('🧹 Clearing cart');
                    cart = [];
                    discount = 0;
                    appliedPromoCode = null;
                    if (promoCodeInput) {
                        promoCodeInput.value = '';
                        promoCodeInput.disabled = false;
                    }
                    resetPromoButton();
                    updateCart();
                    showToast('Cart cleared', 'info');
                }
            });
        }
        
        // Payment method change
        if (paymentMethodSelect) {
            paymentMethodSelect.addEventListener('change', function() {
                if (this.value === 'cash') {
                    if (cashPaymentSection) {
                        cashPaymentSection.style.display = 'block';
                        cashPaymentSection.classList.add('fade-in');
                    }
                    if (amountTenderedInput) amountTenderedInput.required = true;
                } else {
                    if (cashPaymentSection) {
                        cashPaymentSection.style.display = 'none';
                    }
                    if (amountTenderedInput) amountTenderedInput.required = false;
                }
            });
        }
        
        // Amount tendered calculation
        if (amountTenderedInput) {
            amountTenderedInput.addEventListener('input', function() {
                const amountTendered = parseFloat(this.value) || 0;
                const change = amountTendered - total;
                if (changeAmountElement) {
                    changeAmountElement.textContent = 'Rp ' + formatNumber(Math.max(0, change));
                    
                    // Change color based on amount
                    if (change < 0) {
                        changeAmountElement.className = 'fw-bold text-danger';
                    } else {
                        changeAmountElement.className = 'fw-bold text-success';
                    }
                }
            });
        }
        
        // Apply promo code - FIXED URL
        if (applyPromoBtn) {
            applyPromoBtn.addEventListener('click', function() {
                const promoCode = promoCodeInput ? promoCodeInput.value.trim() : '';
                const selectedTicketId = document.getElementById('selected-ticket-id') ? document.getElementById('selected-ticket-id').value : '';
                const visitDate = visitDateInput ? visitDateInput.value : '';
                
                if (!promoCode) {
                    showPromoError('Please enter a promo code');
                    return;
                }
                
                if (!selectedTicketId) {
                    showPromoError('Please add a ticket to the cart first');
                    return;
                }
                
                // Show loading state
                this.disabled = true;
                this.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Applying...';
                
                // Reset discount
                discount = 0;
                appliedPromoCode = null;
                updateCartSummary();
                
                // AJAX call for promo validation
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.warn('CSRF token not found');
                    showPromoError('Security token not found. Please refresh the page.');
                    resetPromoButton();
                    return;
                }
                
                // DEBUGGING: Log data yang dikirim
                const requestData = {
                    code: promoCode,
                    subtotal: subtotal,
                    ticket_id: selectedTicketId,
                    visit_date: visitDate
                };
                console.log('🚀 Sending promo request:', requestData);
                
                // FIXED: Use the correct URL based on current route structure
                const applyPromoUrl = window.location.origin + '/backend/beach-tickets/pos/apply-promo';
                console.log('📡 Fetch URL:', applyPromoUrl);
                
                fetch(applyPromoUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => {
                    console.log('📡 Response status:', response.status);
                    console.log('📡 Response headers:', response.headers);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    
                    return response.json();
                })
                .then(data => {
                    console.log('📨 Response data:', data);
                    
                    if (data.success) {
                        discount = data.discount;
                        total = data.total;
                        appliedPromoCode = data.promo_code;
                        
                        if (discountRow) discountRow.style.display = 'flex';
                        if (cartDiscountElement) cartDiscountElement.textContent = 'Rp ' + formatNumber(discount);
                        if (cartTotalElement) cartTotalElement.textContent = 'Rp ' + formatNumber(total);
                        if (amountTenderedInput) amountTenderedInput.min = total;
                        
                        // UPDATE HIDDEN INPUT FOR FORM SUBMISSION
                        updatePromoCodeHiddenInput();
                        
                        showToast('Promo code applied successfully: ' + data.message, 'success');
                        
                        // Update button to show success
                        this.innerHTML = '<i class="bx bx-check me-1"></i>Applied';
                        this.className = 'btn btn-success';
                        
                        // Disable promo input
                        if (promoCodeInput) promoCodeInput.disabled = true;
                    } else {
                        console.error('❌ Promo error:', data.message);
                        showPromoError(data.message);
                        resetPromoButton();
                    }
                })
                .catch(error => {
                    console.error('💥 Network/Parse error:', error);
                    showPromoError(`Network error: ${error.message}. Please check browser console for details.`);
                    resetPromoButton();
                });
            });
        }
        
        // Form validation - SIMPLIFIED
        const form = document.getElementById('pos-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const visitDate = document.getElementById('visit_date').value;
                const paymentMethod = document.getElementById('payment_method').value;
                const amountTendered = document.getElementById('amount_tendered').value;
                
                // Customer name is now optional - no validation needed
                
                if (!visitDate) {
                    e.preventDefault();
                    showToast('Please select visit date', 'warning');
                    document.getElementById('visit_date').focus();
                    return;
                }
                
                if (paymentMethod === 'cash' && (!amountTendered || parseFloat(amountTendered) < total)) {
                    e.preventDefault();
                    showToast('Amount tendered must be at least Rp ' + formatNumber(total), 'warning');
                    document.getElementById('amount_tendered').focus();
                    return;
                }
                
                if (cart.length === 0) {
                    e.preventDefault();
                    showToast('Please add items to cart before checkout', 'warning');
                    return;
                }
                
                // Show loading state
                const submitBtn = e.target.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i>Processing...';
                }
            });
        }
        
        console.log('✅ Enhanced POS System initialized successfully');
    }
    
    function updateCart() {
        console.log('🔄 Updating cart display...');
        
        const cartItemsContainer = document.getElementById('cart-items');
        const emptyCartMessage = document.getElementById('empty-cart-message');
        const cartSummary = document.getElementById('cart-summary');
        const checkoutBtn = document.getElementById('checkout-btn');
        const clearCartBtn = document.getElementById('clear-cart-btn');
        const promoCodeInput = document.getElementById('promo-code');
        
        if (!cartItemsContainer) {
            console.error('❌ Cart items container not found');
            return;
        }
        
        if (cart.length === 0) {
            // Empty cart state
            if (emptyCartMessage) emptyCartMessage.style.display = 'block';
            if (cartSummary) cartSummary.style.display = 'none';
            if (checkoutBtn) {
                checkoutBtn.disabled = true;
                checkoutBtn.innerHTML = '<i class="bx bx-check-circle me-2"></i>Process Payment';
            }
            if (clearCartBtn) clearCartBtn.disabled = true;
            
            subtotal = 0;
            discount = 0;
            total = 0;
            appliedPromoCode = null;
            
            // CLEAR PROMO CODE INPUT AND RESET BUTTON
            if (promoCodeInput) {
                promoCodeInput.value = '';
                promoCodeInput.disabled = false;
            }
            resetPromoButton();
            
            console.log('📭 Cart is empty');
        } else {
            // Cart has items
            if (emptyCartMessage) emptyCartMessage.style.display = 'none';
            if (cartSummary) {
                cartSummary.style.display = 'block';
                cartSummary.classList.add('fade-in');
            }
            if (checkoutBtn) {
                checkoutBtn.disabled = false;
                checkoutBtn.innerHTML = '<i class="bx bx-check-circle me-2"></i>Process Payment';
            }
            if (clearCartBtn) clearCartBtn.disabled = false;
            
            // Build cart HTML
            let html = '';
            subtotal = 0;
            
            cart.forEach((item, index) => {
                subtotal += item.total;
                
                html += `
                    <div class="cart-item fade-in">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark fw-semibold">${escapeHtml(item.name)}</h6>
                                <small class="text-muted">Rp${formatNumber(item.price)} each</small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-item" 
                                data-index="${index}" title="Remove item">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <label class="me-2 small fw-medium">Qty:</label>
                                <input type="number" class="form-control form-control-sm item-quantity" 
                                    min="1" max="99" value="${item.quantity}" style="width: 70px;" data-index="${index}">
                            </div>
                            <div class="fw-bold text-success fs-6">Rp${formatNumber(item.total)}</div>
                        </div>
                        <input type="hidden" name="items[${index}][id]" value="${item.id}">
                        <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                    </div>
                `;
            });
            
            cartItemsContainer.innerHTML = html;
            
            // Recalculate promo discount if applied
            if (appliedPromoCode) {
                if (appliedPromoCode.discount_type === 'percentage') {
                    discount = (subtotal * appliedPromoCode.discount_amount) / 100;
                    
                    // Apply max_discount if exists
                    if (appliedPromoCode.max_discount && appliedPromoCode.max_discount > 0) {
                        discount = Math.min(discount, appliedPromoCode.max_discount);
                    }
                } else {
                    discount = appliedPromoCode.discount_amount;
                }
                discount = Math.min(discount, subtotal); // Cap at subtotal
            } else {
                discount = 0;
            }
            
            total = subtotal - discount;
            
            // UPDATE HIDDEN INPUT FOR PROMO CODE
            updatePromoCodeHiddenInput();
            
            console.log('💰 Cart totals:', { subtotal, discount, total });
            updateCartSummary();
        }
    }
    
    // NEW FUNCTION: Update hidden promo code input for form submission
    function updatePromoCodeHiddenInput() {
        const form = document.getElementById('pos-form');
        
        // Remove existing hidden promo input
        const existingPromoInput = form.querySelector('input[name="applied_promo_code"]');
        if (existingPromoInput) {
            existingPromoInput.remove();
        }
        
        // Add current promo code if applied
        if (appliedPromoCode && discount > 0) {
            const hiddenPromoInput = document.createElement('input');
            hiddenPromoInput.type = 'hidden';
            hiddenPromoInput.name = 'applied_promo_code';
            hiddenPromoInput.value = appliedPromoCode.code;
            form.appendChild(hiddenPromoInput);
            
            console.log('✅ Added hidden promo input:', appliedPromoCode.code);
        }
    }
    
    function updateCartSummary() {
        const cartSubtotalElement = document.getElementById('cart-subtotal');
        const cartDiscountElement = document.getElementById('cart-discount');
        const discountRow = document.getElementById('discount-row');
        const cartTotalElement = document.getElementById('cart-total');
        const amountTenderedInput = document.getElementById('amount_tendered');
        const changeAmountElement = document.getElementById('change-amount');
        const paymentMethodSelect = document.getElementById('payment_method');
        
        // Update subtotal
        if (cartSubtotalElement) {
            cartSubtotalElement.textContent = 'Rp ' + formatNumber(subtotal);
        }
        
        // Update discount display
        if (discount > 0) {
            if (discountRow) {
                discountRow.style.display = 'flex';
                discountRow.classList.add('fade-in');
            }
            if (cartDiscountElement) cartDiscountElement.textContent = 'Rp ' + formatNumber(discount);
        } else {
            if (discountRow) discountRow.style.display = 'none';
        }
        
        // Update total
        if (cartTotalElement) {
            cartTotalElement.textContent = 'Rp ' + formatNumber(total);
        }
        
        // Update payment section based on method
        if (paymentMethodSelect && amountTenderedInput) {
            const paymentMethod = paymentMethodSelect.value;
            
            if (paymentMethod === 'cash') {
                amountTenderedInput.min = total;
                amountTenderedInput.placeholder = 'Min: Rp ' + formatNumber(total);
                amountTenderedInput.disabled = false;
                amountTenderedInput.required = true;
            } else if (paymentMethod === 'card') {
                // Auto-fill with exact total for card payment
                amountTenderedInput.value = total;
                amountTenderedInput.placeholder = 'Exact amount (card payment)';
                amountTenderedInput.disabled = true;
                amountTenderedInput.required = false;
                
                // Set change to 0 for card payment
                if (changeAmountElement) {
                    changeAmountElement.textContent = 'Rp 0';
                    changeAmountElement.className = 'fw-bold text-success';
                }
            }
        }
        
        // Update change calculation if amount tendered is entered (cash only)
        if (amountTenderedInput && amountTenderedInput.value && changeAmountElement && paymentMethodSelect?.value === 'cash') {
            const amountTendered = parseFloat(amountTenderedInput.value) || 0;
            const change = amountTendered - total;
            changeAmountElement.textContent = 'Rp ' + formatNumber(Math.max(0, change));
            
            if (change < 0) {
                changeAmountElement.className = 'fw-bold text-danger';
            } else {
                changeAmountElement.className = 'fw-bold text-success';
            }
        }
    }
    
    function resetPromoButton() {
        const applyPromoBtn = document.getElementById('apply-promo');
        if (applyPromoBtn) {
            applyPromoBtn.disabled = false;
            applyPromoBtn.innerHTML = 'Apply';
            applyPromoBtn.className = 'btn btn-outline-pink';
        }
    }
    
    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
    
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
    
    function showToast(message, type = 'info') {
        // Simple toast notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : type === 'danger' ? 'danger' : 'info'} position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; opacity: 0; transform: translateX(100%); transition: all 0.3s ease;';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bx ${type === 'success' ? 'bx-check-circle' : type === 'warning' ? 'bx-error' : type === 'danger' ? 'bx-x-circle' : 'bx-info-circle'} me-2"></i>
                ${message}
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto remove
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }
    
    function showPromoError(message) {
        const modalElement = document.getElementById('promo-error-modal');
        const messageElement = document.getElementById('promo-error-message');
        
        if (messageElement) messageElement.textContent = message;
        
        if (modalElement && window.bootstrap && window.bootstrap.Modal) {
            const modal = new window.bootstrap.Modal(modalElement);
            modal.show();
        } else {
            showToast(message, 'danger');
        }
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Global debug functions
    window.debugCart = function() {
        console.log('🛒 Current cart state:', cart);
        console.log('💰 Totals:', { subtotal, discount, total });
        console.log('🎟️ Applied promo:', appliedPromoCode);
    };
    
    window.clearDebugCart = function() {
        cart = [];
        updateCart();
        console.log('🧹 Cart cleared via debug');
    };
    
})();
</script>
@endsection
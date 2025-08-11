@extends('admin.admin_dashboard')
@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Beach Ticket</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('backend.beach-tickets.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.beach-tickets.orders.index') }}">Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Order</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('backend.beach-tickets.orders.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to Orders
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Create New Beach Ticket Order</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('backend.beach-tickets.orders.store') }}" method="POST">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="mb-3">Ticket Information</h6>
                    
                    <div class="form-group mb-3">
                        <label for="beach_ticket_id" class="form-label">Select Ticket <span class="text-danger">*</span></label>
                        <select name="beach_ticket_id" id="beach_ticket_id" class="form-control @error('beach_ticket_id') is-invalid @enderror" required>
                            <option value="">-- Select Ticket --</option>
                            <optgroup label="Lalassa Beach - Regular Tickets">
                                @foreach($tickets->where('beach_name', 'lalassa')->where('ticket_type', 'regular') as $ticket)
                                <option value="{{ $ticket->id }}" data-price="{{ $ticket->price }}">
                                    {{ $ticket->name }} - Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Lalassa Beach - Bundling Tickets">
                                @foreach($tickets->where('beach_name', 'lalassa')->where('ticket_type', 'bundling') as $ticket)
                                <option value="{{ $ticket->id }}" data-price="{{ $ticket->price }}">
                                    {{ $ticket->name }} - Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Bodur Beach - Regular Tickets">
                                @foreach($tickets->where('beach_name', 'bodur')->where('ticket_type', 'regular') as $ticket)
                                <option value="{{ $ticket->id }}" data-price="{{ $ticket->price }}">
                                    {{ $ticket->name }} - Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Bodur Beach - Bundling Tickets">
                                @foreach($tickets->where('beach_name', 'bodur')->where('ticket_type', 'bundling') as $ticket)
                                <option value="{{ $ticket->id }}" data-price="{{ $ticket->price }}">
                                    {{ $ticket->name }} - Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                </option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('beach_ticket_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" min="1" required>
                        @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="visit_date" class="form-label">Visit Date <span class="text-danger">*</span></label>
                        <input type="date" name="visit_date" id="visit_date" class="form-control @error('visit_date') is-invalid @enderror" value="{{ old('visit_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                        @error('visit_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="additional_request" class="form-label">Additional Request</label>
                        <textarea name="additional_request" id="additional_request" class="form-control @error('additional_request') is-invalid @enderror" rows="3">{{ old('additional_request') }}</textarea>
                        @error('additional_request')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h6 class="mb-3">Customer Information</h6>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Customer Type</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="customer_type" id="customer_type_new" value="new" checked>
                            <label class="form-check-label" for="customer_type_new">New Customer</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="customer_type" id="customer_type_existing" value="existing">
                            <label class="form-check-label" for="customer_type_existing">Existing Customer</label>
                        </div>
                    </div>
                    
                    <div id="existing_customer_section" style="display: none;">
                        <div class="form-group mb-3">
                            <label for="customer_id" class="form-label">Select Customer <span class="text-danger">*</span></label>
                            <select name="customer_id" id="customer_id" class="form-control">
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div id="new_customer_section">
                        <div class="form-group mb-3">
                            <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" required>
                            @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="customer_email" class="form-label">Customer Email <span class="text-danger">*</span></label>
                            <input type="email" name="customer_email" id="customer_email" class="form-control @error('customer_email') is-invalid @enderror" value="{{ old('customer_email') }}" required>
                            @error('customer_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="customer_phone" class="form-label">Customer Phone</label>
                            <input type="text" name="customer_phone" id="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone') }}">
                            @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <h6 class="mt-4 mb-3">Payment Information</h6>
                    
                    <div class="form-group mb-3">
                        <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        </select>
                        @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="total_price" class="form-label">Total Price</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" id="total_price" class="form-control" readonly>
                        </div>
                    </div>
                    
                    <input type="hidden" name="is_offline_order" value="1">
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-primary">Create Order</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle customer type toggle
        const customerTypeRadios = document.querySelectorAll('input[name="customer_type"]');
        const existingCustomerSection = document.getElementById('existing_customer_section');
        const newCustomerSection = document.getElementById('new_customer_section');
        const customerIdSelect = document.getElementById('customer_id');
        const customerNameInput = document.getElementById('customer_name');
        const customerEmailInput = document.getElementById('customer_email');
        const customerPhoneInput = document.getElementById('customer_phone');
        
        customerTypeRadios.forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.value === 'existing') {
                    existingCustomerSection.style.display = 'block';
                    newCustomerSection.style.display = 'none';
                    customerIdSelect.required = true;
                    customerNameInput.required = false;
                    customerEmailInput.required = false;
                } else {
                    existingCustomerSection.style.display = 'none';
                    newCustomerSection.style.display = 'block';
                    customerIdSelect.required = false;
                    customerNameInput.required = true;
                    customerEmailInput.required = true;
                }
            });
        });
        
        // Calculate total price
        const ticketSelect = document.getElementById('beach_ticket_id');
        const quantityInput = document.getElementById('quantity');
        const totalPriceInput = document.getElementById('total_price');
        
        function updateTotalPrice() {
            const selectedOption = ticketSelect.options[ticketSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const price = parseFloat(selectedOption.getAttribute('data-price'));
                const quantity = parseInt(quantityInput.value);
                const total = price * quantity;
                totalPriceInput.value = total.toLocaleString('id-ID');
            } else {
                totalPriceInput.value = '';
            }
        }
        
        ticketSelect.addEventListener('change', updateTotalPrice);
        quantityInput.addEventListener('input', updateTotalPrice);
        
        // Initialize total price
        updateTotalPrice();
    });
</script>
@endpush
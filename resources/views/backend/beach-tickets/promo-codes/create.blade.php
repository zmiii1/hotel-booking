@extends('admin.admin_dashboard')

@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ isset($promoCode) ? 'Edit' : 'Create' }} Beach Ticket Promo Code</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('backend.beach-tickets.promo-codes.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Promo Codes
        </a>
    </div>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ isset($promoCode) ? route('backend.beach-tickets.promo-codes.update', $promoCode->id) : route('backend.beach-tickets.promo-codes.store') }}" method="POST">
            @csrf
            @if(isset($promoCode))
                @method('PUT')
            @endif
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="code">Promo Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', isset($promoCode) ? $promoCode->code : '') }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Enter a unique code for this promotion.</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="is_active">Status</label>
                        <div class="form-check mt-2">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ (old('is_active', isset($promoCode) ? $promoCode->is_active : true)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="2">{{ old('description', isset($promoCode) ? $promoCode->description : '') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Optional description of this promo code.</small>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="discount_type">Discount Type <span class="text-danger">*</span></label>
                        <select name="discount_type" id="discount_type" class="form-control @error('discount_type') is-invalid @enderror" required>
                            <option value="percentage" {{ old('discount_type', isset($promoCode) ? $promoCode->discount_type : '') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                            <option value="fixed_amount" {{ old('discount_type', isset($promoCode) ? $promoCode->discount_type : '') == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount</option>
                        </select>
                        @error('discount_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="discount_value">Discount Value <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="discount_value" id="discount_value" class="form-control @error('discount_value') is-invalid @enderror" value="{{ old('discount_value', isset($promoCode) ? $promoCode->discount_value : '') }}" step="0.01" min="0" required>
                            <div class="input-group-append">
                                <span class="input-group-text discount-type-append">%</span>
                            </div>
                        </div>
                        @error('discount_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row" id="percentage-options">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="min_purchase">Minimum Purchase Amount</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" name="min_purchase" id="min_purchase" class="form-control @error('min_purchase') is-invalid @enderror" value="{{ old('min_purchase', isset($promoCode) ? $promoCode->min_purchase : '') }}" step="0.01" min="0">
                        </div>
                        @error('min_purchase')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Optional minimum purchase amount required.</small>
                    </div>
                </div>
                
                <div class="col-md-6 max-discount-container">
                    <div class="form-group mb-3">
                        <label for="max_discount">Maximum Discount Amount</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" name="max_discount" id="max_discount" class="form-control @error('max_discount') is-invalid @enderror" value="{{ old('max_discount', isset($promoCode) ? $promoCode->max_discount : '') }}" step="0.01" min="0">
                        </div>
                        @error('max_discount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Optional cap on the discount amount (for percentage discounts).</small>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="start_date">Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', isset($promoCode) ? (is_string($promoCode->start_date) ? $promoCode->start_date : $promoCode->start_date->format('Y-m-d')) : '') }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="end_date">End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', isset($promoCode) ? (is_string($promoCode->end_date) ? $promoCode->end_date : $promoCode->end_date->format('Y-m-d')) : '') }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="max_uses">Maximum Uses</label>
                        <input type="number" name="max_uses" id="max_uses" class="form-control @error('max_uses') is-invalid @enderror" value="{{ old('max_uses', isset($promoCode) ? $promoCode->max_uses : '') }}" min="0">
                        @error('max_uses')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Optional. Maximum number of times this promo code can be used. Leave empty or set to 0 for unlimited usage.</small>
                    </div>
                </div>
                <input type="hidden" name="applies_to" value="beach_tickets">
                <div class="form-group mb-3">
                    <label for="beach_preference">Beach Preference</label>
                    <select name="beach_preference" id="beach_preference" class="form-control">
                        <option value="all">All Beaches</option>
                        <option value="lalassa">Lalassa Beach Only</option>
                        <option value="bodur">Bodur Beach Only</option>
                    </select>
                    <small class="text-muted">Select which beach this promo focuses on. You can still select specific tickets below.</small>
                </div>
            </div>
            
            <div class="form-group mb-3">
                <label>Specific Beach Tickets</label>
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> If you select specific tickets below, the promo code will only apply to those tickets. If none are selected, it applies to all tickets in the selected beach.
                        </div>
                        
                        {{-- Lalassa Beach Tickets --}}
                        <h6 class="mb-2">Lalassa Beach Tickets</h6>
                        <div class="row mb-3">
                            @foreach($beachTickets->where('beach_name', 'lalassa') as $ticket)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input ticket-checkbox" 
                                        type="checkbox" 
                                        name="ticket_ids[]" 
                                        value="{{ $ticket->id }}" 
                                        id="ticket_{{ $ticket->id }}"
                                        data-beach="lalassa"
                                        {{ (isset($promoCode) && $promoCode->beachTickets->contains($ticket->id)) || 
                                            (is_array(old('ticket_ids')) && in_array($ticket->id, old('ticket_ids'))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ticket_{{ $ticket->id }}">
                                        {{ $ticket->name }} ({{ ucfirst($ticket->ticket_type) }})
                                        <span class="text-muted">(Rp {{ number_format($ticket->price, 0, ',', '.') }})</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        {{-- Bodur Beach Tickets --}}
                        <h6 class="mb-2">Bodur Beach Tickets</h6>
                        <div class="row">
                            @foreach($beachTickets->where('beach_name', 'bodur') as $ticket)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input ticket-checkbox" 
                                        type="checkbox" 
                                        name="ticket_ids[]" 
                                        value="{{ $ticket->id }}" 
                                        id="ticket_{{ $ticket->id }}"
                                        data-beach="bodur"
                                        {{ (isset($promoCode) && $promoCode->beachTickets->contains($ticket->id)) || 
                                            (is_array(old('ticket_ids')) && in_array($ticket->id, old('ticket_ids'))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ticket_{{ $ticket->id }}">
                                        {{ $ticket->name }} ({{ ucfirst($ticket->ticket_type) }})
                                        <span class="text-muted">(Rp {{ number_format($ticket->price, 0, ',', '.') }})</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <small class="text-muted">Leave all unchecked to apply to all tickets in the selected beach.</small>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ isset($promoCode) ? 'Update' : 'Create' }} Promo Code
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Update untuk menggunakan beach_preference instead of applies_to
    const beachPreferenceSelect = document.getElementById('beach_preference');
    const ticketCheckboxes = document.querySelectorAll('.ticket-checkbox');
    
    function updateTicketCheckboxes() {
        const selectedBeach = beachPreferenceSelect.value;
        
        if (selectedBeach === 'all') {
            ticketCheckboxes.forEach(checkbox => {
                checkbox.disabled = false;
                checkbox.closest('.form-check').style.opacity = '1';
            });
            return;
        }
        
        ticketCheckboxes.forEach(checkbox => {
            const checkboxBeach = checkbox.getAttribute('data-beach');
            
            if (checkboxBeach === selectedBeach) {
                checkbox.disabled = false;
                checkbox.closest('.form-check').style.opacity = '1';
            } else {
                checkbox.disabled = true;
                checkbox.checked = false;
                checkbox.closest('.form-check').style.opacity = '0.5';
            }
        });
    }
    
    beachPreferenceSelect.addEventListener('change', updateTicketCheckboxes);
    updateTicketCheckboxes(); // Initialize
});
</script>
@endpush
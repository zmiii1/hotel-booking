@extends('admin.admin_dashboard')
@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Room Promo Code: {{ $promoCode->code }}</h4>
                    </div>
                    <div class="card-body">
                        <!-- Display Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Display Success Message -->
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Display Error Message -->
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="post" action="{{ route('promo.codes.update', $promoCode->id) }}">
                            @csrf
                            
                            <!-- Hidden field untuk applies_to -->
                            <input type="hidden" name="applies_to" value="rooms">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Coupon Code</label>
                                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $promoCode->code) }}" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Description</label>
                                    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $promoCode->description) }}">
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Discount Type</label>
                                    <select class="form-select @error('discount_type') is-invalid @enderror" name="discount_type" required>
                                        <option value="percentage" {{ old('discount_type', $promoCode->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        <option value="fixed_amount" {{ old('discount_type', $promoCode->discount_type) == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount</option>
                                    </select>
                                    @error('discount_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Discount Value
                                        @if($promoCode->discount_type == 'percentage')
                                            (%)
                                        @else
                                            (Amount)
                                        @endif
                                    </label>
                                    <input type="number" step="0.01" name="discount_value" class="form-control @error('discount_value') is-invalid @enderror" value="{{ old('discount_value', $promoCode->discount_value) }}" required>
                                    @error('discount_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Minimum Purchase (Optional)</label>
                                    <input type="number" step="0.01" name="min_purchase" class="form-control @error('min_purchase') is-invalid @enderror" value="{{ old('min_purchase', $promoCode->min_purchase) }}" min="0">
                                    @error('min_purchase')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Minimum amount required to use this promo code</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Maximum Discount (Optional)</label>
                                    <input type="number" step="0.01" name="max_discount" class="form-control @error('max_discount') is-invalid @enderror" value="{{ old('max_discount', $promoCode->max_discount) }}" min="0">
                                    @error('max_discount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Maximum discount cap for percentage discounts</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                           value="{{ old('start_date', is_string($promoCode->start_date) ? $promoCode->start_date : $promoCode->start_date->format('Y-m-d')) }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                           value="{{ old('end_date', is_string($promoCode->end_date) ? $promoCode->end_date : $promoCode->end_date->format('Y-m-d')) }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Max Uses (Leave blank for unlimited)</label>
                                    <input type="number" name="max_uses" class="form-control @error('max_uses') is-invalid @enderror" value="{{ old('max_uses', $promoCode->max_uses) }}" min="1">
                                    @error('max_uses')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $promoCode->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Active</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Apply to Specific Rooms</label>
                                    <small class="text-muted d-block mb-2">
                                        Currently applied to: 
                                        @if($promoCode->rooms->isEmpty())
                                            <span class="badge bg-info">All Rooms</span>
                                        @else
                                            <span class="badge bg-success">{{ $promoCode->rooms->count() }} Selected Room(s)</span>
                                        @endif
                                    </small>
                                    <select class="form-select select2 @error('room_ids') is-invalid @enderror" name="room_ids[]" multiple>
                                        @forelse($rooms as $room)
                                            <option value="{{ $room->id }}" {{ in_array($room->id, old('room_ids', $promoCode->rooms->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $room->type->name ?? 'Room ' . $room->id }} - Room #{{ $room->room_number ?? $room->id }}
                                            </option>
                                        @empty
                                            <option disabled>No rooms available</option>
                                        @endforelse
                                    </select>
                                    @error('room_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Leave empty to apply to all rooms, or select multiple rooms to restrict this promo code</small>
                                </div>
                            </div>

                            <!-- Show currently selected rooms -->
                            @if($promoCode->rooms->isNotEmpty())
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <strong>Currently Applied to These Rooms:</strong>
                                            <ul class="mb-0 mt-2">
                                                @foreach($promoCode->rooms as $room)
                                                    <li>{{ $room->type->name ?? 'Room ' . $room->id }} - Room #{{ $room->room_number ?? $room->id }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Update Room Promo Code
                                    </button>
                                    <a href="{{ route('promo.codes') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 for room selection
        $('.select2').select2({
            placeholder: "Select rooms (leave empty for all rooms)",
            allowClear: true,
            width: '100%'
        });

        // Add some visual feedback for discount type selection
        $('select[name="discount_type"]').on('change', function() {
            const discountValue = $('input[name="discount_value"]');
            const label = discountValue.siblings('label');
            
            if (this.value === 'percentage') {
                label.text('Discount Value (%)');
                discountValue.attr('max', '100');
                discountValue.attr('placeholder', 'e.g., 10 for 10%');
            } else if (this.value === 'fixed_amount') {
                label.text('Discount Value (Amount)');
                discountValue.removeAttr('max');
                discountValue.attr('placeholder', 'e.g., 50.00');
            }
        });
    });
</script>
@endpush
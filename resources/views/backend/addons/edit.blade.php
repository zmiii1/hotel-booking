@extends('admin.admin_dashboard')
@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Edit Room Add-on</h6>
                    
                    <form class="forms-sample" method="POST" action="{{ route('room-addons.update', $roomAddon->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Add-on Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $roomAddon->name) }}" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $key => $category)
                                            <option value="{{ $key }}" {{ old('category', $roomAddon->category) == $key ? 'selected' : '' }}>{{ $category }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $roomAddon->description) }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price (Rp)</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $roomAddon->price) }}" required min="0" step="1000">
                                    @error('price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="normal_price" class="form-label">Normal Price (Rp) <small class="text-muted">(Optional)</small></label>
                                    <input type="number" class="form-control @error('normal_price') is-invalid @enderror" id="normal_price" name="normal_price" value="{{ old('normal_price', $roomAddon->normal_price) }}" min="0" step="1000">
                                    @error('normal_price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price_type" class="form-label">Price Type</label>
                                    <select class="form-select @error('price_type') is-invalid @enderror" id="price_type" name="price_type" required>
                                        <option value="">Select Price Type</option>
                                        @foreach($priceTypes as $key => $type)
                                            <option value="{{ $key }}" {{ old('price_type', $roomAddon->price_type) == $key ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                    @error('price_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="for_guests_type" class="form-label">For Guest Type</label>
                                    <select class="form-select @error('for_guests_type') is-invalid @enderror" id="for_guests_type" name="for_guests_type" required>
                                        <option value="">Select Guest Type</option>
                                        @foreach($guestTypes as $key => $type)
                                            <option value="{{ $key }}" {{ old('for_guests_type', $roomAddon->for_guests_type) == $key ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                    @error('for_guests_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3" id="guestCountContainer" style="{{ $roomAddon->for_guests_type == 'specific' ? 'display: block;' : 'display: none;' }}">
                            <label for="guest_count" class="form-label">Number of Guests</label>
                            <input type="number" class="form-control @error('guest_count') is-invalid @enderror" id="guest_count" name="guest_count" value="{{ old('guest_count', $roomAddon->guest_count) }}" min="1">
                            @error('guest_count')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Image <small class="text-muted">(Optional)</small></label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            
                            @if($roomAddon->image)
                                <div class="mt-2">
                                    <label class="form-label">Current Image:</label>
                                    <img src="{{ asset($roomAddon->image) }}" alt="{{ $roomAddon->name }}" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Include in Packages <small class="text-muted">(Optional)</small></label>
                            <div class="row">
                                @foreach($packages as $package)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="packages[]" value="{{ $package->id }}" id="package{{ $package->id }}" 
                                            {{ in_array($package->id, old('packages', $selectedPackages)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="package{{ $package->id }}">
                                            {{ $package->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_prepayment_required" name="is_prepayment_required" 
                                        {{ old('is_prepayment_required', $roomAddon->is_prepayment_required) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_prepayment_required">Prepayment Required</label>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_included" name="is_included" 
                                        {{ old('is_included', $roomAddon->is_included) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_included">Included in Package</label>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_bestseller" name="is_bestseller" 
                                        {{ old('is_bestseller', $roomAddon->is_bestseller) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_bestseller">Bestseller</label>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_sale" name="is_sale" 
                                        {{ old('is_sale', $roomAddon->is_sale) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_sale">On Sale</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="status" name="status" 
                                {{ old('status', $roomAddon->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Active Status</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary me-2">Update Add-on</button>
                        <a href="{{ route('room-addons.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forGuestsTypeSelect = document.getElementById('for_guests_type');
        const guestCountContainer = document.getElementById('guestCountContainer');
        
        // Show/hide guest count field based on selected guest type
        function toggleGuestCount() {
            if (forGuestsTypeSelect.value === 'specific') {
                guestCountContainer.style.display = 'block';
            } else {
                guestCountContainer.style.display = 'none';
            }
        }
        
        // Run on load
        toggleGuestCount();
        
        // Run on change
        forGuestsTypeSelect.addEventListener('change', toggleGuestCount);
    });
</script>

@endsection
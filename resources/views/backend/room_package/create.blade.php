@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-content">
    <div class="row profile-body">
        <div class="col-md-12 col-xl-12 middle-wrapper">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Add Room Package</h6>
                        
                        <form id="myForm" method="POST" action="{{ route('room.packages.store') }}" class="forms-sample">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Package Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="code" class="form-label">Package Code</label>
                                        <input type="text" name="code" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="price_adjustment" class="form-label">Price Adjustment (Rp)</label>
                                <input type="number" name="price_adjustment" class="form-control" value="0" required>
                                <small class="text-muted">This amount will be added to the base room price</small>
                            </div>
                            
                            <div class="inclusions-container mb-3">
                                <label class="form-label">Inclusions</label>
                                <div id="inclusions-wrapper">
                                    <div class="inclusion-item mb-2 d-flex align-items-center">
                                        <input type="text" name="inclusions[]" class="form-control me-2">
                                        <button type="button" class="btn btn-sm btn-success add-inclusion">+</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="amenities-container mb-3">
                                <label class="form-label">Amenities</label>
                                <div id="amenities-wrapper">
                                    <div class="amenity-item mb-2 d-flex align-items-center">
                                        <input type="text" name="amenities[]" class="form-control me-2">
                                        <button type="button" class="btn btn-sm btn-success add-amenity">+</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default">
                                        <label class="form-check-label" for="is_default">Set as Default Package</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="status" name="status" checked>
                                        <label class="form-check-label" for="status">Active</label>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary me-2">Save</button>
                            <a href="{{ route('room.packages') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Add more inclusions
        $(document).on('click', '.add-inclusion', function() {
            var newItem = `
                <div class="inclusion-item mb-2 d-flex align-items-center">
                    <input type="text" name="inclusions[]" class="form-control me-2">
                    <button type="button" class="btn btn-sm btn-danger remove-item">-</button>
                </div>`;
            $('#inclusions-wrapper').append(newItem);
        });
        
        // Add more amenities
        $(document).on('click', '.add-amenity', function() {
            var newItem = `
                <div class="amenity-item mb-2 d-flex align-items-center">
                    <input type="text" name="amenities[]" class="form-control me-2">
                    <button type="button" class="btn btn-sm btn-danger remove-item">-</button>
                </div>`;
            $('#amenities-wrapper').append(newItem);
        });
        
        // Remove item
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.inclusion-item, .amenity-item').remove();
        });
    });
</script>

@endsection
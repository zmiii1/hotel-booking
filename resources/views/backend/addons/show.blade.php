@extends('admin.admin_dashboard')
@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="card-title mb-0">Room Add-on Details</h6>
                        <div>
                            <a href="{{ route('room-addons.index') }}" class="btn btn-secondary">
                                <i data-feather="arrow-left"></i> Back to List
                            </a>
                            <a href="{{ route('room-addons.edit', $roomAddon->id) }}" class="btn btn-warning ms-2">
                                <i data-feather="edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Name</th>
                                    <td>{{ $roomAddon->name }}</td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>{{ $roomAddon->getCategoryNameAttribute() }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $roomAddon->description ?: 'No description' }}</td>
                                </tr>
                                <tr>
                                    <th>Price</th>
                                    <td>
                                        Rp {{ number_format($roomAddon->price, 0, ',', '.') }}
                                        @if($roomAddon->normal_price)
                                            <span class="text-decoration-line-through text-muted">
                                                Rp {{ number_format($roomAddon->normal_price, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Price Type</th>
                                    <td>{{ $roomAddon->getPriceTypeTextAttribute() }}</td>
                                </tr>
                                <tr>
                                    <th>For Guest Type</th>
                                    <td>
                                        {{ $roomAddon->getGuestTypeTextAttribute() }}
                                        @if($roomAddon->for_guests_type == 'specific' && $roomAddon->guest_count)
                                            ({{ $roomAddon->guest_count }} {{ $roomAddon->guest_count == 1 ? 'guest' : 'guests' }})
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($roomAddon->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="fw-bold">Add-on Image</h6>
                                @if($roomAddon->image)
                                    <img src="{{ asset($roomAddon->image) }}" alt="{{ $roomAddon->name }}" class="img-fluid" style="max-width: 100%; max-height: 200px;">
                                @else
                                    <p class="text-muted">No image available</p>
                                @endif
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="fw-bold">Features</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($roomAddon->is_prepayment_required)
                                        <span class="badge bg-primary">Prepayment Required</span>
                                    @endif
                                    
                                    @if($roomAddon->is_included)
                                        <span class="badge bg-info">Included in Package</span>
                                    @endif
                                    
                                    @if($roomAddon->is_bestseller)
                                        <span class="badge bg-warning">Bestseller</span>
                                    @endif
                                    
                                    @if($roomAddon->is_sale)
                                        <span class="badge bg-danger">On Sale</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <h6 class="fw-bold">Included in Packages</h6>
                                @if($roomAddon->packages->count() > 0)
                                    <ul class="list-group">
                                        @foreach($roomAddon->packages as $package)
                                            <li class="list-group-item">{{ $package->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">Not included in any package</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
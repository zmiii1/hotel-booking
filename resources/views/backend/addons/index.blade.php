@extends('admin.admin_dashboard')
@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-content">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Room Add-ons</h6>
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('room-addons.create') }}" class="btn btn-primary">
                            <i data-feather="plus"></i> Add New Add-on
                        </a>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Price Type</th>
                                    <th>Status</th>
                                    <th>Included In</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($addons as $key => $addon)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>
                                        @if($addon->image)
                                            <img src="{{ asset($addon->image) }}" alt="{{ $addon->name }}" style="width: 70px; height: 40px;">
                                        @else
                                            <span class="badge bg-light text-dark">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $addon->name }}</td>
                                    <td>{{ $addon->getCategoryNameAttribute() }}</td>
                                    <td>
                                        Rp {{ number_format($addon->price, 0, ',', '.') }}
                                        @if($addon->normal_price)
                                            <span class="text-decoration-line-through text-muted">
                                                Rp {{ number_format($addon->normal_price, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $addon->getPriceTypeTextAttribute() }}</td>
                                    <td>
                                        @if($addon->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($addon->packages->count() > 0)
                                            @foreach($addon->packages as $package)
                                                <span class="badge bg-info">{{ $package->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-light text-dark">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('room-addons.edit', $addon->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('room-addons.destroy', $addon->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this add-on?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@extends('admin.admin_dashboard')
@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-content">
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Promo List</h4>
                    <div class="page-title-right">
                        <a href="{{ route('promo.codes.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="page-length">
                                <select class="form-select form-select-sm" style="width: 80px;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> <span>items/page</span>
                            </div>
                            <div class="search-box">
                                <input type="text" class="form-control form-control-sm" placeholder="Search..." style="width: 200px;">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>PROMO CODE</th>
                                        <th>PROMO PERCENTAGE</th>
                                        <th>VALID DATE</th>
                                        <th>STATUS</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($promoCodes as $key => $item)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->discount_value }}%</td>
                                        <td>
                                            {{ date('d M Y', strtotime($item->start_date)) }} - 
                                            {{ date('d M Y', strtotime($item->end_date)) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $item->is_active ? 'success' : 'danger' }}">
                                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('promo.codes.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="{{ route('promo.codes.delete', $item->id) }}" class="btn btn-sm btn-danger" id="delete">Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                Showing {{ $promoCodes->firstItem() }} to {{ $promoCodes->lastItem() }} of {{ $promoCodes->total() }} entries
                            </div>
                            <div>
                                {{ $promoCodes->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
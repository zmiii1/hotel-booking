@extends('admin.admin_dashboard')
@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active">All Role Permission</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('add.roles.permission') }}" class="btn btn-primary px-5">Add Role Permission</a>
            </div>
        </div>
    </div>

    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Role Name</th>
                            <th>Permissions</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $key => $role)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td><span class="badge bg-primary">{{ $role->name }}</span></td>
                            <td>
                                @forelse($role->permissions as $permission)
                                    <span class="badge bg-secondary me-1">{{ $permission->name }}</span>
                                @empty
                                    <span class="text-muted">No permissions assigned</span>
                                @endforelse
                            </td>
                            <td>
                                <a href="{{ route('edit.roles.permission', $role->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                @if($role->permissions->count() > 0)
                                    <a href="{{ route('delete.roles.permission', $role->id) }}" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Remove all permissions from this role?')">Clear</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
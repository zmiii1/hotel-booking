@extends('admin.admin_dashboard')
@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active">All Admin</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('add.admin') }}" class="btn btn-primary px-5">Add Admin</a>
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $key => $admin)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td><strong>{{ $admin->username }}</strong></td>
                            <td>
                                @foreach($admin->roles as $role)
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $admin->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('edit.admin', $admin->id) }}" class="btn btn-warning btn-sm px-3">Edit</a>
                                @if(!$admin->hasRole('Super Admin') && $admin->id != auth()->user()->id)
                                    <a href="{{ route('reset.password', $admin->id) }}" 
                                       class="btn btn-info btn-sm px-3" 
                                       onclick="return confirm('Generate new password for this admin?')">Reset Password</a>
                                    <a href="{{ route('delete.admin', $admin->id) }}" 
                                       class="btn btn-danger btn-sm px-3" 
                                       onclick="return confirm('Are you sure you want to delete this admin?')">Delete</a>
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






@extends('admin.admin_dashboard')
@section('admin')

<link href="{{ asset('backend/assets/css/index.css') }}" rel="stylesheet">

<div class="page-content" style="padding: 0.5rem !important;">
    {{-- Welcome Section - Enhanced Pink Theme --}}
    <div class="row mb-2">
        <div class="col-12">
            <div class="card pink-gradient-card border-0 shadow-lg">
                <div class="card-body" style="padding: 1rem 1.5rem;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1 text-white fw-bold">Welcome, {{ Auth::user()->name }}! 🌟</h4>
                            <p class="mb-0 text-white-50">
                                Role: <span class="badge bg-light text-pink fw-semibold">{{ Auth::user()->getRoleNames()->first() }}</span>
                            </p>
                        </div>
                        <div class="text-end">
                            <small class="text-white-50">
                                <i class="bx bx-calendar me-1"></i>
                                {{ \Carbon\Carbon::now('Asia/Jakarta')->format('l, d F Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-2" style="row-gap: 0.5rem;">
        @role('Super Admin')
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card action-card pink-card border-0 shadow-hover">
                <div class="card-body text-center" style="padding: 1.5rem 0.75rem;">
                    <div class="icon-circle pink-bg mb-3">
                        <i class="bx bx-user-circle"></i>
                    </div>
                    <h6 class="text-white mb-2 fw-semibold">Admin Management</h6>
                    <a href="{{ route('all.admin') }}" class="btn btn-light btn-sm fw-semibold">
                        <i class="bx bx-arrow-right me-1"></i>Manage Admins
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card action-card green-card border-0 shadow-hover">
                <div class="card-body text-center" style="padding: 1.5rem 0.75rem;">
                    <div class="icon-circle green-bg mb-3">
                        <i class="bx bx-shield"></i>
                    </div>
                    <h6 class="text-white mb-2 fw-semibold">Roles & Permissions</h6>
                    <a href="{{ route('all.roles') }}" class="btn btn-light btn-sm fw-semibold">
                        <i class="bx bx-arrow-right me-1"></i>Manage Roles
                    </a>
                </div>
            </div>
        </div>
        @endrole
        
        @canany(['booking.view', 'dashboard.view'])
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card action-card blue-card border-0 shadow-hover">
                <div class="card-body text-center" style="padding: 1.5rem 0.75rem;">
                    <div class="icon-circle blue-bg mb-3">
                        <i class="bx bx-calendar-check"></i>
                    </div>
                    <h6 class="text-white mb-2 fw-semibold">Bookings</h6>
                    <a href="{{ route('admin.bookings') }}" class="btn btn-light btn-sm fw-semibold">
                        <i class="bx bx-arrow-right me-1"></i>View Bookings
                    </a>
                </div>
            </div>
        </div>
        @endcanany

        @can('pos.access')
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="card action-card orange-card border-0 shadow-hover">
                <div class="card-body text-center" style="padding: 1.5rem 0.75rem;">
                    <div class="icon-circle orange-bg mb-3">
                        <i class="bx bx-store"></i>
                    </div>
                    <h6 class="text-white mb-2 fw-semibold">POS System</h6>
                    <a href="{{ route('backend.pos.index') }}" class="btn btn-light btn-sm fw-semibold">
                        <i class="bx bx-arrow-right me-1"></i>Open POS
                    </a>
                </div>
            </div>
        </div>
        @endcan
    </div>

    {{-- Quick Stats - Pink Theme --}}
    <div class="row mb-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header pink-gradient-light" style="padding: 0.75rem 1rem;">
                    <h6 class="mb-0 fw-semibold text-white">
                        <i class="bx bx-chart-line me-2"></i>Quick Statistics
                    </h6>
                </div>
                <div class="card-body" style="padding: 1rem;">
                    <div class="row" style="row-gap: 0.75rem;">
                        <div class="col-xl-3 col-lg-6 col-md-6">
                            <div class="stats-card pink-stats">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon-new pink-icon me-3">
                                        <i class="bx bx-user"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 fw-bold text-pink">{{ App\Models\User::count() }}</h4>
                                        <p class="mb-0 text-muted small">Total Users</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-lg-6 col-md-6">
                            <div class="stats-card green-stats">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon-new green-icon me-3">
                                        <i class="bx bx-calendar-check"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 fw-bold text-success">{{ $totalBookings ?? 0 }}</h4>
                                        <p class="mb-0 text-muted small">Total Bookings</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @role('Super Admin')
                        <div class="col-xl-3 col-lg-6 col-md-6">
                            <div class="stats-card blue-stats">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon-new blue-icon me-3">
                                        <i class="bx bx-shield"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 fw-bold text-info">{{ Spatie\Permission\Models\Role::count() }}</h4>
                                        <p class="mb-0 text-muted small">Total Roles</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6">
                            <div class="stats-card orange-stats">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon-new orange-icon me-3">
                                        <i class="bx bx-key"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 fw-bold text-warning">{{ Spatie\Permission\Models\Permission::count() }}</h4>
                                        <p class="mb-0 text-muted small">Total Permissions</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endrole
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Links & System Info - Pink Theme --}}
    <div class="row" style="row-gap: 0.5rem;">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header pink-gradient-light" style="padding: 0.75rem 1rem;">
                    <h6 class="mb-0 fw-semibold text-white">
                        <i class="bx bx-link me-2"></i>Quick Links
                    </h6>
                </div>
                <div class="card-body" style="padding: 0.75rem 1rem;">
                    <div class="list-group list-group-flush">
                        @role('Super Admin')
                        <a href="{{ route('add.admin') }}" class="list-group-item list-group-item-action border-0 pink-hover" style="padding: 0.75rem 0;">
                            <i class="bx bx-plus-circle me-2 text-pink"></i>
                            <span class="fw-medium">Add New Admin</span>
                        </a>
                        <a href="{{ route('all.roles.permission') }}" class="list-group-item list-group-item-action border-0 pink-hover" style="padding: 0.75rem 0;">
                            <i class="bx bx-shield-quarter me-2 text-pink"></i>
                            <span class="fw-medium">Manage Role Permissions</span>
                        </a>
                        @endrole

                        <a href="{{ route('admin.profile') }}" class="list-group-item list-group-item-action border-0 pink-hover" style="padding: 0.75rem 0;">
                            <i class="bx bx-user me-2 text-pink"></i>
                            <span class="fw-medium">My Profile</span>
                        </a>
                        <a href="{{ route('admin.change.password') }}" class="list-group-item list-group-item-action border-0 pink-hover" style="padding: 0.75rem 0;">
                            <i class="bx bx-lock me-2 text-pink"></i>
                            <span class="fw-medium">Change Password</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header pink-gradient-light" style="padding: 0.75rem 1rem;">
                    <h6 class="mb-0 fw-semibold text-white">
                        <i class="bx bx-info-circle me-2"></i>System Information
                    </h6>
                </div>
                <div class="card-body" style="padding: 0.75rem 1rem;">
                    <div class="table-responsive">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <td style="padding: 0.5rem 0;" class="fw-medium text-pink">Laravel:</td>
                                <td style="padding: 0.5rem 0;">{{ app()->version() }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem 0;" class="fw-medium text-pink">PHP:</td>
                                <td style="padding: 0.5rem 0;">{{ PHP_VERSION }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem 0;" class="fw-medium text-pink">Time (WIB):</td>
                                <td style="padding: 0.5rem 0;">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('d M Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.5rem 0;" class="fw-medium text-pink">Role:</td>
                                <td style="padding: 0.5rem 0;">
                                    @foreach(Auth::user()->roles as $role)
                                        <span class="badge pink-badge">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
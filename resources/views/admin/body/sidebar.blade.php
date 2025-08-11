<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('backend/assets/images/logotl (3).png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Hello Admin!</h4>
        </div>
    </div>

    <ul class="metismenu" id="menu">
        
        {{-- DASHBOARD - All authenticated users can access --}}
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        {{-- ROOM MANAGEMENT - HANYA Admin & Super Admin --}}
        @can('room.type.view')
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i></div>
                <div class="menu-title">Manage Room Type</div>
            </a>
            <ul>
                <li><a href="{{ route('tlroom.list') }}"><i class='bx bx-radio-circle'></i>Tanjung Lesung Beach Resort</a></li>
                <li><a href="{{ route('kalicaaroom.list') }}"><i class='bx bx-radio-circle'></i>Kalicaa Villa</a></li>
                <li><a href="{{ route('lbvroom.list') }}"><i class='bx bx-radio-circle'></i>Ladda Bay Village</a></li>
                <li><a href="{{ route('lalassaroom.list') }}"><i class='bx bx-radio-circle'></i>Lalassa Beach Club</a></li>
            </ul>
        </li>
        @endcan

        @can('room.packages.manage')
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i></div>
                <div class="menu-title">Room Management</div>
            </a>
            <ul>
                @can('promo.view')
                <li><a href="{{ route('promo.codes') }}"><i class='bx bx-radio-circle'></i>Promo</a></li>
                @endcan
                
                @can('room.packages.manage')
                <li><a href="{{ route('room.packages') }}"><i class='bx bx-radio-circle'></i>Room Package</a></li>
                @endcan
                
                @can('room.addons.manage')
                <li><a href="{{ route('room-addons.index') }}"><i class='bx bx-radio-circle'></i>AddOns</a></li>
                @endcan
            </ul>
        </li>
        @endcan

        {{-- BOOKING MANAGEMENT - HANYA Receptionist, Admin, Super Admin --}}
        @can('booking.view')
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-calendar-check"></i></div>
                <div class="menu-title">Booking Management</div>
            </a>
            <ul>
                @can('booking.view')
                <li><a href="{{ route('admin.bookings') }}"><i class='bx bx-radio-circle'></i>All Bookings</a></li>
                @endcan
                
                @can('booking.export')
                <li><a href="{{ route('admin.bookings.export.options') }}"><i class='bx bx-radio-circle'></i>Export Report</a></li>
                @endcan
            </ul>
        </li>
        @endcan

        {{-- BEACH TICKET & POS - HANYA Cashier, Admin, Super Admin --}}
        @can('pos.access')
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i></div>
                <div class="menu-title">Beach Ticket & POS</div>
            </a>
            <ul>
                @can('ticket.dashboard')
                <li><a href="{{ route('backend.beach-tickets.dashboard') }}"><i class='bx bx-radio-circle'></i>Dashboard</a></li>
                @endcan
                
                @can('ticket.orders.view')
                <li><a href="{{ route('backend.beach-tickets.orders.index') }}"><i class='bx bx-radio-circle'></i>Orders</a></li>
                @endcan
                
                @can('pos.access')
                <li><a href="{{ route('backend.beach-tickets.pos.index') }}"><i class='bx bx-radio-circle'></i>Point of Sale (POS)</a></li>
                @endcan
                
                {{-- Manage Tickets & Promo - HANYA Admin & Super Admin --}}
                @can('ticket.create')
                <li><a href="{{ route('backend.beach-tickets.manage.index') }}"><i class='bx bx-radio-circle'></i>Manage Tickets</a></li>
                @endcan
                
                @can('ticket.create')
                <li><a href="{{ route('backend.beach-tickets.promo-codes.index') }}"><i class='bx bx-radio-circle'></i>Promo Codes</a></li>
                @endcan
            </ul>
        </li>
        @endcan

        {{-- ROLE & PERMISSION MANAGEMENT - HANYA Super Admin & Admin --}}
        @canany(['roles.view', 'permissions.view', 'roles.permissions.manage'])
        <li class="menu-label">System Management</li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-shield'></i></div>
                <div class="menu-title">Role & Permission</div>
            </a>
            <ul>
                @canany(['permissions.view', 'permissions.create', 'permissions.edit'])
                <li><a href="{{ route('all.permission') }}"><i class='bx bx-radio-circle'></i>Manage Permission</a></li>
                @endcanany
                
                @canany(['roles.view', 'roles.create', 'roles.edit'])
                <li><a href="{{ route('all.roles') }}"><i class='bx bx-radio-circle'></i>Manage Roles</a></li>
                @endcanany
                
                @can('roles.permissions.manage')
                <li><a href="{{ route('all.roles.permission') }}"><i class='bx bx-radio-circle'></i>Manage Role Permission</a></li>
                @endcan
            </ul>
        </li>
        @endcanany

        {{-- ADMIN MANAGEMENT - HANYA Super Admin --}}
        @role('Super Admin')
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-user'></i></div>
                <div class="menu-title">Admin Management</div>
            </a>
            <ul>
                <li><a href="{{ route('all.admin') }}"><i class='bx bx-radio-circle'></i>Manage Admin</a></li>
            </ul>
        </li>
        @endrole
    </ul>
</div>

{{-- User Info Section di Sidebar --}}
<div class="sidebar-footer">
    <div class="user-info p-3">
        <div class="d-flex align-items-center">
            <div class="avatar">
                @if(Auth::user()->photo)
                    <img src="{{ asset('upload/admin_images/'.Auth::user()->photo) }}" class="rounded-circle" width="40" height="40">
                @else
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                        <i class='bx bx-user text-white'></i>
                    </div>
                @endif
            </div>
            <div class="ms-2">
                <h6 class="mb-0 text-white">{{ Auth::user()->name }}</h6>
                <small class="text-light">@{{ Auth::user()->username }}</small><br>
                <span class="badge bg-info">{{ Auth::user()->getRoleNames()->first() }}</span>
            </div>
        </div>
    </div>
</div>
<!-- POS Navigation -->
<li class="nav-item {{ request()->routeIs('backend.pos.*') ? 'active' : '' }}">
    <a class="nav-link {{ request()->routeIs('backend.pos.*') ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePos" aria-expanded="{{ request()->routeIs('backend.pos.*') ? 'true' : 'false' }}" aria-controls="collapsePos">
        <i class="fas fa-cash-register"></i>
        <span>POS System</span>
    </a>
    <div id="collapsePos" class="collapse {{ request()->routeIs('backend.pos.*') ? 'show' : '' }}" aria-labelledby="headingPos">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Point of Sale:</h6>
            <a class="collapse-item {{ request()->routeIs('backend.pos.dashboard') ? 'active' : '' }}" href="{{ route('backend.pos.dashboard') }}">
                <i class="fas fa-tachometer-alt fa-fw"></i> Dashboard
            </a>
            <a class="collapse-item {{ request()->routeIs('backend.pos.index') ? 'active' : '' }}" href="{{ route('backend.pos.index') }}">
                <i class="fas fa-shopping-cart fa-fw"></i> POS Terminal
            </a>
            <a class="collapse-item {{ request()->routeIs('backend.beach-tickets.orders.index') ? 'active' : '' }}" href="{{ route('backend.beach-tickets.orders.index') }}">
                <i class="fas fa-list fa-fw"></i> Order History
            </a>
        </div>
    </div>
</li>
@extends('admin.admin_dashboard')

@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-content">
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">
            <i class="bx bx-ticket me-2"></i>Beach Ticket Orders
        </div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('backend.beach-tickets.dashboard') }}">Beach Tickets</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Orders</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('backend.beach-tickets.pos.index') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Create New Order
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card">
        <div class="card-body">
            <!-- Header Section -->
            <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bx bx-receipt" style="font-size: 2rem; color: var(--pink-primary);"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Order Management</h5>
                        <p class="mb-0 text-muted">Manage all beach ticket orders and receipts</p>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('backend.beach-tickets.orders.export', $filters) }}" 
                       class="btn btn-success btn-sm">
                        <i class="bx bx-export me-1"></i>Export Orders
                    </a>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#quickStatsModal">
                        <i class="bx bx-bar-chart me-1"></i>Quick Stats
                    </button>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section mb-4">
                <form action="{{ route('backend.beach-tickets.orders.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-2 col-md-3">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select form-select-sm" name="payment_method">
                                <option value="">All Methods</option>
                                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                <option value="xendit" {{ request('payment_method') == 'xendit' ? 'selected' : '' }}>Online</option>
                            </select>
                        </div>
                        
                        <div class="col-lg-2 col-md-3">
                            <label class="form-label">Order Type</label>
                            <select class="form-select form-select-sm" name="order_type">
                                <option value="">All Types</option>
                                <option value="offline" {{ request('order_type') == 'offline' ? 'selected' : '' }}>Offline (POS)</option>
                                <option value="online" {{ request('order_type') == 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                        </div>
                        
                        <div class="col-lg-2 col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date" class="form-control form-control-sm" name="date_from" 
                                   value="{{ request('date_from') }}">
                        </div>
                        
                        <div class="col-lg-2 col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date" class="form-control form-control-sm" name="date_to" 
                                   value="{{ request('date_to') }}">
                        </div>
                        
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Search</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Order ID, customer name..." 
                                       value="{{ request('search') }}">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="bx bx-search"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-lg-1 col-md-3">
                            <a href="{{ route('backend.beach-tickets.orders.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                                <i class="bx bx-reset"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Results Summary -->
            <div class="results-summary mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted">
                            Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} results
                        </span>
                    </div>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;" onchange="changePerPage(this.value)">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per page</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width: 130px;">
                                <div class="d-flex flex-column">
                                    <span>ORDER NUMBER</span>
                                </div>
                            </th>
                            <th style="width: 150px;">
                                <div class="d-flex flex-column">
                                    <span>CUSTOMER</span>
                                    <span>NAME</span>
                                </div>
                            </th>
                            <th style="width: 120px;">
                                <div class="d-flex flex-column">
                                    <span>VISIT DATE</span>
                                </div>
                            </th>
                            <th style="width: 120px;">
                                <div class="d-flex flex-column">
                                    <span>CASHIER</span>
                                </div>
                            </th>
                            <th style="width: 120px;">
                                <div class="d-flex flex-column">
                                    <span>PAYMENT</span>
                                    <span>METHOD</span>
                                </div>
                            </th>
                            <th style="width: 110px;">
                                <div class="d-flex flex-column">
                                    <span>ORDER TOTAL</span>
                                </div>
                            </th>
                            <th style="width: 120px;">
                                <div class="d-flex flex-column">
                                    <span>CREATED AT</span>
                                </div>
                            </th>
                            <th style="width: 100px;">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <div class="order-id-cell">
                                    <div class="order-id">#{{ $order->order_code }}</div>
                                    <div class="text-muted small">
                                        <i class="bx bx-receipt me-1"></i>{{ $order->quantity }} 
                                        {{ $order->quantity > 1 ? 'tickets' : 'ticket' }}
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <div class="customer-info">
                                    <div class="customer-name">{{ $order->customer_name }}</div>
                                </div>
                            </td>
                            
                            <td>
                                <div class="visit-date text-center">
                                    <div class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ \Carbon\Carbon::parse($order->visit_date)->format('l') }}
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <div class="cashier-info text-center">
                                    @if($order->cashier)
                                        <span class="badge bg-light text-dark">{{ $order->cashier->name }}</span>
                                    @else
                                        <span class="badge bg-secondary">System</span>
                                    @endif
                                </div>
                            </td>
                            
                            <td>
                                <div class="payment-method text-center">
                                    {!! $order->getPaymentMethodBadgeAttribute() !!}
                                </div>
                            </td>
                            
                            <td>
                                <div class="order-total text-end">
                                    <div class="amount fw-bold text-success">{{ $order->getFormattedTotalPriceAttribute() }}</div>
                                    @if($order->discount && $order->discount > 0)
                                        <div class="text-muted small">
                                            Disc: Rp{{ number_format($order->discount, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <td>
                                <div class="created-date text-center">
                                    <div>{{ $order->created_at->format('d M Y') }}</div>
                                    <div class="text-muted small">{{ $order->created_at->format('H:i') }}</div>
                                </div>
                            </td>
                            
                            <td>
                                <div class="d-flex order-actions justify-content-center">
                                    <a href="{{ route('backend.beach-tickets.orders.show', $order->order_code) }}" 
                                       class="btn btn-sm btn-outline-primary me-1" 
                                       title="View Details">
                                        <i class="bx bx-detail"></i>
                                    </a>
                                    
                                    <form action="{{ route('backend.beach-tickets.orders.destroy', $order->id) }}" 
                                          method="POST" 
                                          style="display: inline-block;" 
                                          onsubmit="return confirm('Are you sure you want to delete order #{{ $order->order_code }}? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Delete Order">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bx bx-receipt" style="font-size: 4rem; color: #ccc;"></i>
                                    <h5 class="mt-3 mb-2">No Orders Found</h5>
                                    <p class="text-muted">No orders match your current filters. Try adjusting your search criteria.</p>
                                    <a href="{{ route('backend.beach-tickets.orders.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bx bx-reset me-1"></i>Clear All Filters
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- FIXED PAGINATION SECTION -->
            @if($orders->hasPages())
            <div class="pagination-section mt-4 pt-4 border-top">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="pagination-info mb-2 mb-md-0">
                        <span class="text-muted">
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                        </span>
                    </div>
                    <div class="pagination-nav">
                        <!-- Custom Pagination Links -->
                        <nav aria-label="Order pagination">
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Previous Page Link --}}
                                @if ($orders->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="bx bx-chevron-left"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $orders->appends(request()->query())->previousPageUrl() }}">
                                            <i class="bx bx-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($orders->appends(request()->query())->getUrlRange(1, $orders->lastPage()) as $page => $url)
                                    @if ($page == $orders->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($orders->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $orders->appends(request()->query())->nextPageUrl() }}">
                                            <i class="bx bx-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="bx bx-chevron-right"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Stats Modal -->
<div class="modal fade" id="quickStatsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-bar-chart me-2"></i>Quick Order Statistics
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="stat-card text-center p-3">
                            <div class="stat-icon mb-2">
                                <i class="bx bx-receipt" style="font-size: 2rem; color: #28a745;"></i>
                            </div>
                            <h4 class="mb-1">{{ $orders->count() }}</h4>
                            <p class="text-muted mb-0">Total Orders</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-center p-3">
                            <div class="stat-icon mb-2">
                                <i class="bx bx-store" style="font-size: 2rem; color: #17a2b8;"></i>
                            </div>
                            <h4 class="mb-1">{{ $orders->where('is_offline_order', true)->count() }}</h4>
                            <p class="text-muted mb-0">POS Orders</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-center p-3">
                            <div class="stat-icon mb-2">
                                <i class="bx bx-globe" style="font-size: 2rem; color: #ffc107;"></i>
                            </div>
                            <h4 class="mb-1">{{ $orders->where('is_offline_order', false)->count() }}</h4>
                            <p class="text-muted mb-0">Online Orders</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-center p-3">
                            <div class="stat-icon mb-2">
                                <i class="bx bx-money" style="font-size: 2rem; color: #28a745;"></i>
                            </div>
                            <h4 class="mb-1">{{ $orders->sum('quantity') }}</h4>
                            <p class="text-muted mb-0">Total Tickets</p>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="stat-card p-3">
                            <h6 class="mb-3">
                                <i class="bx bx-money me-2 text-success"></i>Revenue Summary
                            </h6>
                            <div class="d-flex justify-content-between">
                                <span>Total Revenue:</span>
                                <strong class="text-success">
                                    Rp{{ number_format($orders->sum('total_price'), 0, ',', '.') }}
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Total Discount:</span>
                                <strong class="text-warning">
                                    Rp{{ number_format($orders->sum('discount'), 0, ',', '.') }}
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Average Order Value:</span>
                                <strong class="text-primary">
                                    Rp{{ number_format($orders->avg('total_price'), 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show position-fixed" 
     style="top: 100px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show position-fixed" 
     style="top: 100px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
    <i class="bx bx-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.click();
            }
        });
    }, 5000);
    
    // Change per page function
    window.changePerPage = function(value) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', 1); // Reset to first page
        window.location.href = url.toString();
    };
    
    // Enhanced search functionality
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
            }
        });
    }
    
    // Table row click to show details
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function(e) {
            // Don't trigger if clicking on buttons or links
            if (e.target.closest('button') || e.target.closest('a') || e.target.closest('form')) {
                return;
            }
            
            const viewLink = row.querySelector('a[href*="orders.show"]');
            if (viewLink) {
                window.location.href = viewLink.href;
            }
        });
        
        // Add pointer cursor to indicate clickable rows
        row.style.cursor = 'pointer';
    });
});
</script>

@endsection
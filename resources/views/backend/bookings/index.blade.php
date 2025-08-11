@extends('admin.admin_dashboard')
@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-content">
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">
            <i class="bx bx-calendar me-2"></i>Bookings
        </div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">All Bookings</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card">
        <div class="card-body">
            <!-- Header Section -->
            <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bx bx-calendar-check" style="font-size: 2rem; color: var(--pink-primary);"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Booking Management</h5>
                        <p class="mb-0 text-muted">Manage all hotel reservations and bookings</p>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.bookings.export.options', $filters) }}" 
                       class="btn btn-success btn-sm">
                        <i class="bx bx-export me-1"></i>Export Data
                    </a>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#quickStatsModal">
                        <i class="bx bx-bar-chart me-1"></i>Quick Stats
                    </button>
                </div>
            </div>
            
            <!-- Filter Section -->
            <div class="filter-section mb-4">
                <form action="{{ route('admin.bookings') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-3 col-md-4">
                            <label class="form-label">Hotel</label>
                            <select class="form-select form-select-sm" name="hotel_id">
                                <option value="">All Hotels</option>
                                @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}" {{ isset($filters['hotel_id']) && $filters['hotel_id'] == $hotel->id ? 'selected' : '' }}>
                                    {{ $hotel->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-lg-2 col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date" class="form-control form-control-sm" name="date_from" 
                                   value="{{ $filters['date_from'] ?? '' }}">
                        </div>
                        
                        <div class="col-lg-2 col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date" class="form-control form-control-sm" name="date_to" 
                                   value="{{ $filters['date_to'] ?? '' }}">
                        </div>
                        
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label">Search</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Booking ID, guest name, email..." 
                                       value="{{ $filters['search'] ?? '' }}">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="bx bx-search"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-lg-1 col-md-3">
                            <a href="{{ route('admin.bookings') }}" class="btn btn-outline-secondary btn-sm w-100">
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
                            Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} results
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
            
            <!-- Bookings Table -->
            <div class="booking-table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" data-table="bookings">
                        <thead>
                            <tr>
                                <th style="width: 130px;">
                                    <div class="d-flex flex-column">
                                        <span>BOOKING</span>
                                        <span>ID</span>
                                    </div>
                                </th>
                                <th style="width: 120px;">
                                    <div class="d-flex flex-column">
                                        <span>GUEST</span>
                                        <span>NAME</span>
                                    </div>
                                </th>
                                <th style="width: 180px;">
                                    <div class="d-flex flex-column">
                                        <span>CONTACT</span>
                                        <span>INFO</span>
                                    </div>
                                </th>
                                <th style="width: 160px;">
                                    <div class="d-flex flex-column">
                                        <span>HOTEL</span>
                                        <span>& ROOM</span>
                                    </div>
                                </th>
                                <th style="width: 130px;">
                                    <div class="d-flex flex-column">
                                        <span>CHECK-IN</span>
                                        <span>CHECK-OUT</span>
                                    </div>
                                </th>
                                <th style="width: 110px;">
                                    <div class="d-flex flex-column">
                                        <span>PAYMENT</span>
                                        <span>AMOUNT</span>
                                    </div>
                                </th>
                                
                                <th style="width: 100px;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <div class="booking-id-cell">
                                        <div class="booking-id">{{ $booking->code }}</div>
                                        <div class="text-muted small">
                                            <i class="bx bx-user me-1"></i>{{ $booking->adults }} 
                                            {{ $booking->adults > 1 ? 'adults' : 'adult' }}
                                            @if($booking->child > 0)
                                                <br><i class="bx bx-child me-1"></i>{{ $booking->child }} 
                                                {{ $booking->child > 1 ? 'children' : 'child' }}
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="guest-info">
                                        <div class="guest-name">{{ $booking->first_name }} {{ $booking->last_name }}</div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="contact-info">
                                        <div class="mb-1">
                                            <i class="bx bx-envelope me-1 text-primary"></i>
                                            <span style="font-size: 0.8rem;">{{ $booking->email }}</span>
                                        </div>
                                        @if($booking->phone)
                                        <div>
                                            <i class="bx bx-phone me-1 text-success"></i>
                                            <span style="font-size: 0.8rem;">{{ $booking->phone }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="hotel-info">
                                        <div class="hotel-name">{{ $booking->hotel->name ?? 'Unknown Hotel' }}</div>
                                        <div class="room-type">{{ $booking->roomType->name ?? 'Unknown Room' }}</div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="date-info text-center">
                                        <div class="check-in">
                                            <i class="bx bx-log-in me-1 text-success"></i>
                                            {{ Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}
                                        </div>
                                        <div class="check-out">
                                            <i class="bx bx-log-out me-1 text-danger"></i>
                                            {{ Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}
                                        </div>
                                        <div class="night-count">
                                            {{ $booking->total_night }} {{ $booking->total_night > 1 ? 'nights' : 'night' }}
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="payment-info text-end">
                                        <div class="amount">Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</div>
                                        <div class="mt-1">
                                            <span class="badge bg-success">Paid</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="d-flex order-actions justify-content-center gap-1">
                                        <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="View Details">
                                            <i class="bx bx-detail"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Delete Booking"
                                                onclick="confirmDelete({{ $booking->id }}, '{{ $booking->code }}')">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bx bx-calendar-x" style="font-size: 4rem; color: #ccc;"></i>
                                        <h5 class="mt-3 mb-2">No Bookings Found</h5>
                                        <p class="text-muted">No bookings match your current filters. Try adjusting your search criteria.</p>
                                        <a href="{{ route('admin.bookings') }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bx bx-reset me-1"></i>Clear All Filters
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- SINGLE PAGINATION SECTION - FIXED -->
            @if($bookings->hasPages())
            <div class="pagination-section mt-4 pt-4 border-top">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="pagination-info mb-2 mb-md-0">
                        <span class="text-muted">
                            Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} results
                        </span>
                    </div>
                    <div class="pagination-nav">
                        <!-- Custom Pagination Links -->
                        <nav aria-label="Booking pagination">
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Previous Page Link --}}
                                @if ($bookings->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="bx bx-chevron-left"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $bookings->appends($filters)->previousPageUrl() }}">
                                            <i class="bx bx-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($bookings->appends($filters)->getUrlRange(1, $bookings->lastPage()) as $page => $url)
                                    @if ($page == $bookings->currentPage())
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
                                @if ($bookings->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $bookings->appends($filters)->nextPageUrl() }}">
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-trash me-2 text-danger"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="bx bx-exclamation-triangle" style="font-size: 4rem; color: #dc3545;"></i>
                    <h5 class="mt-3 mb-2">Are you sure?</h5>
                    <p class="text-muted mb-3">
                        You are about to delete booking <strong id="booking-code"></strong>. 
                        This action cannot be undone.
                    </p>
                    <div class="alert alert-warning">
                        <i class="bx bx-info-circle me-2"></i>
                        Deleting this booking will permanently remove all associated data including payment records.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i>Cancel
                </button>
                <form id="delete-form" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bx bx-trash me-1"></i>Delete Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Modal (updated) -->
<div class="modal fade" id="quickStatsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-bar-chart me-2"></i>Quick Booking Statistics
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="stat-card text-center p-3">
                            <div class="stat-icon mb-2">
                                <i class="bx bx-calendar-check" style="font-size: 2rem; color: #28a745;"></i>
                            </div>
                            <h4 class="mb-1">{{ $bookings->count() }}</h4>
                            <p class="text-muted mb-0">Total Bookings</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card text-center p-3">
                            <div class="stat-icon mb-2">
                                <i class="bx bx-money" style="font-size: 2rem; color: #17a2b8;"></i>
                            </div>
                            <h4 class="mb-1">{{ $bookings->sum('adults') + $bookings->sum('child') }}</h4>
                            <p class="text-muted mb-0">Total Guests</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card text-center p-3">
                            <div class="stat-icon mb-2">
                                <i class="bx bx-bed" style="font-size: 2rem; color: #6f42c1;"></i>
                            </div>
                            <h4 class="mb-1">{{ $bookings->sum('total_night') }}</h4>
                            <p class="text-muted mb-0">Total Nights</p>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="stat-card p-3">
                            <h6 class="mb-3">
                                <i class="bx bx-money me-2 text-success"></i>Revenue Summary
                            </h6>
                            <div class="d-flex justify-content-between">
                                <span>Total Revenue:</span>
                                <strong class="text-success">
                                    Rp{{ number_format($bookings->sum('total_amount'), 0, ',', '.') }}
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Average per Booking:</span>
                                <strong class="text-info">
                                    Rp{{ number_format($bookings->count() > 0 ? $bookings->sum('total_amount') / $bookings->count() : 0, 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card p-3">
                            <h6 class="mb-3">
                                <i class="bx bx-hotel me-2 text-primary"></i>Hotel Performance
                            </h6>
                            @foreach($hotels->take(3) as $hotel)
                            <div class="d-flex justify-content-between">
                                <span>{{ $hotel->name }}:</span>
                                <strong>{{ $bookings->where('hotel_id', $hotel->id)->count() }} bookings</strong>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('admin.bookings.export.options', $filters) }}" class="btn btn-primary">
                    <i class="bx bx-download me-1"></i>Download Report
                </a>
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

<style>
/* Clean pagination styling */
.pagination-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
}

.pagination {
    margin: 0;
}

.pagination .page-link {
    color: var(--pink-primary);
    border: 1px solid #dee2e6;
    border-radius: 6px;
    margin: 0 2px;
    padding: 0.375rem 0.75rem;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: var(--pink-primary);
    border-color: var(--pink-primary);
    color: white;
    transform: translateY(-2px);
}

.pagination .page-item.active .page-link {
    background: var(--pink-gradient);
    border-color: var(--pink-primary);
    color: white;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
}

/* Filter section styling */
.filter-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
}

/* Results summary styling */
.results-summary {
    padding: 1rem;
    background: white;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

/* Table specific styles */
.booking-id-cell .booking-id {
    font-family: 'Courier New', monospace;
    font-weight: 700;
    color: var(--pink-dark);
    font-size: 0.9rem;
}

.guest-info .guest-name {
    font-weight: 600;
    color: #333;
}

.contact-info {
    font-size: 0.85rem;
}

.hotel-info .hotel-name {
    font-weight: 600;
    color: var(--pink-dark);
    font-size: 0.9rem;
}

.hotel-info .room-type {
    color: #6c757d;
    font-style: italic;
    font-size: 0.8rem;
}

.date-info .check-in,
.date-info .check-out {
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
}

.payment-info .amount {
    font-weight: 700;
    color: #28a745;
    font-size: 0.9rem;
}

.empty-state {
    padding: 2rem;
}

.stat-card {
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* Action buttons styling */
.order-actions .btn {
    transition: all 0.3s ease;
}

.order-actions .btn:hover {
    transform: translateY(-2px);
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

/* Delete modal styling */
.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    padding: 1.5rem;
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 1.5rem;
}

/* Responsive adjustments */
@media (max-width: 1400px) {
    .table {
        font-size: 0.8rem;
    }
    
    .table th,
    .table td {
        padding: 0.5rem 0.25rem;
    }
}

@media (max-width: 768px) {
    .filter-section .row > div {
        margin-bottom: 1rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .table-responsive {
        font-size: 0.7rem;
    }
    
    .pagination-section .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .pagination-nav {
        text-align: center;
    }
    
    .order-actions {
        flex-direction: column;
        gap: 0.25rem;
    }
}

/* Hide default Laravel pagination styling that might be interfering */
.pagination-wrapper {
    display: none !important;
}

/* Ensure only our custom pagination shows */
.page-content .pagination:not(.pagination-section .pagination) {
    display: none !important;
}
</style>

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
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Table row click to show details (avoid action buttons)
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function(e) {
            // Don't trigger if clicking on buttons or links
            if (e.target.closest('button') || e.target.closest('a')) {
                return;
            }
            
            const viewLink = row.querySelector('a[href*="bookings.show"]');
            if (viewLink) {
                window.location.href = viewLink.href;
            }
        });
        
        // Add pointer cursor to indicate clickable rows
        row.style.cursor = 'pointer';
    });
});

function confirmDelete(bookingId, bookingCode) {
    // Set booking code in modal
    document.getElementById('booking-code').textContent = bookingCode;
    
    // Set form action - KEMBALI KE ORIGINAL
    const deleteForm = document.getElementById('delete-form');
    deleteForm.action = `/admin/bookings/${bookingId}`;
    
    // Show modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// Handle delete form submission with loading state
document.getElementById('delete-form').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Deleting...';
    submitBtn.disabled = true;
    
    // Optional: Add timeout to prevent infinite loading
    setTimeout(function() {
        if (submitBtn.disabled) {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }, 10000); // 10 seconds timeout
});
</script>

@endsection
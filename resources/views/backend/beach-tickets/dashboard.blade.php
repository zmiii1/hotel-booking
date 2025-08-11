@extends('admin.admin_dashboard')
@section('admin')

<link href="{{ asset('backend/assets/css/index.css') }}" rel="stylesheet">

<div class="page-content">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card dashboard-header-card border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-2 text-white fw-bold">
                                <i class="bx bx-dashboard me-2"></i>Beach Ticket Dashboard
                            </h4>
                            <p class="mb-0 text-white-50">
                                Unified overview of all beach ticket sales (Website + POS) • 
                                <span class="badge bg-light text-pink fw-semibold px-3 py-2">{{ Auth::user()->getRoleNames()->first() }}</span>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="dashboard-actions">
                                <a href="{{ route('backend.beach-tickets.pos.index') }}" class="btn btn-light btn-sm me-2">
                                    <i class="bx bx-store me-1"></i>Open POS
                                </a>
                                <div class="text-white-50 mt-2">
                                    <i class="bx bx-calendar me-1"></i>
                                    {{ \Carbon\Carbon::now('Asia/Jakarta')->format('l, d F Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Key Metrics Row --}}
    <div class="row g-4 mb-4">
        {{-- Total Revenue --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card metric-card revenue-card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="metric-icon revenue-icon mb-3">
                                <i class="bx bx-money"></i>
                            </div>
                            <h6 class="text-white mb-2">Total Revenue</h6>
                            <div class="metric-value text-white">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</div>
                            <small class="text-white-50">All channels</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card metric-card orders-card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="metric-icon orders-icon mb-3">
                                <i class="bx bx-cart"></i>
                            </div>
                            <h6 class="text-white mb-2">Total Orders</h6>
                            <div class="metric-value text-white">{{ $totalOrders }}</div>
                            <small class="text-white-50">Website + POS</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Today's Sales --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card metric-card today-card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="metric-icon today-icon mb-3">
                                <i class="bx bx-calendar-check"></i>
                            </div>
                            <h6 class="text-white mb-2">Today's Sales</h6>
                            <div class="metric-value text-white">Rp{{ number_format($todayRevenue, 0, ',', '.') }}</div>
                            <small class="text-white-50">{{ $todayOrders }} orders</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Average Order Value --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card metric-card aov-card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="metric-icon aov-icon mb-3">
                                <i class="bx bx-trending-up"></i>
                            </div>
                            <h6 class="text-white mb-2">Avg. Order Value</h6>
                            <div class="metric-value text-white">Rp{{ number_format($averageOrderValue, 0, ',', '.') }}</div>
                            <small class="text-white-50">Per transaction</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Channel Breakdown Section --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card channel-card border-0 shadow-sm h-100">
                <div class="card-header channel-header">
                    <h6 class="mb-0 fw-semibold text-white d-flex align-items-center">
                        <i class="bx bx-globe me-2"></i>Website Sales
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-6">
                            <div class="channel-stat">
                                <div class="stat-value text-primary fw-bold">{{ $websiteOrders }}</div>
                                <div class="stat-label text-muted">Orders</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="channel-stat">
                                <div class="stat-value text-success fw-bold">Rp{{ number_format($websiteRevenue, 0, ',', '.') }}</div>
                                <div class="stat-label text-muted">Revenue</div>
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: {{ ($websiteRevenue / $totalRevenue) * 100 }}%"></div>
                    </div>
                    <small class="text-muted">{{ number_format(($websiteRevenue / $totalRevenue) * 100, 1) }}% of total revenue</small>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card channel-card border-0 shadow-sm h-100">
                <div class="card-header channel-header-pos">
                    <h6 class="mb-0 fw-semibold text-white d-flex align-items-center">
                        <i class="bx bx-store me-2"></i>POS Sales
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-6">
                            <div class="channel-stat">
                                <div class="stat-value text-warning fw-bold">{{ $posOrders }}</div>
                                <div class="stat-label text-muted">Orders</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="channel-stat">
                                <div class="stat-value text-success fw-bold">Rp{{ number_format($posRevenue, 0, ',', '.') }}</div>
                                <div class="stat-label text-muted">Revenue</div>
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: {{ ($posRevenue / $totalRevenue) * 100 }}%"></div>
                    </div>
                    <small class="text-muted">{{ number_format(($posRevenue / $totalRevenue) * 100, 1) }}% of total revenue</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Tables Section --}}
    <div class="row g-4">
        {{-- Recent Orders (All Channels) --}}
        <div class="col-lg-8">
            <div class="card data-card border-0 shadow-sm h-100">
                <div class="card-header data-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold text-white">
                            <i class="bx bx-time-five me-2"></i>Recent Orders (All Channels)
                        </h6>
                        <div class="header-actions">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-light btn-xs active" data-filter="all">All</button>
                                <button class="btn btn-outline-light btn-xs" data-filter="website">Website</button>
                                <button class="btn btn-outline-light btn-xs" data-filter="pos">POS</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3">Order Code</th>
                                    <th class="py-3">Customer</th>
                                    <th class="py-3">Channel</th>
                                    <th class="py-3">Amount</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr class="order-row" data-channel="{{ $order->cashier_id ? 'pos' : 'website' }}">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('backend.beach-tickets.orders.show', $order->order_code) }}" 
                                           class="text-pink fw-medium text-decoration-none">
                                            {{ $order->order_code }}
                                        </a>
                                    </td>
                                    <td class="py-3">
                                        {{ $order->customer_name }}
                                        @if($order->is_offline_order)
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        @if($order->cashier_id)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bx bx-store me-1"></i>POS
                                            </span>
                                        @else
                                            <span class="badge bg-primary">
                                                <i class="bx bx-globe me-1"></i>Website
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 fw-medium">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="py-3">
                                        @if($order->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($order->payment_status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-danger">{{ ucfirst($order->payment_status) }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-muted">{{ $order->created_at->format('H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bx bx-receipt" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mt-2 mb-0">No recent orders found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('backend.beach-tickets.orders.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-list-ul me-1"></i>View All Orders
                        </a>
                        <small class="text-muted">Showing last 10 orders</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Performance Summary --}}
        <div class="col-lg-4">
            <div class="card data-card border-0 shadow-sm h-100">
                <div class="card-header data-header">
                    <h6 class="mb-0 fw-semibold text-white">
                        <i class="bx bx-trending-up me-2"></i>Performance Summary
                    </h6>
                </div>
                <div class="card-body p-4">
                    {{-- Top Selling Tickets --}}
                    <div class="mb-4">
                        <h6 class="fw-semibold text-dark mb-3">Top Selling Tickets</h6>
                        @forelse($topTickets as $ticket)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-medium text-dark">{{ $ticket->ticket->name }}</div>
                                <small class="text-muted">{{ ucfirst($ticket->ticket->beach_name) }}</small>
                            </div>
                            <span class="badge bg-pink">{{ $ticket->total_sold }}</span>
                        </div>
                        @empty
                        <p class="text-muted small">No data available</p>
                        @endforelse
                    </div>

                    {{-- Quick Stats --}}
                    <div class="border-top pt-4">
                        <h6 class="fw-semibold text-dark mb-3">Quick Stats</h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="quick-stat">
                                    <div class="stat-number text-primary">{{ $weeklyOrders }}</div>
                                    <div class="stat-label">Weekly Orders</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="quick-stat">
                                    <div class="stat-number text-success">{{ $monthlyOrders }}</div>
                                    <div class="stat-label">Monthly Orders</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard Styling */
:root {
    --pink-primary: #e91e63;
    --pink-dark: #c2185b;
    --pink-light: #f8bbd9;
    --pink-gradient: linear-gradient(135deg, #e91e63 0%, #f06292 100%);
}

.dashboard-header-card {
    background: var(--pink-gradient);
    border-radius: 15px;
}

.dashboard-actions .btn {
    border-radius: 8px;
    font-weight: 500;
}

/* Metric Cards */
.metric-card {
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.metric-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.revenue-card { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
.orders-card { background: linear-gradient(135deg, #007bff 0%, #6f42c1 100%); }
.today-card { background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%); }
.aov-card { background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); }

.metric-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.2);
}

.metric-icon i {
    font-size: 1.5rem;
    color: white;
}

.metric-value {
    font-size: 1.8rem;
    font-weight: 700;
    line-height: 1.2;
}

/* Channel Cards */
.channel-card {
    border-radius: 12px;
    overflow: hidden;
}

.channel-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    padding: 1rem 1.5rem;
    border: none;
}

.channel-header-pos {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    padding: 1rem 1.5rem;
    border: none;
}

.channel-stat {
    text-align: center;
    padding: 1rem 0;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1.2;
}

.stat-label {
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Data Cards */
.data-card {
    border-radius: 12px;
    overflow: hidden;
}

.data-header {
    background: var(--pink-gradient);
    padding: 1rem 1.5rem;
    border: none;
}

.btn-xs {
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 6px;
}

.table th {
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
}

.order-row {
    transition: background-color 0.2s ease;
}

.order-row:hover {
    background-color: rgba(233, 30, 99, 0.05);
}

/* Quick Stats */
.quick-stat {
    padding: 1rem;
    border-radius: 8px;
    background: rgba(233, 30, 99, 0.05);
    margin-bottom: 0.5rem;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
}

.stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

/* Responsive */
@media (max-width: 768px) {
    .metric-value {
        font-size: 1.5rem;
    }
    
    .dashboard-actions {
        margin-top: 1rem;
        text-align: center;
    }
    
    .header-actions {
        margin-top: 0.5rem;
    }
}

/* Badge Styles */
.bg-pink {
    background-color: var(--pink-primary) !important;
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.6s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Channel filter functionality
    const filterButtons = document.querySelectorAll('[data-filter]');
    const orderRows = document.querySelectorAll('.order-row');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter rows
            orderRows.forEach(row => {
                const channel = row.getAttribute('data-channel');
                if (filter === 'all' || channel === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
});
</script>

@endsection
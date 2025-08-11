@extends('admin.admin_dashboard')

@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Beach Ticket</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('backend.beach-tickets.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.beach-tickets.orders.index') }}">Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->order_code }}</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('backend.beach-tickets.orders.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to Orders
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0">Order #{{ $order->order_code }}</h5>
                    <span class="ms-auto">{!! $order->getStatusBadgeAttribute() !!}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="mb-3">Order Details</h6>
                        <p class="mb-1"><strong>Order Date:</strong> {{ $order->created_at->format('d F Y H:i') }}</p>
                        <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                        <p class="mb-1"><strong>Visit Date:</strong> {{ $order->getFormattedVisitDateAttribute() }}</p>
                        @if($order->promoCode)
                        <p class="mb-1"><strong>Promo Code:</strong> {{ $order->promoCode->code }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">Customer Information</h6>
                        <p class="mb-1"><strong>Name:</strong> {{ $order->customer_name }}</p>
                        @if($order->customer_email)
                        <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                        @endif
                        @if($order->customer_phone)
                        <p class="mb-1"><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                        @endif
                    </div>
                </div>
                
                <h6 class="mb-3">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Beach</th>
                                <th>Type</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $order->ticket->name }}</td>
                                <td>{{ ucfirst($order->ticket->beach_name) }}</td>
                                <td>{{ ucfirst($order->ticket->ticket_type) }}</td>
                                <td class="text-center">{{ $order->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($order->ticket->price, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($order->ticket->price * $order->quantity, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            @if($order->subtotal && $order->subtotal != $order->total_price)
                            <tr>
                                <td colspan="5" class="text-end"><strong>Subtotal:</strong></td>
                                <td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            
                            @if($order->discount && $order->discount > 0)
                            <tr>
                                <td colspan="5" class="text-end"><strong>Discount:</strong></td>
                                <td class="text-end">Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            
                            <tr>
                                <td colspan="5" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end"><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                            </tr>
                            
                            @if($order->payment_method == 'cash' && $order->amount_tendered)
                            <tr>
                                <td colspan="5" class="text-end"><strong>Amount Tendered:</strong></td>
                                <td class="text-end">Rp {{ number_format($order->amount_tendered, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end"><strong>Change:</strong></td>
                                <td class="text-end">Rp {{ number_format($order->amount_tendered - $order->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
                
                @if($order->additional_request)
                <div class="mt-4">
                    <h6 class="mb-2">Additional Request:</h6>
                    <div class="p-3 bg-light rounded">
                        {{ $order->additional_request }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Ticket Benefits</h5>
            </div>
            <div class="card-body">
                @if($order->ticket && $order->ticket->benefits && $order->ticket->benefits->count() > 0)
                    <ul class="list-group">
                        @foreach($order->ticket->benefits as $benefit)
                        <li class="list-group-item d-flex align-items-center">
                            <i class="bx bx-check-circle text-success me-2"></i>
                            {{ $benefit->benefit_name }}
                        </li>
                        @endforeach
                    </ul>
                @else
                    <!-- Debug: Show what we have -->
                    <div class="alert alert-warning">
                        <p><strong>Debug Info:</strong></p>
                        <p>Ticket ID: {{ $order->ticket->id ?? 'N/A' }}</p>
                        <p>Benefits Count: {{ $order->ticket->benefits->count() ?? 'N/A' }}</p>
                        <p>Benefits: {{ $order->ticket->benefits ?? 'NULL' }}</p>
                    </div>
                    
                    @if($order->ticket && $order->ticket->description)
                        <div class="alert alert-info">
                            <h6>Ticket Description:</h6>
                            <p class="mb-0">{{ $order->ticket->description }}</p>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h6>Standard Beach Access Includes:</h6>
                            <ul class="mb-0">
                                <li><i class="bx bx-check-circle text-success me-2"></i>Beach entrance</li>
                                <li><i class="bx bx-check-circle text-success me-2"></i>Access to common areas</li>
                                <li><i class="bx bx-check-circle text-success me-2"></i>Sunbathing space</li>
                                <li><i class="bx bx-check-circle text-success me-2"></i>Swimming in designated areas</li>
                            </ul>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('backend.beach-tickets.orders.print-receipt', $order->order_code) }}" class="btn btn-primary" target="_blank">
                        <i class="bx bx-printer"></i> Print Receipt
                    </a>
                    
                    @if($order->payment_status == 'pending')
                    <form action="{{ route('backend.beach-tickets.orders.mark-as-paid', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bx bx-check"></i> Mark as Paid
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Order Timeline</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bx bx-calendar me-2 text-primary"></i>
                            <span>Order Created</span>
                        </div>
                        <span class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</span>
                    </li>
                    
                    @if($order->paid_at)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bx bx-credit-card me-2 text-success"></i>
                            <span>Payment Received</span>
                        </div>
                        <span class="text-muted">{{ $order->paid_at->format('d M Y H:i') }}</span>
                    </li>
                    @endif
                    
                    @if($order->payment && $order->payment->confirmed_at)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bx bx-check-circle me-2 text-success"></i>
                            <span>Payment Confirmed</span>
                        </div>
                        <span class="text-muted">{{ $order->payment->confirmed_at->format('d M Y H:i') }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Order Information</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Order Type</span>
                        <span>{{ $order->is_offline_order ? 'Offline' : 'Online' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Payment Status</span>
                        <span>{!! $order->getStatusBadgeAttribute() !!}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Payment Method</span>
                        <span>{!! $order->getPaymentMethodBadgeAttribute() !!}</span>
                    </li>
                    @if($order->cashier)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Cashier</span>
                        <span>{{ $order->cashier->name }}</span>
                    </li>
                    @endif
                    @if($order->transaction_id)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Transaction ID</span>
                        <span>{{ $order->transaction_id }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
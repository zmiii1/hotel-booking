@extends('admin.admin_dashboard')

@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Beach Ticket Promo Codes</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('backend.beach-tickets.promo-codes.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add New Promo Code
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h5>All Beach Ticket Promo Codes</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Valid Period</th>
                        <th>Usage</th>
                        <th>Applies To</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promoCodes as $promoCode)
                    <tr>
                        <td><strong>{{ $promoCode->code }}</strong></td>
                        <td>{{ ucfirst($promoCode->discount_type) }}</td>
                        <td>
                            @if($promoCode->discount_type == 'percentage')
                                {{ $promoCode->discount_value }}%
                                @if($promoCode->max_discount)
                                    <small class="text-muted d-block">Max: Rp {{ number_format($promoCode->max_discount, 0, ',', '.') }}</small>
                                @endif
                            @else
                                Rp {{ number_format($promoCode->discount_value, 0, ',', '.') }}
                            @endif
                            
                            @if($promoCode->min_purchase)
                                <small class="text-muted d-block">Min purchase: Rp {{ number_format($promoCode->min_purchase, 0, ',', '.') }}</small>
                            @endif
                        </td>
                        <td>
                            @if(is_string($promoCode->start_date))
                                {{ \Carbon\Carbon::parse($promoCode->start_date)->format('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($promoCode->end_date)->format('d M Y') }}
                            @else
                                {{ $promoCode->start_date->format('d M Y') }} - 
                                {{ $promoCode->end_date->format('d M Y') }}
                            @endif
                            
                            @if(is_string($promoCode->end_date) 
                                ? \Carbon\Carbon::parse($promoCode->end_date)->isPast() 
                                : $promoCode->end_date->isPast())
                                <span class="badge badge-danger">Expired</span>
                            @elseif(is_string($promoCode->start_date) 
                                ? \Carbon\Carbon::parse($promoCode->start_date)->isFuture() 
                                : $promoCode->start_date->isFuture())
                                <span class="badge badge-info">Upcoming</span>
                            @else
                                <span class="badge badge-success">Active</span>
                            @endif
                        </td>
                        <td>
                            {{ $promoCode->used_count }} 
                            @if($promoCode->max_uses > 0)
                                / {{ $promoCode->max_uses }}
                                @if($promoCode->used_count >= $promoCode->max_uses)
                                    <span class="badge badge-danger">Depleted</span>
                                @endif
                            @else
                                <small class="text-muted">(Unlimited)</small>
                            @endif
                        </td>                                      
                        <td>
                            @if($promoCode->beachTickets->count() > 0)
                                <div class="mt-1">
                                    <small class="text-muted">Specific tickets:</small>
                                    @foreach($promoCode->beachTickets as $ticket)
                                        <span class="badge bg-secondary me-1">{{ $ticket->name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="badge bg-info">All Beach Tickets</span>
                            @endif
                        </td>
                        <td>
                            @if($promoCode->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('backend.beach-tickets.promo-codes.edit', $promoCode->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('backend.beach-tickets.promo-codes.destroy', $promoCode->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this promo code?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No promo codes found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@extends('admin.admin_dashboard')
@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Bookings</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.bookings') }}">All Bookings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Booking Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">Booking #{{ $booking->code }}</h5>
                        <div class="ms-auto">
                            @if($booking->status == 'confirmed')
                            <div class="badge rounded-pill bg-success p-2 px-3">Confirmed</div>
                            @elseif($booking->status == 'pending')
                            <div class="badge rounded-pill bg-warning text-dark p-2 px-3">Pending</div>
                            @elseif($booking->status == 'cancelled')
                            <div class="badge rounded-pill bg-danger p-2 px-3">Cancelled</div>
                            @elseif($booking->status == 'checked_in')
                            <div class="badge rounded-pill bg-info p-2 px-3">Checked In</div>
                            @elseif($booking->status == 'checked_out')
                            <div class="badge rounded-pill bg-secondary p-2 px-3">Checked Out</div>
                            @else
                            <div class="badge rounded-pill bg-dark p-2 px-3">{{ ucfirst($booking->status) }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-2">Guest Information</h6>
                            <div class="border rounded p-3">
                                <p class="mb-1"><strong>Name:</strong> {{ $booking->first_name }} {{ $booking->last_name }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $booking->email }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $booking->phone }}</p>
                                <p class="mb-0"><strong>Country:</strong> {{ $booking->country ?: 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-2">Booking Information</h6>
                            <div class="border rounded p-3">
                                <p class="mb-1"><strong>Created:</strong> {{ $booking->created_at->format('d M Y H:i') }}</p>
                                <p class="mb-1">
                                    <strong>Payment:</strong> 
                                    <span class="badge {{ $booking->payment_status == 'paid' ? 'bg-success' : ($booking->payment_status == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </p>
                                <p class="mb-1"><strong>Method:</strong> {{ ucfirst($booking->payment_method) }}</p>
                                <p class="mb-0"><strong>Transaction ID:</strong> {{ $booking->transaction_id ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-2">Hotel & Room Details</h6>
                            <div class="border rounded p-3">
                                <p class="mb-1"><strong>Hotel:</strong> {{ $booking->hotel->name ?? 'Unknown' }}</p>
                                <p class="mb-1"><strong>Room Type:</strong> {{ $booking->room->name ?? ($booking->roomType->name ?? 'Unknown') }}</p>
                                <p class="mb-1"><strong>Package:</strong> {{ $booking->package->name ?? 'None' }}</p>
                                <p class="mb-0">
                                    <strong>Room Number(s):</strong> 
                                    @php
                                        $roomNumbers = [];
                                        
                                        // Try single room number first
                                        if($booking->room_number) {
                                            $roomNumbers[] = $booking->room_number->room_numbers ?? $booking->room_number->number ?? 'N/A';
                                        }
                                        
                                        // Try multiple room numbers
                                        if($booking->room_numbers && $booking->room_numbers->count() > 0) {
                                            foreach($booking->room_numbers as $roomNumber) {
                                                $number = $roomNumber->room_numbers ?? $roomNumber->number ?? 'N/A';
                                                if(!in_array($number, $roomNumbers)) {
                                                    $roomNumbers[] = $number;
                                                }
                                            }
                                        }
                                        
                                        // Try through room lists/assign_rooms
                                        if(empty($roomNumbers) && $booking->room_lists && $booking->room_lists->count() > 0) {
                                            foreach($booking->room_lists as $roomList) {
                                                if($roomList->roomNumber) {
                                                    $number = $roomList->roomNumber->room_numbers ?? $roomList->roomNumber->number ?? 'N/A';
                                                    if(!in_array($number, $roomNumbers)) {
                                                        $roomNumbers[] = $number;
                                                    }
                                                }
                                            }
                                        }
                                        
                                        // Try assign_rooms as fallback
                                        if(empty($roomNumbers) && $booking->assign_rooms && $booking->assign_rooms->count() > 0) {
                                            foreach($booking->assign_rooms as $assignedRoom) {
                                                if($assignedRoom->roomNumber) {
                                                    $number = $assignedRoom->roomNumber->room_numbers ?? $assignedRoom->roomNumber->number ?? 'N/A';
                                                    if(!in_array($number, $roomNumbers)) {
                                                        $roomNumbers[] = $number;
                                                    }
                                                }
                                            }
                                        }
                                    @endphp
                                    
                                    @if(!empty($roomNumbers))
                                        {{ implode(', ', $roomNumbers) }}
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-2">Stay Information</h6>
                            <div class="border rounded p-3">
                                <p class="mb-1"><strong>Check-in:</strong> {{ Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}</p>
                                <p class="mb-1"><strong>Check-out:</strong> {{ Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}</p>
                                <p class="mb-1"><strong>Nights:</strong> {{ $booking->total_night }}</p>
                                <p class="mb-0"><strong>Guests:</strong> {{ $booking->adults }} {{ $booking->adults > 1 ? 'adults' : 'adult' }}{{ $booking->child > 0 ? ', ' . $booking->child . ' ' . ($booking->child > 1 ? 'children' : 'child') : '' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($booking->additional_request)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="mb-2">Additional Request</h6>
                            <div class="border rounded p-3">
                                <p class="mb-0">{{ $booking->additional_request }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-2">Services & Add-ons</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Service</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Package Included Addons -->
                                        @if($booking->package && $booking->package->addons && $booking->package->addons->count() > 0)
                                            @foreach($booking->package->addons as $addon)
                                                <tr>
                                                    <td>{{ $addon->name }} <span class="badge bg-info">Included in Package</span></td>
                                                    <td>1</td>
                                                    <td>Rp0</td>
                                                    <td>Rp0</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        
                                        <!-- Additional Addons -->
                                        @if($booking->addons && $booking->addons->count() > 0)
                                            @foreach($booking->addons as $addon)
                                                <tr>
                                                    <td>{{ $addon->name }}</td>
                                                    <td>{{ $addon->pivot->quantity }}</td>
                                                    <td>Rp{{ number_format($addon->pivot->price, 0, ',', '.') }}</td>
                                                    <td>Rp{{ number_format($addon->pivot->total_price, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        
                                        @if((!$booking->package || !$booking->package->addons || $booking->package->addons->count() == 0) && (!$booking->addons || $booking->addons->count() == 0))
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No additional services</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Booking Summary</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Room Rate ({{ $booking->total_night }} nights)</td>
                                    <td class="text-end">Rp{{ number_format($booking->actual_price ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Package</td>
                                    <td class="text-end">Rp{{ number_format($booking->package_price ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-end">Rp{{ number_format($booking->subtotal ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                @if($booking->discount > 0)
                                <tr>
                                    <td>Discount</td>
                                    <td class="text-end text-success">-Rp{{ number_format($booking->discount, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td class="text-end"><strong>Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.bookings') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
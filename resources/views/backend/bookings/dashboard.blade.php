@extends('admin.admin_dashboard')
@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-content">
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10 bg-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-white">Total Bookings</p>
                            <h4 class="my-1 text-white">{{ $totalBookings }}</h4>
                        </div>
                        <div class="text-white ms-auto font-35"><i class="bx bx-calendar-check"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 bg-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-white">Confirmed Bookings</p>
                            <h4 class="my-1 text-white">{{ $confirmedBookings }}</h4>
                        </div>
                        <div class="text-white ms-auto font-35"><i class="bx bx-check-circle"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 bg-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-dark">Pending Bookings</p>
                            <h4 class="my-1 text-dark">{{ $pendingBookings }}</h4>
                        </div>
                        <div class="text-dark ms-auto font-35"><i class="bx bx-time"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 bg-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-white">Total Revenue</p>
                            <h4 class="my-1 text-white">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                        </div>
                        <div class="text-white ms-auto font-35"><i class="bx bx-money"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Bookings & Revenue Overview</h6>
                        </div>
                        <div class="dropdown ms-auto">
                            <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-horizontal-rounded font-22 text-option"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.bookings.export') }}">Export Data</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex align-items-center ms-auto font-13 gap-2 my-3">
                        <span class="border px-1 rounded cursor-pointer" style="background: #7b00ff;"><i class="bx bxs-circle me-1" style="color: #fff;"></i>Bookings</span>
                        <span class="border px-1 rounded cursor-pointer" style="background: #ffc107;"><i class="bx bxs-circle me-1" style="color: #fff;"></i>Revenue</span>
                    </div>
                    <div class="chart-container-1">
                        <canvas id="bookingRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Bookings by Hotel</h6>
                        </div>
                        <div class="dropdown ms-auto">
                            <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-horizontal-rounded font-22 text-option"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.bookings') }}">View All</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="chart-container-2 mt-4">
                        <canvas id="hotelBookingsChart"></canvas>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach($bookingsByHotel as $hotel)
                    <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                        {{ $hotel['hotel_name'] }}
                        <span class="badge bg-primary rounded-pill">{{ $hotel['total'] }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="card radius-10">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-0">Recent Bookings</h6>
                </div>
                <div class="dropdown ms-auto">
                    <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-horizontal-rounded font-22 text-option"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.bookings') }}">View All</a></li>
                    </ul>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest Name</th>
                            <th>Hotel</th>
                            <th>Room</th>
                            <th>Check-in</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings as $booking)
                        <tr>
                            <td>{{ $booking->code }}</td>
                            <td>{{ $booking->first_name }} {{ $booking->last_name }}</td>
                            <td>{{ $booking->hotel->name ?? 'Unknown' }}</td>
                            <td>{{ $booking->room->name ?? 'Unknown' }}</td>
                            <td>{{ Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}</td>
                            <td>
                                @if($booking->status == 'confirmed')
                                <div class="badge rounded-pill bg-success">Confirmed</div>
                                @elseif($booking->status == 'pending')
                                <div class="badge rounded-pill bg-warning text-dark">Pending</div>
                                @elseif($booking->status == 'cancelled')
                                <div class="badge rounded-pill bg-danger">Cancelled</div>
                                @elseif($booking->status == 'checked_in')
                                <div class="badge rounded-pill bg-info">Checked In</div>
                                @elseif($booking->status == 'checked_out')
                                <div class="badge rounded-pill bg-secondary">Checked Out</div>
                                @else
                                <div class="badge rounded-pill bg-dark">{{ ucfirst($booking->status) }}</div>
                                @endif
                            </td>
                            <td>Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-primary btn-sm">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('backend/assets/plugins/chartjs/js/Chart.min.js') }}"></script>
<script>
    // Bookings and Revenue Chart
    var ctx1 = document.getElementById('bookingRevenueChart').getContext('2d');
    var bookingRevenueChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Bookings',
                data: {!! json_encode($chartBookingData) !!},
                backgroundColor: '#7b00ff',
                borderColor: '#7b00ff',
                borderWidth: 1,
                yAxisID: 'y-axis-1',
            }, {
                label: 'Revenue (in millions)',
                data: {!! json_encode(array_map(function($val) { return $val / 1000000; }, $chartRevenueData)) !!},
                backgroundColor: '#ffc107',
                borderColor: '#ffc107',
                borderWidth: 1,
                type: 'line',
                yAxisID: 'y-axis-2',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    position: 'left',
                    id: 'y-axis-1',
                    ticks: {
                        beginAtZero: true
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Number of Bookings'
                    }
                }, {
                    position: 'right',
                    id: 'y-axis-2',
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return value + 'M';
                       }
                   },
                   scaleLabel: {
                       display: true,
                       labelString: 'Revenue (IDR)'
                   },
                   gridLines: {
                       drawOnChartArea: false
                   }
               }]
           }
       }
   });

   // Hotel Bookings Chart
   var ctx2 = document.getElementById('hotelBookingsChart').getContext('2d');
   var hotelData = {!! json_encode($bookingsByHotel) !!};
   var hotelNames = hotelData.map(function(hotel) { return hotel.hotel_name; });
   var hotelCounts = hotelData.map(function(hotel) { return hotel.total; });
   var hotelColors = [
       '#3f51b5', '#009688', '#ff5722', '#607d8b', 
       '#ff9800', '#9c27b0', '#2196f3', '#4caf50',
       '#673ab7', '#f44336', '#cddc39', '#795548'
   ];

   var hotelBookingsChart = new Chart(ctx2, {
       type: 'doughnut',
       data: {
           labels: hotelNames,
           datasets: [{
               data: hotelCounts,
               backgroundColor: hotelColors.slice(0, hotelNames.length),
               borderWidth: 1
           }]
       },
       options: {
           responsive: true,
           maintainAspectRatio: false,
           legend: {
               position: 'bottom',
               display: false
           },
           cutoutPercentage: 70
       }
   });
</script>
@endpush

@endsection
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmed Bookings Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e91e63;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #e91e63;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .business-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 11px;
            color: #856404;
        }
        .info-section {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .filters-section {
            margin-bottom: 20px;
            padding: 10px;
            background: #e8f5e8;
            border: 1px solid #d4edda;
            border-radius: 3px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #e91e63;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .amount {
            text-align: right;
            font-weight: bold;
            color: #28a745;
        }
        .status-confirmed {
            background: #d1e7dd;
            color: #0f5132;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Confirmed Bookings Report</h1>
        <p>Generated on {{ $export_date }}</p>
        @if(isset($view_type) && $view_type !== 'all')
            <p style="font-style: italic;">View: {{ ucfirst(str_replace('_', ' ', $view_type)) }}</p>
        @endif
        @if(isset($date_filter_type))
            <p style="font-style: italic;">Date Filter: {{ ucfirst(str_replace('_', ' ', $date_filter_type)) }}</p>
        @endif
    </div>
    
    <div class="business-note">
        <strong>Business Policy:</strong> All bookings shown are confirmed (payment completed). 
        No cancellations or refunds are processed.
    </div>
    
    <div class="info-section">
        <strong>Summary:</strong> 
        {{ $total_bookings }} Confirmed Bookings | 
        Total Revenue: Rp{{ number_format($total_revenue, 0, ',', '.') }} 
    </div>
    
    @if(!empty(array_filter($filters)))
    <div class="filters-section">
        <strong>Applied Filters:</strong>
        @if(!empty($filters['hotel_id']))
            Hotel: {{ App\Models\Hotel::find($filters['hotel_id'])->name ?? 'Unknown' }} |
        @endif
        @if(!empty($filters['view_type']))
            View: {{ ucfirst(str_replace('_', ' ', $filters['view_type'])) }} |
        @endif
        @if(!empty($filters['date_from']))
            From: {{ Carbon\Carbon::parse($filters['date_from'])->format('d M Y') }} |
        @endif
        @if(!empty($filters['date_to']))
            To: {{ Carbon\Carbon::parse($filters['date_to'])->format('d M Y') }} |
        @endif
        @if(!empty($filters['search']))
            Search: "{{ $filters['search'] }}"
        @endif
    </div>
    @endif
    
    <table>
        <thead>
            <tr>
                <th>Booking Code</th>
                <th>Guest Name</th>
                <th>Hotel</th>
                <th>Room & Package</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Nights</th>
                <th>Guests</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr>
                <td style="font-family: monospace; font-weight: bold;">{{ $booking->code }}</td>
                <td>{{ $booking->first_name }} {{ $booking->last_name }}</td>
                <td>{{ $booking->hotel->name ?? 'Unknown Hotel' }}</td>
                <td>
                    {{ $booking->roomType->name ?? 'Unknown Room' }}
                    @if($booking->package)
                        <br><small style="color: #666;">{{ $booking->package->name }}</small>
                    @endif
                </td>
                <td>{{ Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}</td>
                <td>{{ Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}</td>
                <td style="text-align: center;">{{ $booking->total_night }}</td>
                <td style="text-align: center;">
                    {{ $booking->adults + $booking->child }}
                    <br><small>({{ $booking->adults }}A{{ $booking->child > 0 ? ', '.$booking->child.'C' : '' }})</small>
                </td>
                <td class="amount">Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 30px; color: #666;">
                    No confirmed bookings found with current filters.
                </td>
            </tr>
            @endforelse
        </tbody>
        
        @if($bookings->count() > 0)
        <tfoot>
            <tr style="background: #e8f5e8; font-weight: bold;">
                <td colspan="8" style="text-align: right; padding: 10px;">
                    <strong>Total Revenue:</strong>
                </td>
                <td class="amount" style="font-size: 12px; background: #d1e7dd;">
                    Rp{{ number_format($total_revenue, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>
    
    @if($bookings->count() > 0)
    <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
        <h4 style="margin: 0 0 10px 0; color: #e91e63;">Report Statistics</h4>
        <div style="display: flex; justify-content: space-between;">
            <div>
                <strong>Total Bookings:</strong> {{ $total_bookings }}<br>
                <strong>Average Revenue per Booking:</strong> Rp{{ number_format($total_revenue / max($total_bookings, 1), 0, ',', '.') }}<br>
                <strong>Total Nights Booked:</strong> {{ $bookings->sum('total_night') }}
            </div>
            <div>
                <strong>Total Guests:</strong> {{ $bookings->sum('adults') + $bookings->sum('child') }}<br>
                <strong>Adults:</strong> {{ $bookings->sum('adults') }} | <strong>Children:</strong> {{ $bookings->sum('child') }}<br>
                <strong>Average Guests per Booking:</strong> {{ round(($bookings->sum('adults') + $bookings->sum('child')) / max($total_bookings, 1), 1) }}
            </div>
        </div>
    </div>
    @endif
    
    <div class="footer">
        <p><strong>Hotel Management System</strong> | Confirmed Bookings Only | No Cancellation Policy</p>
        <p>Report generated on {{ $export_date }} | All amounts in Indonesian Rupiah (IDR)</p>
        @if(isset($business_note))
            <p style="font-style: italic;">{{ $business_note }}</p>
        @endif
    </div>
</body>
</html>
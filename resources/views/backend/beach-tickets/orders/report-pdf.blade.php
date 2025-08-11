<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Beach Ticket Orders Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .info-section {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
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
        }
        .payment-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .payment-cash {
            background-color: #28a745;
            color: white;
        }
        .payment-card {
            background-color: #007bff;
            color: white;
        }
        .payment-online {
            background-color: #ffc107;
            color: #212529;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Beach Ticket Orders Report</h1>
        <p>Generated on {{ $export_date }}</p>
    </div>
    
    <div class="info-section">
        <strong>Summary:</strong> 
        {{ $total_orders }} Total Orders | 
        Revenue: Rp{{ number_format($total_revenue, 0, ',', '.') }} | 
        Tickets Sold: {{ $total_tickets }}
        @if($total_discount > 0)
        | Total Discount: Rp{{ number_format($total_discount, 0, ',', '.') }}
        @endif
    </div>
    
    @if(!empty(array_filter($filters)))
    <div style="margin-bottom: 20px; padding: 10px; background: #fff; border: 1px solid #ddd;">
        <strong>Applied Filters:</strong>
        @if(!empty($filters['payment_method']))
            Payment Method: {{ ucfirst($filters['payment_method']) }} |
        @endif
        @if(!empty($filters['order_type']))
            Order Type: {{ ucfirst($filters['order_type']) }} |
        @endif
        @if(!empty($filters['date_from']))
            Visit Date From: {{ Carbon\Carbon::parse($filters['date_from'])->format('d M Y') }} |
        @endif
        @if(!empty($filters['date_to']))
            Visit Date To: {{ Carbon\Carbon::parse($filters['date_to'])->format('d M Y') }} |
        @endif
        @if(!empty($filters['created_from']))
            Created From: {{ Carbon\Carbon::parse($filters['created_from'])->format('d M Y') }} |
        @endif
        @if(!empty($filters['created_to']))
            Created To: {{ Carbon\Carbon::parse($filters['created_to'])->format('d M Y') }}
        @endif
    </div>
    @endif
    
    <table>
        <thead>
            <tr>
                <th>Order Code</th>
                <th>Customer Name</th>
                <th>Visit Date</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Discount</th>
                <th>Final Amount</th>
                <th>Payment Method</th>
                <th>Order Type</th>
                <th>Cashier</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->order_code }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ Carbon\Carbon::parse($order->visit_date)->format('d/m/Y') }}</td>
                <td>{{ $order->quantity }} {{ $order->quantity > 1 ? 'tickets' : 'ticket' }}</td>
                <td>Rp{{ number_format($order->price_per_ticket ?? ($order->total_price / $order->quantity), 0, ',', '.') }}</td>
                <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td>{{ $order->discount ? 'Rp' . number_format($order->discount, 0, ',', '.') : '-' }}</td>
                <td>Rp{{ number_format($order->total_price - ($order->discount ?? 0), 0, ',', '.') }}</td>
                <td>
                    @if($order->payment_method == 'cash')
                        <span class="payment-badge payment-cash">Cash</span>
                    @elseif($order->payment_method == 'card')
                        <span class="payment-badge payment-card">Card</span>
                    @elseif($order->payment_method == 'xendit')
                        <span class="payment-badge payment-online">Online</span>
                    @else
                        {{ ucfirst($order->payment_method) }}
                    @endif
                </td>
                <td>{{ $order->is_offline_order ? 'Offline (POS)' : 'Online' }}</td>
                <td>{{ $order->cashier ? $order->cashier->name : 'System' }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="12" style="text-align: center; padding: 30px;">
                    No orders found with current filters.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Summary Statistics -->
    @if($orders->count() > 0)
    <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border: 1px solid #ddd;">
        <h4 style="margin-bottom: 15px; color: #333;">Order Statistics</h4>
        <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
            <div style="width: 48%; margin-bottom: 10px;">
                <strong>Payment Method Breakdown:</strong><br>
                @php
                    $cashOrders = $orders->where('payment_method', 'cash')->count();
                    $cardOrders = $orders->where('payment_method', 'card')->count();
                    $onlineOrders = $orders->where('payment_method', 'xendit')->count();
                @endphp
                • Cash: {{ $cashOrders }} orders<br>
                • Card: {{ $cardOrders }} orders<br>
                • Online: {{ $onlineOrders }} orders
            </div>
            <div style="width: 48%; margin-bottom: 10px;">
                <strong>Order Type Breakdown:</strong><br>
                @php
                    $offlineOrders = $orders->where('is_offline_order', true)->count();
                    $onlineOrdersType = $orders->where('is_offline_order', false)->count();
                @endphp
                • Offline (POS): {{ $offlineOrders }} orders<br>
                • Online: {{ $onlineOrdersType }} orders
            </div>
        </div>
        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ccc;">
            <strong>Revenue Analysis:</strong><br>
            • Average Order Value: Rp{{ number_format($orders->avg('total_price'), 0, ',', '.') }}<br>
            • Average Tickets per Order: {{ round($orders->avg('quantity'), 1) }}<br>
            @if($total_discount > 0)
            • Average Discount per Order: Rp{{ number_format($orders->where('discount', '>', 0)->avg('discount'), 0, ',', '.') }}
            @endif
        </div>
    </div>
    @endif
    
    <div class="footer">
        <p>Beach Ticket Management System - Report generated on {{ $export_date }}</p>
        <p>This report contains {{ $orders->count() }} order records</p>
    </div>
</body>
</html>
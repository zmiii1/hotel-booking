<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Receipt #{{ $order->order_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            padding: 15px;
            font-size: 13px;
            line-height: 1.4;
        }
        
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .receipt-header {
            background: linear-gradient(135deg, #e91e63 0%, #f06292 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .receipt-header h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .receipt-header h2 {
            font-size: 14px;
            font-weight: 400;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        
        .receipt-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
        }
        
        .receipt-meta div {
            background: rgba(255, 255, 255, 0.15);
            padding: 5px 10px;
            border-radius: 15px;
        }
        
        .receipt-body {
            padding: 20px;
        }
        
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #e91e63;
        }
        
        .info-group h3 {
            color: #e91e63;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        
        .info-group h3 i {
            margin-right: 6px;
            font-size: 14px;
        }
        
        .info-item {
            margin-bottom: 4px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }
        
        .info-label {
            font-weight: 500;
            color: #555;
        }
        
        .info-value {
            color: #333;
            font-weight: 400;
        }
        
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .order-table thead {
            background: linear-gradient(135deg, #e91e63 0%, #f06292 100%);
            color: white;
        }
        
        .order-table th,
        .order-table td {
            padding: 10px 8px;
            text-align: left;
            border: none;
            font-size: 12px;
        }
        
        .order-table th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .order-table tbody tr {
            background: white;
            border-bottom: 1px solid #eee;
        }
        
        .order-table .text-center {
            text-align: center;
        }
        
        .order-table .text-end {
            text-align: right;
        }
        
        .order-table tfoot {
            background: #f8f9fa;
        }
        
        .order-table tfoot td {
            font-weight: 600;
            border-top: 2px solid #e91e63;
            padding: 8px;
        }
        
        .total-row {
            background: #e91e63 !important;
            color: white !important;
        }
        
        .ticket-badge {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-right: 5px;
        }
        
        .beach-badge {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 500;
        }
        
        .additional-section {
            background: #fff3e0;
            border: 1px solid #ffcc02;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 15px;
        }
        
        .additional-section h4 {
            color: #f57f17;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            font-size: 13px;
        }
        
        .additional-section h4 i {
            margin-right: 6px;
        }
        
        .qr-section {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        
        .order-code {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: bold;
            color: #e91e63;
            letter-spacing: 1.5px;
            padding: 10px;
            border: 2px dashed #e91e63;
            border-radius: 6px;
            background: white;
            margin-top: 8px;
        }
        
        .receipt-footer {
            background: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
        }
        
        .receipt-footer h3 {
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .footer-info {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 10px;
            font-size: 11px;
        }
        
        .footer-info div {
            display: flex;
            align-items: center;
        }
        
        .footer-info i {
            margin-right: 5px;
            font-size: 12px;
        }
        
        .print-section {
            text-align: center;
            padding: 15px;
            background: white;
        }
        
        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        /* Print Styles - COMPACT VERSION */
        @media print {
            body {
                background: white;
                padding: 0;
                font-size: 11px;
            }
            
            .receipt-container {
                box-shadow: none;
                border-radius: 0;
                max-width: none;
                margin: 0;
            }
            
            .print-section {
                display: none;
            }
            
            .receipt-header {
                background: #e91e63 !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                padding: 15px;
            }
            
            .receipt-header h1 {
                font-size: 18px;
                margin-bottom: 3px;
            }
            
            .receipt-header h2 {
                font-size: 12px;
                margin-bottom: 8px;
            }
            
            .receipt-body {
                padding: 15px;
            }
            
            .info-section {
                margin-bottom: 12px;
                padding: 12px;
            }
            
            .order-table th,
            .order-table td {
                padding: 6px 5px;
                font-size: 10px;
            }
            
            .order-table thead {
                background: #e91e63 !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .total-row {
                background: #e91e63 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .qr-section {
                padding: 10px;
                margin-bottom: 10px;
            }
            
            .order-code {
                font-size: 14px;
                padding: 8px;
                letter-spacing: 1px;
            }
            
            .receipt-footer {
                background: #2c3e50 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                padding: 10px;
            }
            
            .receipt-footer h3 {
                font-size: 14px;
                margin-bottom: 8px;
            }
            
            .footer-info {
                font-size: 9px;
                gap: 15px;
            }
            
            @page {
                size: A4;
                margin: 10mm;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 8px;
            }
            
            .receipt-body {
                padding: 15px;
            }
            
            .info-section {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .receipt-meta {
                flex-direction: column;
                gap: 8px;
            }
            
            .order-table {
                font-size: 11px;
            }
            
            .order-table th,
            .order-table td {
                padding: 8px 6px;
            }
            
            .footer-info {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <h1>🏖️ Lalassa Beach Club</h1>
            <h2>Official Ticket Receipt</h2>
            <div class="receipt-meta">
                <div>
                    <strong>Receipt #{{ $order->order_code }}</strong>
                </div>
                <div>
                    {{ $order->created_at->format('d M Y, H:i') }} WIB
                </div>
            </div>
        </div>
        
        <!-- Body -->
        <div class="receipt-body">
            <!-- Customer & Order Info -->
            <div class="info-section">
                <div class="info-group">
                    <h3><i class='bx bx-user'></i> Customer Information</h3>
                    <div class="info-item">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $order->customer_name }}</span>
                    </div>
                    @if($order->customer_email)
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $order->customer_email }}</span>
                    </div>
                    @endif
                    @if($order->customer_phone)
                    <div class="info-item">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">{{ $order->customer_phone }}</span>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Type:</span>
                        <span class="info-value">{{ $order->is_offline_order ? 'Walk-in Guest' : 'Online' }}</span>
                    </div>
                </div>
                
                <div class="info-group">
                    <h3><i class='bx bx-calendar'></i> Visit Information</h3>
                    <div class="info-item">
                        <span class="info-label">Visit Date:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Payment Method:</span>
                        <span class="info-value">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                    @if($order->cashier)
                    <div class="info-item">
                        <span class="info-label">Served by:</span>
                        <span class="info-value">{{ $order->cashier->name }}</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Order Details Table -->
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Ticket Details</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div style="margin-bottom: 5px;">
                                <strong>{{ $order->ticket->name }}</strong>
                            </div>
                            <div>
                                <span class="beach-badge">{{ ucfirst($order->ticket->beach_name) }}</span>
                                <span class="ticket-badge">{{ ucfirst($order->ticket->ticket_type) }}</span>
                            </div>
                        </td>
                        <td class="text-center" style="font-weight: 600; font-size: 14px;">
                            {{ $order->quantity }}
                        </td>
                        <td class="text-end">
                            Rp {{ number_format($order->ticket->price, 0, ',', '.') }}
                        </td>
                        <td class="text-end" style="font-weight: 600;">
                            Rp {{ number_format($order->ticket->price * $order->quantity, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    @if($order->discount && $order->discount > 0)
                    <tr style="color: #4CAF50;">
                        <td colspan="3" class="text-end"><strong>Discount:</strong></td>
                        <td class="text-end"><strong>- Rp {{ number_format($order->discount, 0, ',', '.') }}</strong></td>
                    </tr>
                    @endif
                    
                    <tr class="total-row">
                        <td colspan="3" class="text-end"><strong>TOTAL AMOUNT:</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                    </tr>
                    
                    @if($order->payment_method == 'cash' && $order->amount_tendered)
                    <tr>
                        <td colspan="3" class="text-end"><strong>Amount Tendered:</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($order->amount_tendered, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr style="color: #4CAF50;">
                        <td colspan="3" class="text-end"><strong>Change:</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($order->amount_tendered - $order->total_price, 0, ',', '.') }}</strong></td>
                    </tr>
                    @endif
                </tfoot>
            </table>
            
            <!-- Additional Notes -->
            @if($order->additional_request)
            <div class="additional-section">
                <h4><i class='bx bx-note'></i> Special Request</h4>
                <p style="font-size: 12px;">{{ $order->additional_request }}</p>
            </div>
            @endif
            
            <!-- QR Code Section -->
            <div class="qr-section">
                <h4 style="color: #e91e63; margin-bottom: 8px; font-size: 14px;">
                    <i class='bx bx-qr'></i> Order Verification Code
                </h4>
                <p style="color: #666; margin-bottom: 8px; font-size: 11px;">Show this code at the entrance</p>
                <div class="order-code">{{ $order->order_code }}</div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="receipt-footer">
            <h3>🙏 Thank You for Your Purchase!</h3>
            <div class="footer-info">
                <div>
                    <i class='bx bx-phone'></i>
                    +62 813-8888-9999
                </div>
                <div>
                    <i class='bx bx-envelope'></i>
                    info@lalassabeachclub.com
                </div>
                <div>
                    <i class='bx bx-map'></i>
                    Lalassa Beach Club, Banten
                </div>
            </div>
            <p style="opacity: 0.8; font-size: 10px; margin-top: 8px;">
                This receipt is automatically generated and valid without signature.<br>
                Please keep this receipt for entry verification.
            </p>
        </div>
    </div>
    
    <!-- Print Actions -->
    <div class="print-section">
        <button onclick="window.print();" class="btn btn-primary">
            <i class='bx bx-printer'></i> Print Receipt
        </button>
        <button onclick="window.close();" class="btn btn-secondary">
            <i class='bx bx-x'></i> Close Window
        </button>
    </div>
    
    <script>
        // Auto-print functionality
        window.addEventListener('load', function() {
            setTimeout(function() {
                if (window.location.search.includes('print=1') || document.referrer.includes('orders')) {
                    window.print();
                }
            }, 500);
        });
        
        function printReceipt() {
            window.print();
        }
        
        window.addEventListener('beforeprint', function() {
            document.title = 'Receipt-' + '{{ $order->order_code }}';
        });
        
        window.addEventListener('afterprint', function() {
            document.title = 'Receipt #{{ $order->order_code }}';
        });
    </script>
</body>
</html>
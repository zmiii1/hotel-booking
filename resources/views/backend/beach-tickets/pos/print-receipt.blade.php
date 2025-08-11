<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $order->order_code }} - Lalassa Beach Club</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .receipt {
            max-width: 80mm; /* Standard thermal receipt width */
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            margin: 5px 0;
        }
        .header h2 {
            font-size: 14px;
            margin: 5px 0;
        }
        .header p {
            margin: 2px 0;
        }
        .customer-info {
            margin-bottom: 15px;
        }
        .customer-info p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        th {
            border-bottom: 1px solid #ddd;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals {
            margin-top: 5px;
        }
        .totals p {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 1px dashed #ddd;
            padding-top: 10px;
        }
        .footer p {
            margin: 2px 0;
        }
        .bold {
            font-weight: bold;
        }
        .qr-code {
            text-align: center;
            margin: 15px 0;
        }
        @media print {
            body {
                padding: 0;
            }
            .receipt {
                border: none;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>Lalassa Beach Club</h1>
            <h2>Receipt</h2>
            <p>Date: {{ $order->created_at->format('M d, Y, h:i A') }}</p>
            <p>Order #: {{ $order->order_code }}</p>
            <p>Cashier: {{ optional($order->cashier)->name ?? 'System' }}</p>
        </div>
        
        <div class="customer-info">
            <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
            @if($order->customer_email)
            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
            @endif
            @if($order->customer_phone)
            <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
            @endif
            <p><strong>Visit Date:</strong> {{ \Carbon\Carbon::parse($order->visit_date)->format('M d, Y') }}</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $order->ticket->name }}</td>
                    <td class="text-center">{{ $order->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($order->ticket->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($order->subtotal ?? ($order->ticket->price * $order->quantity), 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="totals">
            <p>
                <span class="bold">Subtotal:</span>
                <span>Rp {{ number_format($order->subtotal ?? ($order->ticket->price * $order->quantity), 0, ',', '.') }}</span>
            </p>
            
            @if($order->discount > 0)
            <p>
                <span class="bold">Discount:</span>
                <span>Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
            </p>
            @endif
            
            <p>
                <span class="bold">Total:</span>
                <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </p>
            
            @if($order->payment_method == 'cash')
            <p>
                <span class="bold">Amount Tendered:</span>
                <span>Rp {{ number_format($order->amount_tendered ?? $order->total_price, 0, ',', '.') }}</span>
            </p>
            
            @if(isset($order->amount_tendered) && $order->amount_tendered > $order->total_price)
            <p>
                <span class="bold">Change:</span>
                <span>Rp {{ number_format($order->amount_tendered - $order->total_price, 0, ',', '.') }}</span>
            </p>
            @endif
            @endif
            
            <p>
                <span class="bold">Payment Method:</span>
                <span>{{ ucfirst($order->payment_method) }}</span>
            </p>
        </div>
        
        @if($order->additional_request)
        <div class="additional-info">
            <p class="bold">Additional Notes:</p>
            <p>{{ $order->additional_request }}</p>
        </div>
        @endif
        
        <div class="qr-code">
            {!! QrCode::size(100)->generate(route('ticket-orders.confirmation', ['order_code' => $order->order_code])) !!}
        </div>
        
        <div class="footer">
            <p>Thank you for your purchase!</p>
            <p>For inquiries, please contact:</p>
            <p>Phone: +62-xxx-xxxx-xxxx</p>
            <p>Email: info@lalassabeachclub.com</p>
        </div>
    </div>
    
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print();" style="padding: 8px 16px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Print Receipt
        </button>
        <button onclick="window.close();" style="padding: 8px 16px; background-color: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            Close
        </button>
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
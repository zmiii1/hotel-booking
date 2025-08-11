<!-- resources/views/emails/ticket/confirmation.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Beach Ticket Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background-color: #15b3c7;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .section {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        .button {
            display: inline-block;
            background-color: #e91e63;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .benefits {
            margin-left: 20px;
            padding-left: 0;
        }
        .benefits li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('frontend/assets/img/logotl1.png') }}" alt="Tanjung Lesung" class="logo">
        <h1>Beach Ticket Order Confirmation</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $order->customer_name }},</p>
        
        <p>Thank you for your order. Your beach ticket has been confirmed!</p>
        
        <div class="section">
            <h2>Order Details</h2>
            <table>
                <tr>
                    <th>Order Reference</th>
                    <td>{{ $order->order_code }}</td>
                </tr>
                <tr>
                    <th>Ticket</th>
                    <td>{{ $order->ticket->name }}</td>
                </tr>
                <tr>
                    <th>Visit Date</th>
                    <td>{{ \Carbon\Carbon::parse($order->visit_date)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <th>Quantity</th>
                    <td>{{ $order->quantity }}</td>
                </tr>
            </table>
        </div>
        
        <div class="section">
            <h2>Benefits</h2>
            <ul class="benefits">
                @foreach($order->ticket->benefits as $benefit)
                    <li>{{ $benefit->benefit_name }}</li>
                @endforeach
            </ul>
        </div>
        
        <div class="section">
            <h2>Price Details</h2>
            <table>
                <tr>
                    <th>Ticket Price ({{ $order->quantity }}x)</th>
                    <td>Rp. {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Total</th>
                    <td><strong>Rp. {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>
        
        @if($order->additional_request)
        <div class="section">
            <h2>Additional Requests</h2>
            <p>{{ $order->additional_request }}</p>
        </div>
        @endif
        
        <p>Please print this email or show it on your mobile device when you arrive at the beach entrance.</p>
        
        <p>We look forward to seeing you at {{ $order->ticket->beach_name == 'lalassa' ? 'Lalassa Beach Club' : 'Bodur Beach' }}!</p>
        
        <p>Best regards,<br>
        Tanjung Lesung Team</p>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} Tanjung Lesung. All rights reserved.</p>
        <p>If you have any questions, please contact us at support@tanjunglesung.com</p>
    </div>
</body>
</html>
@extends('admin.admin_dashboard')

@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Payment Successful</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 64px;"></i>
                        <h3 class="mt-3">Order Completed Successfully</h3>
                        <p class="text-muted">Order Code: <strong>{{ $order->order_code }}</strong></p>
                    </div>
                    
                    <div class="receipt-container border p-4 mb-4">
                        <div class="text-center mb-4">
                            <h4>Lalassa Beach Club</h4>
                            <h5>Receipt</h5>
                            <p class="mb-0">Date: {{ $order->created_at->format('M d, Y, h:i A') }}</p>
                            <p>Cashier: {{ auth()->user()->name }}</p>
                        </div>
                        
                        <div class="customer-info mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
                                    @if($order->customer_email)
                                    <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                                    @endif
                                    @if($order->customer_phone)
                                    <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <p><strong>Visit Date:</strong> {{ \Carbon\Carbon::parse($order->visit_date)->format('M d, Y') }}</p>
                                    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                                    <p><strong>Order #:</strong> {{ $order->order_code }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                <tr>
                                    <td>{{ $item['ticket']->name }}</td>
                                    <td class="text-center">{{ $item['quantity'] }}</td>
                                    <td class="text-end">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if($discount > 0)
                                <tr>
                                    <td colspan="3" class="text-end">
                                        <strong>Discount:</strong>
                                        @if($promoCode)
                                        <small class="text-muted">({{ $promoCode->code }})</small>
                                        @endif
                                    </td>
                                    <td class="text-end">Rp {{ number_format($discount, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                                </tr>
                                @if($order->payment_method == 'cash')
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Amount Tendered:</strong></td>
                                    <td class="text-end">Rp {{ number_format($validated['amount_tendered'], 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Change:</strong></td>
                                    <td class="text-end">Rp {{ number_format($change, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                        
                        @if($order->additional_request)
                        <div class="additional-info mt-3">
                            <p><strong>Additional Notes:</strong></p>
                            <p>{{ $order->additional_request }}</p>
                        </div>
                        @endif
                        
                        <div class="text-center mt-4">
                            <p>Thank you for your purchase!</p>
                            <p class="mb-0">For inquiries, please contact:</p>
                            <p class="mb-0">Phone: +62811 1580 025</p>
                            <p>Email: info@lalassabeachclub.com</p>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('backend.beach-tickets.pos.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to POS
                        </a>
                        <a href="{{ route('backend.pos.print-receipt', ['order_code' => $order->order_code]) }}" class="btn btn-primary" target="_blank">
                            <i class="fas fa-print"></i> Print Receipt
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('frontend.main')

@section('main')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card payment-card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Bank Transfer Instructions</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5 class="mb-2">Booking Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Booking Code:</strong> {{ $booking->code }}</p>
                                <p class="mb-1"><strong>Name:</strong> {{ $booking->first_name }} {{ $booking->last_name }}</p>
                                <p class="mb-1"><strong>Room:</strong> {{ $roomName }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->format('D, d M Y') }}</p>
                                <p class="mb-1"><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out)->format('D, d M Y') }}</p>
                                <p class="mb-1"><strong>Total Amount:</strong> <span class="fw-bold text-danger">Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="transfer-instructions mb-4">
                        <h5 class="mb-3">Please follow these steps to complete your payment:</h5>
                        <ol class="transfer-steps">
                            <li>Choose one of our bank accounts below for your transfer</li>
                            <li>Transfer the exact amount: <strong>Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</strong></li>
                            <li>Include your booking code <strong>{{ $booking->code }}</strong> in the transfer description</li>
                            <li>Upload your payment receipt in the form below</li>
                            <li>Wait for our confirmation (usually within 24 hours)</li>
                        </ol>
                    </div>

                    <div class="bank-accounts mb-4">
                        <h5 class="mb-3">Bank Account Information</h5>
                        <div class="row">
                            @foreach($bankAccounts as $account)
                            <div class="col-md-6 mb-3">
                                <div class="bank-account-card">
                                    <div class="bank-logo">
                                        <img src="{{ asset('frontend/assets/img/banks/' . strtolower(explode(' ', $account['bank_name'])[1]) . '.png') }}" alt="{{ $account['bank_name'] }}" onerror="this.src='{{ asset('frontend/assets/img/banks/default.png') }}'" class="img-fluid" style="max-height: 40px;">
                                    </div>
                                    <div class="bank-details">
                                        <h6>{{ $account['bank_name'] }}</h6>
                                        <p class="account-number">{{ $account['account_number'] }}</p>
                                        <p class="account-name">{{ $account['account_name'] }}</p>
                                        <p class="branch">{{ $account['branch'] }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="upload-section">
                        <h5 class="mb-3">Upload Payment Receipt</h5>
                        <form action="{{ route('booking.upload_receipt', ['code' => $booking->code]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="bank_name" class="form-label">Bank Name</label>
                                <select class="form-select @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" required>
                                    <option value="">Select Bank</option>
                                    <option value="Bank BCA">Bank BCA</option>
                                    <option value="Bank Mandiri">Bank Mandiri</option>
                                    <option value="Bank BNI">Bank BNI</option>
                                    <option value="Bank BRI">Bank BRI</option>
                                    <option value="Other">Other</option>
                                </select>
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="account_name" class="form-label">Account Name</label>
                                <input type="text" class="form-control @error('account_name') is-invalid @enderror" id="account_name" name="account_name" required>
                                @error('account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="transfer_date" class="form-label">Transfer Date</label>
                                        <input type="date" class="form-control @error('transfer_date') is-invalid @enderror" id="transfer_date" name="transfer_date" required max="{{ date('Y-m-d') }}">
                                        @error('transfer_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="transfer_amount" class="form-label">Transfer Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control @error('transfer_amount') is-invalid @enderror" id="transfer_amount" name="transfer_amount" value="{{ $booking->total_amount }}" required>
                                        </div>
                                        @error('transfer_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="receipt_file" class="form-label">Receipt File</label>
                                <input type="file" class="form-control @error('receipt_file') is-invalid @enderror" id="receipt_file" name="receipt_file" accept="image/jpeg,image/png,image/jpg,application/pdf" required>
                                <div class="form-text">Upload your payment receipt (JPEG, PNG, or PDF files only, max 2MB)</div>
                                @error('receipt_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Submit Payment Receipt</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .payment-card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .card-header {
        background-color: #e83e8c;
        padding: 15px 20px;
    }
    
    .transfer-steps {
        padding-left: 20px;
    }
    
    .transfer-steps li {
        margin-bottom: 10px;
        padding-left: 10px;
    }
    
    .bank-account-card {
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 15px;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .bank-logo {
        margin-bottom: 10px;
    }
    
    .bank-details {
        flex-grow: 1;
    }
    
    .bank-details h6 {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .account-number {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .account-name, .branch {
        font-size: 14px;
        color: #666;
        margin-bottom: 3px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill transfer amount with booking total
    const transferAmount = document.getElementById('transfer_amount');
    if (transferAmount) {
        transferAmount.value = '{{ $booking->total_amount }}';
    }
    
    // Set today as default transfer date
    const transferDate = document.getElementById('transfer_date');
    if (transferDate) {
        transferDate.value = new Date().toISOString().split('T')[0];
    }
});
</script>
@endsection
@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">
            <i class="bx bx-export me-2"></i>Export Beach Ticket Orders
        </div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.index') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('backend.beach-tickets.dashboard') }}">Beach Tickets</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('backend.beach-tickets.orders.index') }}">Orders</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Export Data</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bx bx-export" style="font-size: 2rem; color: var(--pink-primary);"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Export Beach Ticket Order Data</h5>
                            <p class="mb-0 text-muted">Choose your export format and apply filters</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('backend.beach-tickets.orders.export.download') }}" method="GET" id="exportForm">
                        <!-- Export Format Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="mb-3">Export Format</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card export-option" data-format="csv">
                                            <div class="card-body text-center">
                                                <i class="bx bx-file" style="font-size: 3rem; color: #28a745;"></i>
                                                <h6 class="mt-2 mb-1">CSV Format</h6>
                                                <p class="text-muted small mb-0">Excel compatible, all data fields</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card export-option" data-format="pdf">
                                            <div class="card-body text-center">
                                                <i class="bx bx-file-pdf" style="font-size: 3rem; color: #dc3545;"></i>
                                                <h6 class="mt-2 mb-1">PDF Report</h6>
                                                <p class="text-muted small mb-0">Professional report with charts</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="format" id="selectedFormat" value="csv">
                            </div>
                        </div>

                        <!-- Filters Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="mb-3">Filters (Optional)</h6>
                                <div class="border rounded p-3" style="background: #f8f9fa;">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Payment Method</label>
                                            <select class="form-select" name="payment_method">
                                                <option value="">All Payment Methods</option>
                                                <option value="cash" {{ isset($filters['payment_method']) && $filters['payment_method'] == 'cash' ? 'selected' : '' }}>Cash</option>
                                                <option value="card" {{ isset($filters['payment_method']) && $filters['payment_method'] == 'card' ? 'selected' : '' }}>Card</option>
                                                <option value="xendit" {{ isset($filters['payment_method']) && $filters['payment_method'] == 'xendit' ? 'selected' : '' }}>Online</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Order Type</label>
                                            <select class="form-select" name="order_type">
                                                <option value="">All Order Types</option>
                                                <option value="offline" {{ isset($filters['order_type']) && $filters['order_type'] == 'offline' ? 'selected' : '' }}>Offline (POS)</option>
                                                <option value="online" {{ isset($filters['order_type']) && $filters['order_type'] == 'online' ? 'selected' : '' }}>Online</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Visit Date From</label>
                                            <input type="date" class="form-control" name="date_from" 
                                                   value="{{ $filters['date_from'] ?? '' }}">
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Visit Date To</label>
                                            <input type="date" class="form-control" name="date_to" 
                                                   value="{{ $filters['date_to'] ?? '' }}">
                                        </div>
                                        
                                        <div class="col-12">
                                            <label class="form-label">Search</label>
                                            <input type="text" class="form-control" name="search" 
                                                   placeholder="Order ID, customer name..." 
                                                   value="{{ $filters['search'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('backend.beach-tickets.orders.index') }}" class="btn btn-outline-secondary">
                                        <i class="bx bx-arrow-back me-1"></i>Back to Orders
                                    </a>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary" onclick="previewData()">
                                            <i class="bx bx-show me-1"></i>Preview Data
                                        </button>
                                        <button type="submit" class="btn btn-primary" id="exportBtn">
                                            <i class="bx bx-download me-1"></i>Export Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Data Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading preview...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('exportForm').submit();">
                    <i class="bx bx-download me-1"></i>Export This Data
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.export-option {
    cursor: pointer;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.export-option:hover {
    border-color: var(--pink-primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.export-option.selected {
    border-color: var(--pink-primary);
    background: linear-gradient(135deg, rgba(233, 30, 99, 0.1), rgba(233, 30, 99, 0.05));
}

.export-option.selected i {
    color: var(--pink-primary) !important;
}
</style>

<script>
// Pass route URL from Blade to JavaScript
const previewDataUrl = @json(route('backend.beach-tickets.orders.preview-data'));

document.addEventListener('DOMContentLoaded', function() {
    // Handle export format selection
    const exportOptions = document.querySelectorAll('.export-option');
    const formatInput = document.getElementById('selectedFormat');
    const exportBtn = document.getElementById('exportBtn');
    
    exportOptions.forEach(option => {
        option.addEventListener('click', function() {
            exportOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            
            const format = this.getAttribute('data-format');
            formatInput.value = format;
            
            if (format === 'pdf') {
                exportBtn.innerHTML = '<i class="bx bx-download me-1"></i>Export as PDF';
            } else {
                exportBtn.innerHTML = '<i class="bx bx-download me-1"></i>Export as CSV';
            }
        });
    });
    
    // Set initial selection
    document.querySelector('[data-format="csv"]').classList.add('selected');
    
    // Handle form submission
    document.getElementById('exportForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('exportBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Exporting...';
        
        setTimeout(() => {
            submitBtn.disabled = false;
            if (formatInput.value === 'pdf') {
                submitBtn.innerHTML = '<i class="bx bx-download me-1"></i>Export as PDF';
            } else {
                submitBtn.innerHTML = '<i class="bx bx-download me-1"></i>Export as CSV';
            }
        }, 3000);
    });
});

function previewData() {
    const formData = new FormData(document.getElementById('exportForm'));
    const selectedFormat = document.getElementById('selectedFormat').value;
    const params = new URLSearchParams(formData);
    
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
    
    // Update modal title based on format
    const modalTitle = document.querySelector('#previewModal .modal-title');
    modalTitle.textContent = selectedFormat === 'pdf' ? 'PDF Data Preview' : 'CSV Data Preview';
    
    // Show loading
    document.getElementById('previewContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading actual ${selectedFormat.toUpperCase()} data preview...</p>
        </div>
    `;
    
    // Fetch actual preview data using the URL passed from Blade
    fetch(`${previewDataUrl}?${params.toString()}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (selectedFormat === 'csv') {
                    showCSVDataPreview(data);
                } else {
                    showPDFDataPreview(data);
                }
            } else {
                throw new Error('Failed to load preview data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('previewContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="bx bx-exclamation-circle me-2"></i>
                    Error loading preview: ${error.message}
                    <br><small>URL: ${previewDataUrl}</small>
                </div>
            `;
        });
}

function showCSVDataPreview(data) {
    let csvRows = '';
    
    // Check if we have orders
    if (!data.orders || data.orders.length === 0) {
        document.getElementById('previewContent').innerHTML = `
            <div class="alert alert-warning">
                <i class="bx bx-info-circle me-2"></i>
                <strong>No Data Found:</strong> No orders match your current filters.
            </div>
        `;
        return;
    }
    
    // Generate sample CSV rows
    data.orders.forEach((order, index) => {
        csvRows += `
            <tr>
                <td>${order.id}</td>
                <td>${order.order_code}</td>
                <td>${order.customer_name}</td>
                <td>${order.customer_email}</td>
                <td>${order.customer_phone}</td>
                <td>${order.formatted_visit_date}</td>
                <td>${order.visit_day}</td>
                <td>${order.quantity}</td>
                <td>Rp${new Intl.NumberFormat('id-ID').format(order.price_per_ticket)}</td>
                <td>${order.formatted_total_price}</td>
                <td>${order.formatted_discount}</td>
                <td>${order.formatted_final_amount}</td>
                <td>${order.payment_method}</td>
                <td>${order.order_type}</td>
                <td>${order.cashier_name}</td>
                <td>${order.created_at}</td>
            </tr>
        `;
    });
    
    const csvPreview = `
        <div class="alert alert-info">
            <i class="bx bx-info-circle me-2"></i>
            <strong>CSV Preview:</strong> Showing ${data.showing_count} of ${data.total_count} orders that will be exported.
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size: 0.8rem;">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Order Code</th>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>Customer Phone</th>
                        <th>Visit Date</th>
                        <th>Visit Day</th>
                        <th>Quantity</th>
                        <th>Price Per Ticket</th>
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
                    ${csvRows}
                </tbody>
            </table>
        </div>
        
        ${data.total_count > data.showing_count ? `
            <div class="alert alert-warning">
                <i class="bx bx-info-circle me-2"></i>
                <strong>Note:</strong> Preview shows first ${data.showing_count} records. 
                Full CSV export will contain all ${data.total_count} records.
            </div>
        ` : ''}
        
        <div class="alert alert-success">
            <i class="bx bx-check-circle me-2"></i>
            <strong>CSV Export Ready:</strong> Your file will contain ${data.total_count} orders with all columns shown above.
        </div>
    `;
    
    document.getElementById('previewContent').innerHTML = csvPreview;
}

function showPDFDataPreview(data) {
    // Check if we have orders
    if (!data.orders || data.orders.length === 0) {
        document.getElementById('previewContent').innerHTML = `
            <div class="alert alert-warning">
                <i class="bx bx-info-circle me-2"></i>
                <strong>No Data Found:</strong> No orders match your current filters.
            </div>
        `;
        return;
    }
    
    let pdfRows = '';
    let totalRevenue = 0;
    let totalTickets = 0;
    
    // Generate sample PDF rows
    data.orders.forEach((order, index) => {
        const rowStyle = index % 2 === 0 ? 'background: #f9f9f9;' : '';
        totalRevenue += order.total_price;
        totalTickets += order.quantity;
        
        pdfRows += `
            <tr style="${rowStyle}">
                <td style="border: 1px solid #ddd; padding: 6px; font-size: 10px;">${order.order_code}</td>
                <td style="border: 1px solid #ddd; padding: 6px; font-size: 10px;">${order.customer_name}</td>
                <td style="border: 1px solid #ddd; padding: 6px; font-size: 10px;">${order.formatted_visit_date}</td>
                <td style="border: 1px solid #ddd; padding: 6px; font-size: 10px;">${order.quantity}</td>
                <td style="border: 1px solid #ddd; padding: 6px; font-size: 10px;">${order.payment_method}</td>
                <td style="border: 1px solid #ddd; padding: 6px; font-size: 10px;">${order.order_type}</td>
                <td style="border: 1px solid #ddd; padding: 6px; font-size: 10px;">${order.cashier_name}</td>
                <td style="border: 1px solid #ddd; padding: 6px; font-size: 10px;">${order.formatted_final_amount}</td>
            </tr>
        `;
    });
    
    const pdfPreview = `
        <div class="alert alert-info">
            <i class="bx bx-file-pdf me-2"></i>
            <strong>PDF Preview:</strong> Showing ${data.showing_count} of ${data.total_count} orders that will be in your PDF report.
        </div>
        
        <div style="border: 1px solid #ddd; padding: 20px; background: white; font-family: Arial, sans-serif; max-height: 500px; overflow-y: auto;">
            <div style="text-align: center; border-bottom: 2px solid #e91e63; padding-bottom: 15px; margin-bottom: 20px;">
                <h3 style="color: #e91e63; margin: 0;">Beach Ticket Orders Report</h3>
                <p style="margin: 5px 0; color: #666;">Generated on ${new Date().toLocaleDateString('en-GB', { 
                    day: '2-digit', 
                    month: 'long', 
                    year: 'numeric' 
                })}</p>
            </div>
            
            <div style="background: #f8f9fa; padding: 15px; margin-bottom: 20px; text-align: center;">
                <strong>Summary:</strong> 
                ${data.total_count} Total Orders | 
                Revenue: Rp${new Intl.NumberFormat('id-ID').format(totalRevenue)} | 
                Tickets Sold: ${totalTickets}
            </div>
            
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <thead>
                    <tr style="background: #e91e63; color: white;">
                        <th style="border: 1px solid #ddd; padding: 8px;">Order Code</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Customer</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Visit Date</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Qty</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Payment</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Type</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Cashier</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    ${pdfRows}
                </tbody>
            </table>
            
            ${data.total_count > data.showing_count ? `
                <div style="text-align: center; font-style: italic; padding: 10px; border: 1px solid #ddd; margin-top: 10px;">
                    ... and ${data.total_count - data.showing_count} more orders
                </div>
            ` : ''}
        </div>
        
        <div class="alert alert-success mt-3">
            <i class="bx bx-check-circle me-2"></i>
            <strong>PDF Features:</strong> Professional layout, summary statistics, detailed table with ${data.total_count} orders, and analytics.
        </div>
    `;
    
    document.getElementById('previewContent').innerHTML = pdfPreview;
}
</script>

@endsection
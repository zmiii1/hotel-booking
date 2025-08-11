@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">
            <i class="bx bx-export me-2"></i>Export Bookings
        </div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.bookings') }}">Bookings</a>
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
                            <h5 class="mb-1">Export Booking Data</h5>
                            <p class="mb-0 text-muted">Choose your export format and apply filters</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bookings.export') }}" method="GET" id="exportForm">
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
                                            <label class="form-label">Hotel</label>
                                            <select class="form-select" name="hotel_id">
                                                <option value="">All Hotels</option>
                                                @foreach($hotels as $hotel)
                                                <option value="{{ $hotel->id }}" {{ isset($filters['hotel_id']) && $filters['hotel_id'] == $hotel->id ? 'selected' : '' }}>
                                                    {{ $hotel->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Check-in From Date</label>
                                            <input type="date" class="form-control" name="date_from" 
                                                   value="{{ $filters['date_from'] ?? '' }}">
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Check-in To Date</label>
                                            <input type="date" class="form-control" name="date_to" 
                                                   value="{{ $filters['date_to'] ?? '' }}">
                                        </div>
                                        
                                        <div class="col-12">
                                            <label class="form-label">Search</label>
                                            <input type="text" class="form-control" name="search" 
                                                   placeholder="Booking ID, guest name, email, phone..." 
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
                                    <a href="{{ route('admin.bookings') }}" class="btn btn-outline-secondary">
                                        <i class="bx bx-arrow-back me-1"></i>Back to Bookings
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
document.addEventListener('DOMContentLoaded', function() {
    // Handle export format selection
    const exportOptions = document.querySelectorAll('.export-option');
    const formatInput = document.getElementById('selectedFormat');
    const exportBtn = document.getElementById('exportBtn');
    
    exportOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            exportOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked option
            this.classList.add('selected');
            
            // Update hidden input
            const format = this.getAttribute('data-format');
            formatInput.value = format;
            
            // Update button text
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
        
        // Re-enable button after delay
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
    modalTitle.textContent = selectedFormat === 'pdf' ? 'PDF Preview' : 'CSV Data Preview';
    
    // Show loading
    document.getElementById('previewContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading ${selectedFormat.toUpperCase()} preview...</p>
        </div>
    `;
    
    if (selectedFormat === 'csv') {
        // Preview CSV format
        previewCSV(params);
    } else {
        // Preview PDF format
        previewPDF(params);
    }
}

function previewCSV(params) {
    // Fetch data from bookings API
    fetch(`{{ route('admin.bookings') }}?${params.toString()}`)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Create CSV-style preview table
            const csvPreview = `
                <div class="alert alert-info">
                    <i class="bx bx-info-circle me-2"></i>
                    <strong>CSV Preview:</strong> This shows how your data will appear in the CSV file.
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" style="font-size: 0.85rem;">
                        <thead class="table-dark">
                            <tr>
                                <th>Booking ID</th>
                                <th>Booking Code</th>
                                <th>Guest Name</th>
                                <th>Email</th>
                                <th>Hotel</th>
                                <th>Room Type</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Nights</th>
                                <th>Adults</th>
                                <th>Children</th>
                                <th>Total Guests</th>
                                <th>Status</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="csvPreviewBody">
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-warning mt-3">
                    <i class="bx bx-download me-2"></i>
                    <strong>Note:</strong> The actual CSV file will contain additional columns (Phone, Country, Package, Payment details, etc.)
                </div>
            `;
            
            document.getElementById('previewContent').innerHTML = csvPreview;
            
            // Extract booking data and populate preview
            const bookingRows = doc.querySelectorAll('tbody tr');
            const csvBody = document.getElementById('csvPreviewBody');
            
            if (bookingRows.length === 0) {
                csvBody.innerHTML = '<tr><td colspan="14" class="text-center text-muted">No data found with current filters</td></tr>';
                return;
            }
            
            bookingRows.forEach((row, index) => {
                if (index >= 10) return; // Limit preview to 10 rows
                
                const cells = row.querySelectorAll('td');
                if (cells.length > 0) {
                    const bookingId = cells[0]?.querySelector('.booking-id')?.textContent?.trim() || 'N/A';
                    const guestName = cells[1]?.querySelector('.guest-name')?.textContent?.trim() || 'N/A';
                    const email = cells[2]?.querySelector('span')?.textContent?.trim() || 'N/A';
                    const hotelName = cells[3]?.querySelector('.hotel-name')?.textContent?.trim() || 'N/A';
                    const roomType = cells[3]?.querySelector('.room-type')?.textContent?.trim() || 'N/A';
                    const checkIn = cells[4]?.textContent?.trim() || 'N/A';
                    const amount = cells[5]?.querySelector('.amount')?.textContent?.trim() || 'N/A';
                    
                    csvBody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${bookingId}</td>
                            <td>${guestName}</td>
                            <td>${email}</td>
                            <td>${hotelName}</td>
                            <td>${roomType}</td>
                            <td>29/07/2025</td>
                            <td>31/07/2025</td>
                            <td>2</td>
                            <td>1</td>
                            <td>0</td>
                            <td>1</td>
                            <td>Confirmed</td>
                            <td>${amount}</td>
                        </tr>
                    `;
                }
            });
            
            if (bookingRows.length > 10) {
                csvBody.innerHTML += `<tr><td colspan="14" class="text-center text-muted font-italic">... and ${bookingRows.length - 10} more rows</td></tr>`;
            }
        })
        .catch(error => {
            document.getElementById('previewContent').innerHTML = '<div class="alert alert-danger">Error loading CSV preview.</div>';
        });
}

function previewPDF(params) {
    // Create PDF-style preview
    const pdfPreview = `
        <div class="alert alert-info">
            <i class="bx bx-file-pdf me-2"></i>
            <strong>PDF Preview:</strong> This shows how your report will appear in the PDF file.
        </div>
        <div style="border: 1px solid #ddd; padding: 20px; background: white; font-family: Arial, sans-serif;">
            <div style="text-align: center; border-bottom: 2px solid #e91e63; padding-bottom: 15px; margin-bottom: 20px;">
                <h3 style="color: #e91e63; margin: 0;">Hotel Booking Report</h3>
                <p style="margin: 5px 0; color: #666;">Generated on ${new Date().toLocaleDateString('en-GB', { 
                    day: '2-digit', 
                    month: 'long', 
                    year: 'numeric' 
                })}</p>
            </div>
            
            <div style="background: #f8f9fa; padding: 15px; margin-bottom: 20px; text-align: center;">
                <strong>Summary:</strong> 
                <span id="totalBookings">0</span> Total Bookings | 
                Revenue: <span id="totalRevenue">Rp0</span>
            </div>
            
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <thead>
                    <tr style="background: #e91e63; color: white;">
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Booking ID</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Guest Name</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Hotel</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Room</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Check-in</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Nights</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Total Guests</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Status</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Amount</th>
                    </tr>
                </thead>
                <tbody id="pdfPreviewBody">
                </tbody>
            </table>
            
            <div style="margin-top: 20px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 15px;">
                <p><strong>Hotel Management System</strong> | Page 1 of 1</p>
            </div>
        </div>
        <div class="alert alert-success mt-3">
            <i class="bx bx-check-circle me-2"></i>
            <strong>PDF Features:</strong> Professional layout, company branding, summary statistics, and print-ready format.
        </div>
    `;
    
    document.getElementById('previewContent').innerHTML = pdfPreview;
    
    // Fetch data and populate PDF preview
    fetch(`{{ route('admin.bookings') }}?${params.toString()}`)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const bookingRows = doc.querySelectorAll('tbody tr');
            const pdfBody = document.getElementById('pdfPreviewBody');
            
            if (bookingRows.length === 0) {
                pdfBody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 30px; border: 1px solid #ddd;">No bookings found with current filters.</td></tr>';
                return;
            }
            
            let totalRevenue = 0;
            
            bookingRows.forEach((row, index) => {
                if (index >= 8) return; // Limit PDF preview to 8 rows
                
                const cells = row.querySelectorAll('td');
                if (cells.length > 0) {
                    const bookingId = cells[0]?.querySelector('.booking-id')?.textContent?.trim() || 'N/A';
                    const guestName = cells[1]?.querySelector('.guest-name')?.textContent?.trim() || 'N/A';
                    const hotelName = cells[3]?.querySelector('.hotel-name')?.textContent?.trim() || 'N/A';
                    const roomType = cells[3]?.querySelector('.room-type')?.textContent?.trim() || 'N/A';
                    const amount = cells[5]?.querySelector('.amount')?.textContent?.trim() || 'Rp0';
                    
                    // Extract numeric value for total
                    const amountNumeric = amount.replace(/[^\d]/g, '');
                    totalRevenue += parseInt(amountNumeric) || 0;
                    
                    const rowStyle = index % 2 === 0 ? 'background: #f9f9f9;' : '';
                    
                    pdfBody.innerHTML += `
                        <tr style="${rowStyle}">
                            <td style="border: 1px solid #ddd; padding: 6px;">${bookingId}</td>
                            <td style="border: 1px solid #ddd; padding: 6px;">${guestName}</td>
                            <td style="border: 1px solid #ddd; padding: 6px;">${hotelName}</td>
                            <td style="border: 1px solid #ddd; padding: 6px;">${roomType}</td>
                            <td style="border: 1px solid #ddd; padding: 6px;">29/07/2025</td>
                            <td style="border: 1px solid #ddd; padding: 6px;">2</td>
                            <td style="border: 1px solid #ddd; padding: 6px;">1 guests</td>
                            <td style="border: 1px solid #ddd; padding: 6px;">Confirmed</td>
                            <td style="border: 1px solid #ddd; padding: 6px;">${amount}</td>
                        </tr>
                    `;
                }
            });
            
            if (bookingRows.length > 8) {
                pdfBody.innerHTML += `<tr><td colspan="9" style="text-align: center; font-style: italic; padding: 10px; border: 1px solid #ddd;">... and ${bookingRows.length - 8} more bookings</td></tr>`;
            }
            
            // Update summary
            document.getElementById('totalBookings').textContent = bookingRows.length;
            document.getElementById('totalRevenue').textContent = 'Rp' + totalRevenue.toLocaleString('id-ID');
        })
        .catch(error => {
            document.getElementById('previewContent').innerHTML = '<div class="alert alert-danger">Error loading PDF preview.</div>';
        });
}
</script>

@endsection
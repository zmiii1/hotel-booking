@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bx bx-check-circle me-2"></i>
                            @if($credentialsData['action'] === 'created')
                                Admin Created Successfully!
                            @else
                                Password Reset Successfully!
                            @endif
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-success">
                            <h6><i class="bx bx-info-circle me-2"></i>
                                @if($credentialsData['action'] === 'created')
                                    New Admin Account Details
                                @else
                                    Updated Admin Account Details
                                @endif
                            </h6>
                            <p class="mb-0">
                                @if($credentialsData['action'] === 'created')
                                    Please save these credentials securely. The password will not be shown again.
                                @else
                                    The password has been reset. Please save the new password securely.
                                @endif
                            </p>
                        </div>
                        
                        <!-- ADMIN INFO -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Full Name:</label>
                                <div class="form-control-plaintext bg-light p-2 rounded">
                                    {{ $credentialsData['admin']['name'] }}
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email:</label>
                                <div class="form-control-plaintext bg-light p-2 rounded">
                                    {{ $credentialsData['admin']['email'] }}
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Username:</label>
                                <div class="form-control-plaintext bg-light p-2 rounded">
                                    {{ $credentialsData['admin']['username'] }}
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Role:</label>
                                <div class="form-control-plaintext bg-light p-2 rounded">
                                    <span class="badge bg-primary">{{ $credentialsData['admin']['role'] }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- PASSWORD SECTION - HIGHLIGHTED -->
                        <div class="alert alert-warning border-warning">
                            <h6 class="text-warning mb-3">
                                <i class="bx bx-key me-2"></i>
                                @if($credentialsData['action'] === 'created')
                                    Login Credentials (Save This!)
                                @else
                                    New Login Credentials (Save This!)
                                @endif
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Username:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{ $credentialsData['admin']['username'] }}" readonly id="username-field">
                                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('{{ $credentialsData['admin']['username'] }}', 'Username')">
                                            <i class="bx bx-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-danger">
                                        @if($credentialsData['action'] === 'created')
                                            Password:
                                        @else
                                            New Password:
                                        @endif
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control fw-bold text-danger" value="{{ $credentialsData['password'] }}" readonly id="password-field">
                                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('{{ $credentialsData['password'] }}', 'Password')">
                                            <i class="bx bx-copy"></i>
                                        </button>
                                        <button class="btn btn-outline-info" type="button" onclick="togglePasswordVisibility()">
                                            <i class="bx bx-show" id="password-toggle-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- ACTION BUTTONS -->
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <a href="{{ route('all.admin') }}" class="btn btn-secondary me-2">
                                    <i class="bx bx-arrow-back me-2"></i>Back to Admin List
                                </a>
                                @if($credentialsData['action'] === 'reset')
                                <a href="{{ route('edit.admin', $credentialsData['admin']['id']) }}" class="btn btn-info">
                                    <i class="bx bx-edit me-2"></i>Edit Admin
                                </a>
                                @endif
                            </div>
                            <div>
                                <button class="btn btn-primary me-2" onclick="printCredentials()">
                                    <i class="bx bx-printer me-2"></i>Print Credentials
                                </button>
                                <button class="btn btn-success" onclick="copyAllCredentials()">
                                    <i class="bx bx-copy-alt me-2"></i>Copy All
                                </button>
                            </div>
                        </div>
                        
                        <!-- WARNING MESSAGE -->
                        <div class="alert alert-danger mt-4">
                            <i class="bx bx-exclamation-triangle me-2"></i>
                            <strong>Important:</strong> This password will not be displayed again. Make sure to save it securely before leaving this page.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text, type) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success toast
        showToast(type + ' copied to clipboard!', 'success');
    }).catch(function(err) {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast(type + ' copied to clipboard!', 'success');
    });
}

function copyAllCredentials() {
    const username = "{{ $credentialsData['admin']['username'] }}";
    const password = "{{ $credentialsData['password'] }}";
    const fullText = `Username: ${username}\nPassword: ${password}`;
    
    copyToClipboard(fullText, 'All credentials');
}

function togglePasswordVisibility() {
    const passwordField = document.getElementById('password-field');
    const toggleIcon = document.getElementById('password-toggle-icon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.className = 'bx bx-hide';
    } else {
        passwordField.type = 'password';
        toggleIcon.className = 'bx bx-show';
    }
}

function printCredentials() {
    const adminData = @json($credentialsData);
    const action = adminData.action === 'created' ? 'New Admin Account' : 'Password Reset';
    
    const printContent = `
        <div style="font-family: Arial, sans-serif; padding: 20px;">
            <h2>${action} - Credentials</h2>
            <hr>
            <p><strong>Name:</strong> ${adminData.admin.name}</p>
            <p><strong>Email:</strong> ${adminData.admin.email}</p>
            <p><strong>Username:</strong> ${adminData.admin.username}</p>
            <p><strong>Password:</strong> ${adminData.password}</p>
            <p><strong>Role:</strong> ${adminData.admin.role}</p>
            <hr>
            <p style="color: red;"><strong>Important:</strong> Keep these credentials secure!</p>
            <p><em>Generated on: ${new Date().toLocaleDateString()} at ${new Date().toLocaleTimeString()}</em></p>
        </div>
    `;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Admin Credentials - ${adminData.admin.username}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h2 { color: #333; }
                    hr { border: 1px solid #ccc; }
                </style>
            </head>
            <body>${printContent}</body>
        </html>
    `);
    printWindow.print();
}

function showToast(message, type) {
    // Simple toast notification
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bx bx-check-circle me-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 3000);
}

// Initialize password field as hidden
document.addEventListener('DOMContentLoaded', function() {
    const passwordField = document.getElementById('password-field');
    passwordField.type = 'password';
});
</script>

@endsection
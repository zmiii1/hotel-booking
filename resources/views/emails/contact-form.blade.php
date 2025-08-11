<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            line-height: 1.6; 
            color: #333; 
            margin: 0; 
            padding: 0;
            background-color: #f4f4f4;
        }
        .container { 
            max-width: 600px; 
            margin: 20px auto; 
            background: white; 
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #E91E63, #F06292);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content { 
            padding: 30px; 
        }
        .field {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #E91E63;
        }
        .field-label {
            font-weight: bold;
            color: #E91E63;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .field-value {
            color: #333;
            font-size: 14px;
            word-wrap: break-word;
        }
        .message-content {
            background: white;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
            white-space: pre-wrap;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #666;
        }
        .action-buttons {
            margin: 20px 0;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 5px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }
        .btn-whatsapp {
            background: #25D366;
            color: white;
        }
        .btn-email {
            background: #E91E63;
            color: white;
        }
        .urgent {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .urgent strong {
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 New Contact Form Submission</h1>
            <p>Tanjung Lesung Resort Website</p>
        </div>
        
        <div class="content">
            <div class="urgent">
                <strong>⚡ Action Required:</strong> Please respond to this inquiry within 24 hours
            </div>
            
            <div class="field">
                <div class="field-label">👤 Customer Name</div>
                <div class="field-value">{{ $contactName ?? 'N/A' }}</div>
            </div>
            
            <div class="field">
                <div class="field-label">📧 Email Address</div>
                <div class="field-value">
                    <a href="mailto:{{ $contactEmail ?? '' }}">{{ $contactEmail ?? 'N/A' }}</a>
                </div>
            </div>
            
            <div class="field">
                <div class="field-label">📱 Phone Number</div>
                <div class="field-value">
                    @if(isset($contactPhone) && $contactPhone && $contactPhone !== 'Not provided')
                        <a href="tel:{{ $contactPhone }}">{{ $contactPhone }}</a>
                    @else
                        Not provided
                    @endif
                </div>
            </div>
            
            <div class="field">
                <div class="field-label">💬 Customer Message</div>
                <div class="field-value">
                    <div class="message-content">{{ $contactMessage ?? 'N/A' }}</div>
                </div>
            </div>
            
            <div class="field">
                <div class="field-label">🕒 Submitted At</div>
                <div class="field-value">{{ $submittedAt ?? 'N/A' }}</div>
            </div>
            
            <div class="field">
                <div class="field-label">🌐 IP Address</div>
                <div class="field-value">{{ $ipAddress ?? 'N/A' }}</div>
            </div>
            
            <div class="action-buttons">
                <a href="mailto:{{ $contactEmail ?? '' }}?subject=Re: Your inquiry to Tanjung Lesung Resort&body=Dear {{ $contactName ?? 'Customer' }},%0D%0A%0D%0AThank you for contacting Tanjung Lesung Resort.%0D%0A%0D%0A" 
                   class="btn btn-email">
                    📧 Reply via Email
                </a>
                
                @if(isset($contactPhone) && $contactPhone && $contactPhone !== 'Not provided')
                <a href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $contactPhone) }}?text=Hi {{ $contactName ?? 'there' }}, thank you for contacting Tanjung Lesung Resort. I'm responding to your inquiry..." 
                   class="btn btn-whatsapp" target="_blank">
                    💬 Reply via WhatsApp
                </a>
                @endif
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Tanjung Lesung Resort</strong><br>
            Special Economic Zone Tanjung Lesung<br>
            Pandeglang, Banten, Indonesia<br>
            <a href="mailto:info@tanjunglesung.com">info@tanjunglesung.com</a> | 
            <a href="tel:+6281187800100">+62 811-8780-0100</a></p>
            
            <p style="margin-top: 15px; font-size: 11px; color: #999;">
                This email was automatically generated from the contact form on tanjunglesung.com
            </p>
        </div>
    </div>
</body>
</html>
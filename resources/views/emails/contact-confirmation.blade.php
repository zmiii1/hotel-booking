<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for contacting us</title>
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
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content { 
            padding: 40px 30px; 
        }
        .greeting {
            font-size: 18px;
            color: #E91E63;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .message-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #E91E63;
            margin: 20px 0;
        }
        .message-summary h3 {
            margin: 0 0 10px 0;
            color: #E91E63;
            font-size: 16px;
        }
        .message-text {
            background: white;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
            font-style: italic;
            white-space: pre-wrap;
        }
        .contact-info {
            background: #e3f2fd;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
            text-align: center;
        }
        .contact-info h3 {
            margin: 0 0 15px 0;
            color: #1976d2;
        }
        .contact-buttons {
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
        .btn-phone {
            background: #2196F3;
            color: white;
        }
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px;
            background: #E91E63;
            color: white;
            border-radius: 50%;
            text-decoration: none;
            width: 40px;
            height: 40px;
            line-height: 20px;
        }
        .location-info {
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🙏 Thank You!</h1>
            <p>Your message has been received</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Dear {{ $customerName ?? 'Customer' }},
            </div>

            <!-- Update di bagian message summary juga -->
            <div class="message-summary">
                <h3>📝 Your Message Summary:</h3>
                <div class="message-text">{{ $customerMessage ?? 'Your message' }}</div>
                <p style="margin: 10px 0 0 0; font-size: 12px; color: #666;">
                    <strong>Submitted:</strong> {{ $submittedAt ?? 'Recently' }}
                </p>
            </div>
            </div>
            
            <h3>🕐 What happens next?</h3>
            <ul style="line-height: 1.8;">
                <li>Our customer service team will review your inquiry</li>
                <li>You'll receive a detailed response within <strong>24 hours</strong></li>
                <li>For urgent matters, please contact us directly using the options below</li>
            </ul>
            
            <div class="contact-info">
                <h3>📞 Need immediate assistance?</h3>
                <p>Our team is available to help you right away:</p>
                
                <div class="contact-buttons">
                    <a href="https://wa.me/6285255717166?text=Hi! I just submitted a contact form and need immediate assistance." 
                       class="btn btn-whatsapp" target="_blank">
                        💬 WhatsApp Us
                    </a>
                    <a href="tel:+6281187800100" class="btn btn-phone">
                        📞 Call Now
                    </a>
                </div>
                
                <p style="margin: 15px 0 0 0; font-size: 14px;">
                    <strong>Phone:</strong> +62 811-8780-0100<br>
                    <strong>Email:</strong> info@tanjunglesung.com<br>
                    <strong>WhatsApp:</strong> +62 852-5571-7166
                </p>
            </div>
            
            <p>We appreciate your interest in Tanjung Lesung Resort and look forward to helping you create unforgettable memories at our beautiful beachfront destination.</p>
            
            <p style="margin-top: 25px;">
                <strong>Warm regards,</strong><br>
                <span style="color: #E91E63; font-weight: 600;">The Tanjung Lesung Resort Team</span>
            </p>
        </div>
        
        <div class="footer">
            <div class="social-links">
                <a href="#" title="Facebook">📘</a>
                <a href="#" title="Instagram">📷</a>
                <a href="#" title="Twitter">🐦</a>
            </div>
            
            <div class="location-info">
                <p><strong>Tanjung Lesung Resort</strong><br>
                Special Economic Zone Tanjung Lesung<br>
                Pandeglang, Banten, Indonesia</p>
                
                <p style="margin-top: 15px;">
                    🌊 <em>"Where Paradise Meets Hospitality"</em> 🌊
                </p>
                
                <p style="margin-top: 20px; font-size: 12px; color: #999;">
                    This is an automated confirmation email. Please do not reply to this email.<br>
                    For inquiries, please contact us through the channels mentioned above.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
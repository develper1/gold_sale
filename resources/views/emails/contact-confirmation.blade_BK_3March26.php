<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thank you for contacting us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9ecef;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }
        .highlight {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Thank you for contacting us!</h2>
        <p>We have received your message and will get back to you soon.</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $name ?? 'Valued Customer' }},</p>
        
        <p>Thank you for reaching out to us. We have successfully received your message and our team will review it shortly.</p>
        
        <div class="highlight">
            <strong>What happens next?</strong><br>
            • We will review your message within 24 hours<br>
            • You will receive a detailed response within 48 hours<br>
            • If you have an urgent inquiry, please call us at (212) 470-7540
        </div>
        
        <p><strong>Your message details:</strong></p>
        <p><em>"{{ $message1 ?? 'No message provided' }}"</em></p>
        
        <p>If you need immediate assistance, please don't hesitate to contact us:</p>
        <ul>
            <li><strong>Phone:</strong> (212) 470-7540</li>
            <li><strong>Email:</strong> Sales@OasisMint.com</li>
        </ul>
    </div>
    
    <div class="footer">
        <p>Best regards,<br>
        The Oasis Mint Team</p>
        <p><small>This is an automated message. Please do not reply to this email.</small></p>
    </div>
</body>
</html>

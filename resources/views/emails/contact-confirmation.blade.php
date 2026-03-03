<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Contacting Oasis Mint</title>
    <style>
        body { margin: 0; padding: 0; background: #f5f7fb; }
        table { border-collapse: collapse; }
    </style>
</head>
<body style="margin:0;padding:0;background:#f5f7fb;">
<div style="background:#f5f7fb;padding:32px 0;margin:0;">
  <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="max-width:620px;margin:0 auto;background:#ffffff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.08);font-family:Arial,Helvetica,sans-serif;color:#2b2b2b;">
    <tr>
      <td style="padding:32px 32px 24px;text-align:center;border-top-left-radius:12px;border-top-right-radius:12px;">
        <a href="{{ url('/') }}" target="_blank" style="text-decoration:none;display:inline-block;">
          <img src="{{ url('assets/media/logo.png') }}" alt="Oasis Mint" width="180" style="display:block;margin:0 auto;max-width:180px;height:auto;">
        </a>
        <p style="margin:12px 0 0;font-size:15px;color:#6b7280;letter-spacing:0.5px;text-transform:uppercase;">Your Trusted Source for Gold, Silver & Platinum</p>
      </td>
    </tr>
    <tr>
      <td style="padding:0 32px 24px;text-align:center;">
        <div style="background:linear-gradient(135deg, #059669 0%, #047857 100%);border-radius:10px;padding:28px 24px;margin-bottom:24px;">
          <h1 style="margin:0 0 8px;font-size:24px;font-weight:bold;color:#ffffff;line-height:1.3;">✓ We Got Your Message!</h1>
          <p style="margin:0;font-size:16px;color:rgba(255,255,255,0.95);">Thank you for reaching out — we're on it.</p>
        </div>
        <p style="margin:0 0 16px;font-size:16px;line-height:1.6;color:#374151;">Dear {{ $name ?? 'Valued Customer' }},</p>
        <p style="margin:0 0 16px;font-size:16px;line-height:1.6;color:#374151;">Thank you for contacting <strong>Oasis Mint</strong>. We've received your message and our team will get back to you shortly.</p>
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:20px;margin:20px 0;text-align:left;">
          <p style="margin:0 0 12px;font-size:15px;font-weight:600;color:#166534;">What happens next?</p>
          <ul style="margin:0;padding-left:20px;font-size:15px;line-height:1.8;color:#374151;">
            <li>We'll review your message within 24 hours</li>
            <li>You'll receive a detailed response within 48 hours</li>
            <li>For urgent inquiries, call us at <strong>(212) 470-7540</strong></li>
          </ul>
        </div>
        <div style="background:#f8fafc;border-radius:8px;padding:16px;margin:20px 0;text-align:left;">
          <p style="margin:0 0 8px;font-size:13px;color:#6b7280;text-transform:uppercase;">Your message:</p>
          <p style="margin:0;font-size:15px;line-height:1.6;color:#374151;font-style:italic;">"{{ $message1 ?? 'No message provided' }}"</p>
        </div>
        <p style="margin:0 0 20px;font-size:15px;line-height:1.6;color:#374151;">Need immediate help?</p>
        <p style="margin:0 0 24px;font-size:15px;line-height:1.6;color:#374151;"><strong>Phone:</strong> (212) 470-7540 &nbsp;|&nbsp; <strong>Email:</strong> <a href="mailto:Sales@OasisMint.com" style="color:#2563eb;">Sales@OasisMint.com</a></p>
        <a href="{{ url('/') }}" target="_blank" style="display:inline-block;background:#2563eb;color:#ffffff!important;text-decoration:none;padding:14px 28px;border-radius:8px;font-weight:600;font-size:15px;">Visit Oasis Mint →</a>
      </td>
    </tr>
    <tr>
      <td style="padding:24px 32px 32px;text-align:center;border-bottom-left-radius:12px;border-bottom-right-radius:12px;background:#f9fafb;">
        <p style="margin:0;font-size:14px;color:#6b7280;">Best regards,<br><strong style="color:#111;">The Oasis Mint Team</strong></p>
        <p style="margin:12px 0 0;font-size:12px;color:#9ca3af;">This is an automated message. Please do not reply directly to this email.</p>
      </td>
    </tr>
  </table>
</div>
</body>
</html>

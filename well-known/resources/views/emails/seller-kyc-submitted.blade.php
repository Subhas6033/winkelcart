<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYC Submitted – WinkelKart</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f5f5f5; }
        .email-container { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%); color: white; padding: 40px 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 26px; font-weight: 700; }
        .header p { margin: 10px 0 0 0; font-size: 15px; opacity: 0.9; }
        .content { padding: 30px; }
        .greeting { font-size: 20px; font-weight: 600; color: #1565c0; margin-bottom: 15px; }
        .info-box { background: #e3f2fd; border-left: 4px solid #1565c0; padding: 15px 20px; border-radius: 0 8px 8px 0; margin: 20px 0; }
        .info-box p { margin: 0; color: #0d47a1; font-weight: 600; }
        .steps { margin: 15px 0; padding-left: 20px; }
        .steps li { padding: 4px 0; color: #555; }
        .footer { background: #f8f9fa; padding: 20px 30px; text-align: center; font-size: 13px; color: #888; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ $isResubmission ? '🔄 KYC Re-Submitted' : '📋 KYC Submitted' }}</h1>
            <p>{{ $isResubmission ? 'Your updated KYC is under review' : 'Your KYC is under review' }}</p>
        </div>
        <div class="content">
            <p class="greeting">Hello {{ $seller->name }},</p>

            @if($isResubmission)
                <p>We have received your <strong>updated KYC details</strong>. Our admin team will review your re-submission and notify you of the outcome.</p>
            @else
                <p>Thank you for submitting your KYC details on WinkelKart. Our admin team will review your documents and notify you once verification is complete.</p>
            @endif

            <div class="info-box">
                <p>⏳ Verification typically takes 1–2 business days.</p>
            </div>

            <p><strong>What happens next:</strong></p>
            <ul class="steps">
                <li>Our admin team reviews your submitted documents</li>
                <li>You will receive an email once your KYC is approved or if any action is needed</li>
                <li>Once approved, you can start listing products immediately</li>
            </ul>

            <p style="color: #666; margin-top: 20px;">If you have any questions, contact our support team at <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} WinkelKart. All rights reserved.</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

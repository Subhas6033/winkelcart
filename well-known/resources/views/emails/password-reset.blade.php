<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - WinkelKart</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0fdf4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 40px rgba(22, 163, 74, 0.15);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #16a34a 0%, #15803d 50%, #14532d 100%); padding: 40px 40px 30px; text-align: center;">
                            <img src="{{ asset('assets/images/logo/Winkel_Shop__2_-removebg-preview.png') }}" alt="WinkelKart" style="max-width: 160px; height: auto; filter: brightness(0) invert(1);">
                            <h1 style="color: #ffffff; font-size: 28px; font-weight: 700; margin: 20px 0 0; letter-spacing: -0.5px;">Password Reset Request</h1>
                        </td>
                    </tr>
                    
                    <!-- Key Icon -->
                    <tr>
                        <td align="center" style="padding: 30px 40px 0;">
                            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
                                </svg>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 20px 40px 30px;">
                            <p style="color: #475569; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                                Hello <strong style="color: #0f172a;">{{ $user->name }}</strong>,
                            </p>
                            <p style="color: #475569; font-size: 16px; line-height: 1.6; margin-bottom: 25px;">
                                We received a request to reset the password for your WinkelKart account. Click the button below to create a new password:
                            </p>
                            
                            <!-- CTA Button -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $resetUrl }}" style="display: inline-block; padding: 16px 40px; background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 700; border-radius: 12px; box-shadow: 0 6px 20px rgba(22, 163, 74, 0.35);">
                                            Reset Password →
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- URL fallback -->
                            <p style="color: #94a3b8; font-size: 13px; line-height: 1.6; margin-bottom: 20px;">
                                If the button doesn't work, copy and paste this link into your browser:
                            </p>
                            <p style="background: #f8fafc; padding: 12px 16px; border-radius: 8px; font-size: 13px; color: #16a34a; word-break: break-all; border: 1px solid #e2e8f0;">
                                {{ $resetUrl }}
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Warning Box -->
                    <tr>
                        <td style="padding: 0 40px 30px;">
                            <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px 20px; border-radius: 0 8px 8px 0;">
                                <p style="color: #92400e; font-size: 14px; line-height: 1.6; margin: 0;">
                                    <strong>⚠️ Important:</strong> This link expires in <strong>60 minutes</strong>. If you didn't request a password reset, you can safely ignore this email.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Security Tips -->
                    <tr>
                        <td style="padding: 0 40px 30px;">
                            <p style="color: #64748b; font-size: 14px; font-weight: 600; margin-bottom: 12px;">
                                🔐 Password Tips:
                            </p>
                            <ul style="color: #64748b; font-size: 14px; line-height: 1.8; margin: 0; padding-left: 20px;">
                                <li>Use at least 6 characters</li>
                                <li>Mix letters, numbers, and symbols</li>
                                <li>Avoid using personal information</li>
                                <li>Don't reuse passwords from other sites</li>
                            </ul>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: #f8fafc; padding: 30px 40px; border-top: 1px solid #e2e8f0;">
                            <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="text-align: center;">
                                        <p style="color: #64748b; font-size: 14px; margin: 0 0 8px;">
                                            Need help? Contact us at
                                            <a href="mailto:support@winkelkart.com" style="color: #16a34a; text-decoration: none; font-weight: 600;">support@winkelkart.com</a>
                                        </p>
                                        <p style="color: #94a3b8; font-size: 12px; margin: 15px 0 0;">
                                            © {{ date('Y') }} WinkelKart — A UNIT OF SRD TECHNOLOGIES INDIA<br>
                                            All rights reserved.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

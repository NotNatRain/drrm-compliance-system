<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User verification</title>
</head>
<body style="margin:0; padding:0; background:#f3f4f6;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6; padding: 24px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="width:600px; max-width:600px;">
                <tr>
                    <td style="padding: 12px 8px 18px 8px;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#ffffff; border-radius:14px; overflow:hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                            <tr>
                                <td style="padding: 18px 20px; background: #0b4a8b;">
                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="72" style="width:72px; vertical-align:middle;">
                                                <img src="cid:{{ $drrmLogoCid ?? 'drrm-logo' }}" alt="DRRM Logo" style="height:54px; width:auto; display:block; background:#ffffff; border-radius:10px; padding:6px;">
                                            </td>
                                            <td style="vertical-align:middle; padding: 0 12px; color:#ffffff;">
                                                <div style="font-family: Arial, Helvetica, sans-serif; font-weight:700; font-size:14px; letter-spacing:0.2px;">
                                                    User verification
                                                </div>
                                                <div style="font-family: Arial, Helvetica, sans-serif; font-size:12px; opacity:0.95; margin-top:2px;">
                                                    Department of Education (DepEd) — Disaster Risk Reduction and Management (DRRM)
                                                </div>
                                            </td>
                                            <td width="72" style="width:72px; vertical-align:middle;" align="right">
                                                <img src="cid:{{ $depedLogoCid ?? 'deped-logo' }}" alt="DepEd Logo" style="height:54px; width:auto; display:block; background:#ffffff; border-radius:10px; padding:6px;">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 22px 20px 6px 20px;">
                                    <div style="font-family: Arial, Helvetica, sans-serif; font-size:14px; color:#111827; line-height:1.5;">
                                        We received a request to reset the password for:
                                        <div style="margin-top:6px; font-weight:700;">{{ $recipientEmail }}</div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 12px 20px;">
                                    <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px; padding:16px; text-align:center;">
                                        <div style="font-family: Arial, Helvetica, sans-serif; font-size:12px; color:#6b7280; margin-bottom:8px;">
                                            Your 6-digit verification code
                                        </div>
                                        <div style="font-family: Arial, Helvetica, sans-serif; font-size:30px; font-weight:800; letter-spacing:8px; color:#111827;">
                                            {{ $code }}
                                        </div>
                                        <div style="font-family: Arial, Helvetica, sans-serif; font-size:12px; color:#6b7280; margin-top:10px;">
                                            This code expires in 60 minutes. Do not share this code with anyone.
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 6px 20px 18px 20px;">
                                    <table role="presentation" cellspacing="0" cellpadding="0" align="center">
                                        <tr>
                                            <td style="border-radius:10px; background:#0b4a8b;">
                                                <a href="{{ $verifyUrl }}" style="display:inline-block; padding:12px 18px; font-family: Arial, Helvetica, sans-serif; font-weight:700; font-size:14px; color:#ffffff; text-decoration:none;">
                                                    Verify code
                                                </a>
                                            </td>
                                        </tr>
                                    </table>

                                    <div style="font-family: Arial, Helvetica, sans-serif; font-size:12px; color:#6b7280; line-height:1.5; margin-top:16px;">
                                        If you did not request a password reset, you can safely ignore this email.
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 14px 20px; background:#f9fafb; border-top:1px solid #e5e7eb;">
                                    <div style="font-family: Arial, Helvetica, sans-serif; font-size:11px; color:#6b7280; line-height:1.5;">
                                        This is an automated message from the DepEd DRRM Compliance System. Please do not reply to this email.
                                    </div>
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


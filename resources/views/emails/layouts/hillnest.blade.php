<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title ?? 'HillNest' }}</title>
</head>
<body style="margin:0;padding:0;background-color:#FAF5EC;font-family:Arial,Helvetica,sans-serif;color:#2A1F14;-webkit-text-size-adjust:100%;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#FAF5EC;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px;background:#FFFFFF;border:1px solid #E8D9BC;border-radius:18px;overflow:hidden;box-shadow:0 12px 32px rgba(77,55,24,0.08);">
                    <tr>
                        <td style="background:#1E3B2F;padding:28px 32px;text-align:center;">
                            <img src="{{ $logoUrl }}" alt="HillNest" width="92" style="display:block;margin:0 auto 10px;border:0;">
                            <p style="margin:0;color:#E8C47A;font-size:11px;font-weight:700;letter-spacing:3px;text-transform:uppercase;">Pure Himalayan Ghee</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px 32px 28px;">
                            @yield('content')
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#FAF5EC;padding:22px 32px;text-align:center;border-top:1px solid #E8D9BC;">
                            <p style="margin:0 0 6px;color:#5C4A34;font-size:14px;font-weight:600;">HillNest</p>
                            <p style="margin:0 0 10px;color:#8A7560;font-size:13px;line-height:1.6;">Pure A2 Bilona Ghee from Upper Shimla</p>
                            <p style="margin:0;color:#8A7560;font-size:12px;">&copy; {{ date('Y') }} HillNest. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

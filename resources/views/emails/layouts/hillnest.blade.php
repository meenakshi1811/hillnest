<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title ?? 'HillNest' }}</title>
</head>
<body style="margin:0;padding:0;background-color:#F4EDE0;font-family:Arial,Helvetica,sans-serif;color:#2A1F14;-webkit-text-size-adjust:100%;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;">{{ $preheader ?? 'HillNest — Pure A2 Bilona Ghee from Upper Shimla' }}</div>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#F4EDE0;padding:40px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px;background:#FFFFFF;border:1px solid #E8D9BC;border-radius:20px;overflow:hidden;box-shadow:0 16px 40px rgba(30,59,47,0.08);">
                    <tr>
                        <td style="height:5px;background:linear-gradient(90deg,#C9973A 0%,#E8C47A 50%,#C9973A 100%);font-size:0;line-height:0;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="padding:36px 40px 28px;text-align:center;background:#FFFCF7;">
                            <a href="{{ $shopUrl ?? url('/') }}" style="text-decoration:none;display:inline-block;">
                                <img src="{{ $logoUrl }}" alt="HillNest" width="180" height="48" style="display:block;margin:0 auto;border:0;max-width:180px;height:auto;">
                            </a>
                            <p style="margin:14px 0 0;color:#8A7560;font-size:12px;font-weight:600;letter-spacing:2.4px;text-transform:uppercase;">Upper Shimla &middot; Pure A2 Bilona</p>
                            <table role="presentation" width="72" cellspacing="0" cellpadding="0" align="center" style="margin:20px auto 0;">
                                <tr>
                                    <td style="height:2px;background:#E8D9BC;font-size:0;line-height:0;border-radius:999px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 40px 36px;">
                            @yield('content')
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#FAF5EC;padding:28px 40px;text-align:center;border-top:1px solid #E8D9BC;">
                            <p style="margin:0 0 8px;color:#1E3B2F;font-size:15px;font-weight:700;">HillNest</p>
                            <p style="margin:0 0 14px;color:#8A7560;font-size:13px;line-height:1.65;">Pure A2 Bilona Ghee from Upper Shimla, Himachal Pradesh</p>
                            <p style="margin:0 0 14px;">
                                <a href="{{ $shopUrl ?? url('/shop') }}" style="color:#C9973A;font-size:13px;font-weight:700;text-decoration:none;">Shop ghee</a>
                                <span style="color:#D4C4A8;margin:0 8px;">&middot;</span>
                                <a href="{{ url('/about') }}" style="color:#C9973A;font-size:13px;font-weight:700;text-decoration:none;">Our story</a>
                            </p>
                            <p style="margin:0;color:#A8957A;font-size:12px;line-height:1.5;">&copy; {{ date('Y') }} HillNest. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

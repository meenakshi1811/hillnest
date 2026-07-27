@extends('emails.layouts.hillnest')

@section('content')
    <p style="margin:0 0 10px;color:#C9973A;font-size:11px;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;">Password reset</p>
    <h1 style="margin:0;color:#1E3B2F;font-size:32px;line-height:1.15;font-weight:700;">Reset your password</h1>
    <p style="margin:16px 0 0;color:#5C4A34;font-size:16px;line-height:1.7;">
        Hi {{ $user->name }}, we received a request to reset the password for your Hillnest account. Click the button below to choose a new password.
    </p>

    <table role="presentation" cellspacing="0" cellpadding="0" style="margin-top:28px;">
        <tr>
            <td style="border-radius:999px;background:#1E3B2F;">
                <a href="{{ $resetUrl }}" style="display:inline-block;padding:14px 28px;color:#F5E8C8;font-size:15px;font-weight:700;text-decoration:none;">Reset password</a>
            </td>
        </tr>
    </table>

    <p style="margin:18px 0 0;color:#8A7560;font-size:14px;line-height:1.6;">
        This link expires in 60 minutes. If you did not request a password reset, you can safely ignore this email.
    </p>
@endsection

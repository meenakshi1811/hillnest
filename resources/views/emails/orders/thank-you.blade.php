@extends('emails.layouts.hillnest')

@section('content')
    <p style="margin:0 0 10px;color:#C9973A;font-size:11px;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;">Order confirmed</p>
    <h1 style="margin:0;color:#1E3B2F;font-size:32px;line-height:1.15;font-weight:700;">Thank you, {{ $order->customer_name }}!</h1>
    <p style="margin:16px 0 0;color:#5C4A34;font-size:16px;line-height:1.7;">
        We have received your order and payment successfully. Your HillNest ghee is being prepared with care and will be shipped to you soon.
    </p>

    @include('emails.orders.partials.details')

    <table role="presentation" cellspacing="0" cellpadding="0" style="margin-top:28px;">
        <tr>
            <td style="border-radius:999px;background:#1E3B2F;">
                <a href="{{ $shopUrl }}" style="display:inline-block;padding:14px 28px;color:#F5E8C8;font-size:15px;font-weight:700;text-decoration:none;">Shop More</a>
            </td>
        </tr>
    </table>

    <p style="margin:18px 0 0;color:#8A7560;font-size:14px;line-height:1.6;">
        You can also
        <a href="{{ $orderUrl }}" style="color:#1E3B2F;font-weight:700;text-decoration:underline;">view your order details</a>
        anytime from your account.
    </p>
@endsection

@extends('emails.layouts.hillnest')

@section('content')
    <p style="margin:0 0 10px;color:#C9973A;font-size:11px;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;">New order alert</p>
    <h1 style="margin:0;color:#1E3B2F;font-size:30px;line-height:1.15;font-weight:700;">A new paid order has been received</h1>
    <p style="margin:16px 0 0;color:#5C4A34;font-size:16px;line-height:1.7;">
        Order <strong style="color:#1E3B2F;">{{ $order->order_number }}</strong> was placed by
        <strong style="color:#1E3B2F;">{{ $order->customer_name }}</strong> and payment is confirmed.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:22px;">
        <tr>
            <td style="width:50%;padding:14px 16px;background:#FAF5EC;border:1px solid #E8D9BC;border-radius:12px 0 0 12px;">
                <p style="margin:0;color:#8A7560;font-size:12px;text-transform:uppercase;letter-spacing:1.5px;">Customer email</p>
                <p style="margin:6px 0 0;color:#1E3B2F;font-size:14px;font-weight:700;">{{ $order->customer_email }}</p>
            </td>
            <td style="width:50%;padding:14px 16px;background:#FAF5EC;border:1px solid #E8D9BC;border-left:0;border-radius:0 12px 12px 0;">
                <p style="margin:0;color:#8A7560;font-size:12px;text-transform:uppercase;letter-spacing:1.5px;">Order total</p>
                <p style="margin:6px 0 0;color:#1E3B2F;font-size:20px;font-weight:700;">&#8377;{{ number_format($order->total, 0) }}</p>
            </td>
        </tr>
    </table>

    @include('emails.orders.partials.details')

    <table role="presentation" cellspacing="0" cellpadding="0" style="margin-top:28px;">
        <tr>
            <td style="border-radius:999px;background:#1E3B2F;">
                <a href="{{ $adminOrderUrl }}" style="display:inline-block;padding:14px 28px;color:#F5E8C8;font-size:15px;font-weight:700;text-decoration:none;">Open order in admin</a>
            </td>
        </tr>
    </table>
@endsection

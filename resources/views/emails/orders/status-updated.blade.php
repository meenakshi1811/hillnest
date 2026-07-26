@extends('emails.layouts.hillnest')

@section('content')
    <p style="margin:0 0 10px;color:#C9973A;font-size:11px;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;">Order update</p>
    <h1 style="margin:0;color:#1E3B2F;font-size:30px;line-height:1.15;font-weight:700;">Hi {{ $order->customer_name }}, your order status has changed</h1>
    <p style="margin:16px 0 0;color:#5C4A34;font-size:16px;line-height:1.7;">
        {{ $order->statusUpdateMessage() }}
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:24px;border:1px solid #E8D9BC;border-radius:14px;overflow:hidden;">
        <tr>
            <td style="padding:22px 20px;background:#FAF5EC;text-align:center;">
                <p style="margin:0 0 12px;color:#8A7560;font-size:12px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;">New status</p>
                @include('emails.orders.partials.status-badge', [
                    'status' => $order->status,
                    'label' => $order->status_label,
                ])
                <p style="margin:16px 0 0;color:#5C4A34;font-size:14px;line-height:1.6;">
                    Order <strong style="color:#1E3B2F;">{{ $order->order_number }}</strong>
                    updated from {{ $previousStatusLabel }} to {{ $order->status_label }}.
                </p>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:18px;border:1px solid #E8D9BC;border-radius:14px;overflow:hidden;">
        <tr>
            <td style="padding:18px 20px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="padding:6px 0;color:#8A7560;font-size:13px;">Order total</td>
                        <td align="right" style="padding:6px 0;color:#1E3B2F;font-size:16px;font-weight:700;">&#8377;{{ number_format($order->total, 0) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#8A7560;font-size:13px;">Items</td>
                        <td align="right" style="padding:6px 0;color:#5C4A34;font-size:14px;">{{ $itemCount }} {{ $itemCount === 1 ? 'item' : 'items' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#8A7560;font-size:13px;">Delivery to</td>
                        <td align="right" style="padding:6px 0;color:#5C4A34;font-size:14px;">{{ $order->city }}, {{ $order->pincode }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table role="presentation" cellspacing="0" cellpadding="0" style="margin-top:28px;">
        <tr>
            <td style="border-radius:999px;background:#1E3B2F;">
                <a href="{{ $orderUrl }}" style="display:inline-block;padding:14px 28px;color:#F5E8C8;font-size:15px;font-weight:700;text-decoration:none;">View Order Details</a>
            </td>
        </tr>
    </table>

    <p style="margin:18px 0 0;color:#8A7560;font-size:14px;line-height:1.6;">
        If you have any questions about your order, simply reply to this email and our team will be happy to help.
    </p>
@endsection

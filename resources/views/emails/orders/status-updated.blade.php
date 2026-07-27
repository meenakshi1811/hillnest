@extends('emails.layouts.hillnest')

@section('content')
    @include('emails.partials.eyebrow', ['label' => 'Order update'])

    @include('emails.partials.heading', [
        'text' => 'Hi '.$order->customer_name.', your order status has changed',
        'size' => '26',
    ])

    @include('emails.partials.lede', ['text' => e($order->statusUpdateMessage())])

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:26px;border:1px solid #E8D9BC;border-radius:16px;overflow:hidden;background:#FFFCF7;">
        <tr>
            <td style="padding:24px 22px;text-align:center;">
                <p style="margin:0 0 14px;color:#8A7560;font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;">New status</p>
                @include('emails.orders.partials.status-badge', [
                    'status' => $order->status,
                    'label' => $order->status_label,
                ])
                <p style="margin:18px 0 0;color:#5C4A34;font-size:15px;line-height:1.65;">
                    Order <strong style="color:#1E3B2F;">{{ $order->order_number }}</strong>
                    updated from {{ $previousStatusLabel }} to {{ $order->status_label }}.
                </p>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:16px;border:1px solid #E8D9BC;border-radius:16px;overflow:hidden;">
        <tr>
            <td style="padding:20px 22px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="padding:7px 0;color:#8A7560;font-size:13px;">Order total</td>
                        <td align="right" style="padding:7px 0;color:#1E3B2F;font-size:17px;font-weight:700;">&#8377;{{ number_format($order->total, 0) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:7px 0;color:#8A7560;font-size:13px;">Items</td>
                        <td align="right" style="padding:7px 0;color:#5C4A34;font-size:14px;">{{ $itemCount }} {{ $itemCount === 1 ? 'item' : 'items' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:7px 0;color:#8A7560;font-size:13px;">Delivery to</td>
                        <td align="right" style="padding:7px 0;color:#5C4A34;font-size:14px;">{{ $order->city }}, {{ $order->pincode }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @include('emails.partials.button', [
        'url' => $orderUrl,
        'label' => 'View order details',
    ])

    @include('emails.partials.note', ['text' => 'If you have any questions about your order, simply reply to this email and our team will be happy to help.'])
@endsection

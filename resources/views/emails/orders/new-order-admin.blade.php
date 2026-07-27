@extends('emails.layouts.hillnest')

@section('content')
    @include('emails.partials.eyebrow', ['label' => 'New order alert'])

    @include('emails.partials.heading', [
        'text' => 'A new paid order has been received',
        'size' => '26',
    ])

    @include('emails.partials.lede', ['text' => 'Order <strong style="color:#1E3B2F;">'.$order->order_number.'</strong> was placed by <strong style="color:#1E3B2F;">'.e($order->customer_name).'</strong> and payment is confirmed.'])

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:24px;">
        <tr>
            <td style="width:50%;padding:16px 18px;background:#FFFCF7;border:1px solid #E8D9BC;border-radius:14px 0 0 14px;">
                <p style="margin:0;color:#8A7560;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1.8px;">Customer email</p>
                <p style="margin:8px 0 0;color:#1E3B2F;font-size:14px;font-weight:700;line-height:1.4;">{{ $order->customer_email }}</p>
            </td>
            <td style="width:50%;padding:16px 18px;background:#FFFCF7;border:1px solid #E8D9BC;border-left:0;border-radius:0 14px 14px 0;">
                <p style="margin:0;color:#8A7560;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1.8px;">Order total</p>
                <p style="margin:8px 0 0;color:#1E3B2F;font-size:22px;font-weight:700;">&#8377;{{ number_format($order->total, 0) }}</p>
            </td>
        </tr>
    </table>

    @include('emails.orders.partials.details')

    @include('emails.partials.button', [
        'url' => $adminOrderUrl,
        'label' => 'Open order in admin',
    ])
@endsection

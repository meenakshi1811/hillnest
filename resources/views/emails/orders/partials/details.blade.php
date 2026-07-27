<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:26px;border:1px solid #E8D9BC;border-radius:16px;overflow:hidden;">
    <tr>
        <td style="padding:18px 22px;background:#FFFCF7;border-bottom:1px solid #E8D9BC;">
            <p style="margin:0;color:#C9973A;font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;">Order {{ $order->order_number }}</p>
            <p style="margin:8px 0 0;color:#5C4A34;font-size:14px;line-height:1.5;">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
        </td>
    </tr>
    @foreach($order->items as $item)
    <tr>
        <td style="padding:16px 22px;border-bottom:1px solid #F0E8D6;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="vertical-align:top;">
                        <p style="margin:0;color:#1E3B2F;font-size:16px;font-weight:700;line-height:1.35;">{{ $item->product_name }}</p>
                        @if($item->product_size)
                        <p style="margin:5px 0 0;color:#8A7560;font-size:13px;">{{ $item->product_size }}</p>
                        @endif
                        <p style="margin:6px 0 0;color:#8A7560;font-size:13px;">Qty {{ $item->quantity }} &middot; &#8377;{{ number_format($item->unit_price, 0) }} each</p>
                    </td>
                    <td align="right" style="vertical-align:top;white-space:nowrap;color:#1E3B2F;font-size:16px;font-weight:700;">
                        &#8377;{{ number_format($item->line_total, 0) }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @endforeach
    <tr>
        <td style="padding:20px 22px;background:#FFFFFF;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="padding:5px 0;color:#5C4A34;font-size:14px;">Subtotal</td>
                    <td align="right" style="padding:5px 0;color:#2A1F14;font-size:14px;">&#8377;{{ number_format($order->subtotal, 0) }}</td>
                </tr>
                @if($order->hasCoupon())
                <tr>
                    <td style="padding:5px 0;color:#C9973A;font-size:14px;">Coupon ({{ $order->coupon_code }})</td>
                    <td align="right" style="padding:5px 0;color:#C9973A;font-size:14px;">-&#8377;{{ number_format($order->discount_amount, 0) }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding:5px 0;color:#5C4A34;font-size:14px;">Shipping</td>
                    <td align="right" style="padding:5px 0;color:#2A1F14;font-size:14px;">{{ $order->shipping_fee > 0 ? '&#8377;'.number_format($order->shipping_fee, 0) : 'FREE' }}</td>
                </tr>
                <tr>
                    <td style="padding:14px 0 0;color:#1E3B2F;font-size:16px;font-weight:700;border-top:1px solid #E8D9BC;">Total</td>
                    <td align="right" style="padding:14px 0 0;color:#1E3B2F;font-size:22px;font-weight:700;border-top:1px solid #E8D9BC;">&#8377;{{ number_format($order->total, 0) }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:16px;border:1px solid #E8D9BC;border-radius:16px;overflow:hidden;">
    <tr>
        <td style="padding:20px 22px;background:#FFFCF7;">
            <p style="margin:0 0 10px;color:#C9973A;font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;">Delivery address</p>
            <p style="margin:0;color:#1E3B2F;font-size:15px;font-weight:700;">{{ $order->customer_name }}</p>
            <p style="margin:10px 0 0;color:#5C4A34;font-size:14px;line-height:1.7;">{{ $order->shipping_address }}<br>{{ $order->city }}, {{ $order->state }} &mdash; {{ $order->pincode }}</p>
            <p style="margin:12px 0 0;color:#5C4A34;font-size:14px;">Phone: {{ $order->customer_phone }}</p>
        </td>
    </tr>
</table>

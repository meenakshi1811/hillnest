@extends('emails.layouts.hillnest')

@section('content')
    @if($isTest)
        <p style="margin:0 0 18px;padding:10px 14px;background:#FFF4E5;border:1px solid #F2C98A;border-radius:10px;color:#8A5A12;font-size:13px;line-height:1.5;">
            This is a test email. Customers will see their own name in place of the sample name.
        </p>
    @endif

    @include('emails.partials.heading', ['text' => $heading, 'size' => '26'])

    <div style="margin-top:18px;">{!! $bodyHtml !!}</div>

    @if($coupon)
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:8px 0 4px;">
            <tr>
                <td align="center" style="padding:22px 20px;background:#FFFCF7;border:2px dashed #C9973A;border-radius:16px;">
                    <p style="margin:0;color:#8A7560;font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;">Your coupon code</p>
                    <p style="margin:10px 0 0;color:#1E3B2F;font-size:30px;font-weight:700;letter-spacing:3px;">{{ $coupon->code }}</p>
                    <p style="margin:8px 0 0;color:#5C4A34;font-size:14px;">
                        {{ $coupon->value_label }} off your order
                        @if($coupon->expires_at)
                            {{-- Coupons stop working at the start of their expiry date. --}}
                            &middot; valid till {{ $coupon->expires_at->copy()->subSecond()->format('d M Y') }}
                        @endif
                    </p>
                </td>
            </tr>
        </table>
    @endif

    @if($buttonLabel && $buttonUrl)
        @include('emails.partials.button', ['url' => $buttonUrl, 'label' => $buttonLabel])
    @endif
@endsection

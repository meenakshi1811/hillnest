@extends('emails.layouts.hillnest')

@section('content')
    @include('emails.partials.eyebrow', ['label' => 'Website contact form'])

    @include('emails.partials.heading', [
        'text' => 'New message from '.$contactMessage->name,
        'size' => '26',
    ])

    @include('emails.partials.lede', ['text' => 'Someone sent a message from the Contact page. Reply to this email to answer them directly.'])

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:24px;">
        <tr>
            <td style="width:50%;padding:16px 18px;background:#FFFCF7;border:1px solid #E8D9BC;border-radius:14px 0 0 14px;">
                <p style="margin:0;color:#8A7560;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1.8px;">Email</p>
                <p style="margin:8px 0 0;color:#1E3B2F;font-size:14px;font-weight:700;line-height:1.4;">{{ $contactMessage->email }}</p>
            </td>
            <td style="width:50%;padding:16px 18px;background:#FFFCF7;border:1px solid #E8D9BC;border-left:0;border-radius:0 14px 14px 0;">
                <p style="margin:0;color:#8A7560;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1.8px;">Phone</p>
                <p style="margin:8px 0 0;color:#1E3B2F;font-size:14px;font-weight:700;line-height:1.4;">{{ $contactMessage->phone ?: '—' }}</p>
            </td>
        </tr>
    </table>

    @if($contactMessage->order_number)
        @include('emails.partials.note', ['text' => 'Order number: <strong style="color:#1E3B2F;">'.e($contactMessage->order_number).'</strong>'])
    @endif

    <div style="margin-top:20px;padding:18px 20px;background:#FFFCF7;border:1px solid #E8D9BC;border-radius:14px;color:#5C4A34;font-size:15px;line-height:1.7;">{!! nl2br(e($contactMessage->message)) !!}</div>

    @include('emails.partials.button', [
        'url' => $adminMessageUrl,
        'label' => 'Open in admin',
    ])
@endsection

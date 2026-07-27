@extends('emails.layouts.hillnest')

@section('content')
    @include('emails.partials.eyebrow', ['label' => 'Welcome aboard'])

    @include('emails.partials.heading', ['text' => 'Welcome to Hillnest, '.e($user->name).'!'])

    @include('emails.partials.lede', ['text' => 'Your account is ready. You can now track orders, save your details for faster checkout, and reorder your favourite pure bilona ghee anytime.'])

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:24px;border:1px solid #E8D9BC;border-radius:16px;overflow:hidden;">
        <tr>
            <td style="padding:20px 22px;background:#FFFCF7;">
                <p style="margin:0 0 14px;color:#C9973A;font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;">What you can do</p>
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="padding:8px 0;color:#5C4A34;font-size:15px;line-height:1.6;">&#10003;&nbsp; Track every order from the hills to your home</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;color:#5C4A34;font-size:15px;line-height:1.6;">&#10003;&nbsp; Reorder your favourites in a few clicks</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;color:#5C4A34;font-size:15px;line-height:1.6;">&#10003;&nbsp; Manage your profile and delivery details</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @include('emails.partials.button', [
        'url' => $shopUrl,
        'label' => 'Start shopping',
    ])

    @include('emails.partials.note', ['text' => 'Visit <a href="'.$accountUrl.'" style="color:#1E3B2F;font-weight:700;text-decoration:underline;">your account</a> anytime to view orders and update your profile.'])
@endsection

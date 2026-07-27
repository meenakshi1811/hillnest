@extends('emails.layouts.hillnest')

@section('content')
    @include('emails.partials.eyebrow', ['label' => 'Order confirmed'])

    @include('emails.partials.heading', ['text' => 'Thank you, '.$order->customer_name.'!'])

    @include('emails.partials.lede', ['text' => 'We have received your order and payment successfully. Your HillNest ghee is being prepared with care and will be shipped to you soon.'])

    @include('emails.orders.partials.details')

    @include('emails.partials.button', [
        'url' => $shopUrl,
        'label' => 'Shop more',
    ])

    @include('emails.partials.note', ['text' => 'You can also <a href="'.$orderUrl.'" style="color:#1E3B2F;font-weight:700;text-decoration:underline;">view your order details</a> anytime from your account.'])
@endsection

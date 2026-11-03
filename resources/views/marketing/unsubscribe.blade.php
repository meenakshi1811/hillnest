@extends('layouts.app')

@section('title', 'Email Preferences — HillNest')
@section('robots', 'noindex')

@section('content')
@include('partials.legal-header', [
    'title' => $isUnsubscribed ? 'You\'re unsubscribed' : 'Unsubscribe from offers',
    'eyebrow' => 'Email preferences',
    'updated' => false,
])

            <div class="legal-content">
                @if($isUnsubscribed)
                    <p>We won't send offer and discount emails to <strong>{{ $user->email }}</strong> any more.</p>
                    <p>You'll still receive emails about your orders, such as confirmations and delivery updates.</p>
                @else
                    <p>Stop receiving offer and discount emails at <strong>{{ $user->email }}</strong>?</p>
                    <p>You'll still receive emails about your orders, such as confirmations and delivery updates.</p>
                    <form method="POST" action="{{ request()->fullUrl() }}" style="margin-top:24px">
                        <input type="hidden" name="confirm" value="1">
                        <button type="submit" class="btn-primary"><span>Unsubscribe</span></button>
                    </form>
                @endif
            </div>
        </article>

        <nav class="legal-nav" aria-label="Related links">
            <a href="{{ route('shop.index') }}">Shop</a>
            <a href="{{ route('contact') }}">Contact Us</a>
        </nav>
    </div>
</section>
@endsection

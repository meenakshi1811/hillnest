@extends('layouts.app')

@section('title', 'Shipping Policy — HillNest')
@section('meta_description', 'HillNest delivers across India. See dispatch times, delivery timelines, shipping charges, and order tracking.')

@section('content')
@php($store = config('hillnest.store'))
@include('partials.legal-header', [
    'title' => 'Shipping Policy',
    'eyebrow' => 'Delivery',
    'subtitle' => 'How and when your HillNest ghee travels from Upper Shimla to your kitchen.',
    'updated' => 'October 13, 2026',
])

            <div class="legal-content">
                <p>Every HillNest order is packed by hand in our village at Chhajpur, Upper Shimla, and shipped through trusted courier partners. This policy explains where we deliver, how long it takes, and what it costs.</p>

                <h2>1. Where We Deliver</h2>
                <p>We deliver to addresses across India. We do not ship outside India at this time.</p>

                <h2>2. Order Processing and Dispatch</h2>
                <p>Your order is confirmed once payment is successful. We dispatch confirmed orders within <strong>{{ $store['dispatch_days'] }} business days</strong>.</p>

                <h2>3. Delivery Timelines</h2>
                <p>Most orders are delivered within <strong>{{ $store['delivery_days'] }} business days</strong> after dispatch. Remote areas, hilly regions, the North-East, Jammu &amp; Kashmir, Ladakh, and island territories may take longer.</p>
                <p>Delivery timelines are estimates and may be affected by weather, landslides or road closures in the hills, festivals, or courier delays outside our control.</p>

                <h2>4. Shipping Charges</h2>
                @if(! $shippingEnabled)
                    <p>Delivery is <strong>free on all orders</strong>.</p>
                @elseif($freeThreshold)
                    <ul>
                        <li>Orders of <strong>₹{{ number_format($freeThreshold, 0) }} and above</strong>: free delivery</li>
                        <li>Orders below ₹{{ number_format($freeThreshold, 0) }}: a flat shipping charge of <strong>₹{{ number_format($shippingFee, 0) }}</strong></li>
                    </ul>
                @else
                    <p>A flat shipping charge of <strong>₹{{ number_format($shippingFee, 0) }}</strong> applies to every order.</p>
                @endif
                <p>The exact shipping charge is always shown in your cart and at checkout before you pay.</p>

                <h2>5. Order Tracking</h2>
                <p>We email you when your order status changes, including when it is dispatched. Once your order ships, its tracking number and courier tracking link appear on your order in <a href="{{ route('account.orders') }}">My Orders</a>.</p>

                <h2>6. Delivery Address</h2>
                <p>Please enter a complete address, the correct pincode, and a reachable phone number at checkout. If you need to change your address, contact us as soon as possible; we can update it only before the order is dispatched.</p>

                <h2>7. Damaged, Leaked, or Missing Items</h2>
                <p>If your parcel arrives damaged, leaked, or with items missing, email us within <strong>24 hours</strong> of delivery with your order number and clear photos or video of the package and product. See our <a href="{{ route('refund') }}">Refund Policy</a> for how we handle these claims.</p>

                <h2>8. Marked Delivered but Not Received</h2>
                <p>If your order shows as delivered but has not reached you, tell us within <strong>48 hours</strong> so we can investigate with the courier.</p>

                <h2>9. Contact Us</h2>
                <p>For any question about shipping or your delivery:</p>
                <p>
                    <strong>{{ $store['name'] }}</strong><br>
                    {{ implode(', ', $store['address']) }}<br>
                    Email: <a href="mailto:{{ $store['email'] }}">{{ $store['email'] }}</a>
                    @if($store['phone'])
                        <br>Phone: <a href="tel:{{ preg_replace('/[^\d+]/', '', $store['phone']) }}">{{ $store['phone'] }}</a>
                    @endif
                </p>
            </div>
        </article>

        <nav class="legal-nav" aria-label="Related policies">
            <a href="{{ route('refund') }}">Refund Policy</a>
            <a href="{{ route('terms') }}">Terms of Use</a>
            <a href="{{ route('contact') }}">Contact Us</a>
        </nav>
    </div>
</section>
@endsection

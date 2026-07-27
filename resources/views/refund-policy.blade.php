@extends('layouts.app')

@section('title', 'Refund Policy — HillNest')

@section('content')
@include('partials.legal-header', [
    'title' => 'Refund Policy',
    'eyebrow' => 'Important',
    'subtitle' => 'Please read this policy carefully before placing an order.',
])

            <div class="legal-content">
                <div class="legal-notice">
                    <strong>All sales are final.</strong> HillNest does not provide refunds, returns, or exchanges on any orders once payment is successfully completed.
                </div>

                <p>At HillNest, we craft small-batch A2 bilona ghee with care at Chhajpur, Upper Shimla, Village Dharmana, P.O. Anti, Tehsil Jubbal, Distt. Shimla, Himachal Pradesh, India. Because our products are food items — and perishable once opened — we maintain a strict no-refund policy to protect product quality, hygiene, and customer safety.</p>
                <p>By completing a purchase on our Website, you acknowledge and agree to this Refund Policy in full.</p>

                <h2>1. No Refunds</h2>
                <p>HillNest <strong>does not offer refunds</strong> under any of the following circumstances, including but not limited to:</p>
                <ul>
                    <li>Change of mind or no longer wanting the product</li>
                    <li>Ordered the wrong size, quantity, or variant</li>
                    <li>Product taste, aroma, texture, or color preferences</li>
                    <li>Delays caused by courier partners, weather, or events outside our control</li>
                    <li>Failure to collect the shipment or refusal at delivery without a valid documented issue</li>
                    <li>Opened, used, or partially consumed products</li>
                    <li>Promotional or discounted purchases</li>
                </ul>

                <h2>2. No Returns or Exchanges</h2>
                <p>We do not accept returns or exchanges once an order has been dispatched or delivered. Please review product details, size, quantity, and delivery information carefully before checkout.</p>

                <h2>3. Perishable Food Products</h2>
                <p>Our ghee and related food products cannot be resold once they leave our control. For health, safety, and quality reasons, returned food items cannot be accepted back into inventory. This is standard practice for direct-to-consumer food brands and is essential to maintaining the integrity of what we deliver.</p>

                <h2>4. Order Cancellation</h2>
                <p>Orders cannot be cancelled after payment is successfully processed. If you believe you placed an order in error, contact us immediately at <a href="mailto:hillnestofficial@gmail.com">hillnestofficial@gmail.com</a>. We will try to assist before dispatch, but cancellation is not guaranteed once processing has begun.</p>

                <h2>5. Failed or Duplicate Payments</h2>
                <p>If your payment was debited but the order was not confirmed due to a technical failure, contact us with your payment reference details. After verification with our payment partner (Razorpay), we will either confirm the order or arrange reversal of the unsuccessful transaction in accordance with the payment provider's process. This is not a refund for a completed and confirmed order.</p>

                <h2>6. Damaged or Incorrect Orders</h2>
                <p>If your order arrives damaged in transit, leaked, tampered with, or materially incorrect (wrong product or missing items), you must notify us within <strong>24 hours</strong> of delivery at <a href="mailto:hillnestofficial@gmail.com">hillnestofficial@gmail.com</a> with:</p>
                <ul>
                    <li>Your order number</li>
                    <li>A clear description of the issue</li>
                    <li>Photos or video showing the package, label, and product condition</li>
                </ul>
                <p>We will review eligible claims on a case-by-case basis. Where we determine that the issue occurred before or during delivery and was not caused by misuse after receipt, we may, at our sole discretion, offer a replacement or store credit. <strong>This does not establish a general right to a monetary refund.</strong></p>

                <h2>7. Shipping and Non-Delivery</h2>
                <p>If a shipment is marked delivered but you did not receive it, notify us within 48 hours so we can investigate with the courier. If a shipment is lost in transit and confirmed undeliverable by the courier, we may, at our sole discretion, reship the order or provide another remedy. Monetary refunds will not be issued for confirmed orders except where expressly required by applicable law.</p>

                <h2>8. Chargebacks and Payment Disputes</h2>
                <p>If you initiate a chargeback or payment dispute with your bank or payment provider without first contacting us, we reserve the right to provide transaction and delivery records to the payment processor and to restrict future purchases associated with the disputed account.</p>

                <h2>9. Legal Rights</h2>
                <p>Nothing in this policy is intended to limit any rights you may have under applicable consumer protection laws in India that cannot be excluded by agreement. Where such laws apply and require a remedy, HillNest will comply to the extent legally required.</p>

                <h2>10. Contact Us</h2>
                <p>For questions about this Refund Policy or to report a delivery issue, contact:</p>
                <p>
                    <strong>HillNest</strong><br>
                    Chhajpur, Upper Shimla, Village Dharmana, P.O. Anti, Tehsil Jubbal, Distt. Shimla, Himachal Pradesh, India<br>
                    Email: <a href="mailto:hillnestofficial@gmail.com">hillnestofficial@gmail.com</a>
                </p>
                <p>Please include your order number in all correspondence so we can assist you promptly.</p>
            </div>
        </article>

        <nav class="legal-nav" aria-label="Related policies">
            <a href="{{ route('privacy') }}">Privacy Policy</a>
            <a href="{{ route('terms') }}">Terms of Use</a>
        </nav>
    </div>
</section>
@endsection

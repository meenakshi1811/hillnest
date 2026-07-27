@extends('layouts.app')

@section('title', 'Terms of Use — HillNest')

@section('content')
@include('partials.legal-header', [
    'title' => 'Terms of Use',
    'eyebrow' => 'Legal',
    'subtitle' => 'The rules and conditions for using the HillNest website and purchasing our products.',
])

            <div class="legal-content">
                <p>These Terms of Use ("Terms") govern your access to and use of the HillNest website and your purchase of products from us. By accessing the Website, creating an account, or placing an order, you agree to be bound by these Terms.</p>
                <p>If you do not agree, please do not use our Website or services.</p>

                <h2>1. About HillNest</h2>
                <p>HillNest operates an online store offering pure A2 bilona cow ghee and related food products sourced from Upper Shimla, Himachal Pradesh, India. References to "we," "us," or "our" mean HillNest. References to "you" or "your" mean the person using the Website or placing an order.</p>

                <h2>2. Eligibility</h2>
                <p>You must be at least 18 years old and capable of entering into a legally binding contract under applicable Indian law to use this Website and place orders. By using the Website, you represent that you meet this requirement.</p>

                <h2>3. Account Registration</h2>
                <p>Certain features, including checkout, require you to create an account. You are responsible for maintaining the confidentiality of your login credentials and for all activity that occurs under your account. Please notify us immediately if you suspect unauthorized access.</p>
                <p>We reserve the right to suspend or terminate accounts that violate these Terms or are used fraudulently.</p>

                <h2>4. Product Information</h2>
                <p>We make reasonable efforts to display accurate product descriptions, images, ingredients, pricing, and availability. However, minor variations in color, packaging, batch characteristics, or seasonal availability may occur due to the artisanal and natural nature of our products.</p>
                <p>We reserve the right to correct errors, update product information, limit quantities, or refuse orders where necessary.</p>

                <h2>5. Orders and Pricing</h2>
                <ul>
                    <li>All prices are listed in Indian Rupees (INR) unless stated otherwise.</li>
                    <li>An order is confirmed only after successful payment and order confirmation from HillNest.</li>
                    <li>We may cancel an order before dispatch if a product is unavailable, pricing is incorrect, payment fails, or fraud is suspected. If payment was captured, any applicable reversal will be handled as described in our <a href="{{ route('refund') }}">Refund Policy</a>.</li>
                    <li>Promotional offers, coupons, and discounts are subject to their stated terms and may be modified or withdrawn at any time.</li>
                </ul>

                <h2>6. Payment</h2>
                <p>Payments are processed through Razorpay or other authorized payment partners. By submitting payment information, you confirm that you are authorized to use the selected payment method. You agree to pay all charges associated with your order, including product price, applicable taxes, shipping fees, and any other disclosed charges.</p>

                <h2>7. Shipping and Delivery</h2>
                <p>We ship within India to the address you provide at checkout. Delivery timelines are estimates and may vary due to location, weather, courier delays, or other factors beyond our control.</p>
                <p>You are responsible for providing a complete and accurate delivery address and a reachable phone number. Risk of loss passes to you upon delivery to the address provided or to the courier's proof of delivery, whichever occurs first under applicable law.</p>
                <p>Free delivery may be offered on orders above a specified threshold, as displayed on the Website from time to time.</p>

                <h2>8. No Refunds</h2>
                <p>Because our products are food items and perishable in nature once opened, <strong>all sales are final</strong>. HillNest does not offer refunds, returns, or exchanges except where expressly required by applicable law.</p>
                <p>Please review our complete <a href="{{ route('refund') }}">Refund Policy</a> before placing an order.</p>

                <h2>9. Damaged or Incorrect Deliveries</h2>
                <p>If your order arrives damaged, leaked, or materially incorrect, you must notify us within 24 hours of delivery at <a href="mailto:hillnestofficial@gmail.com">hillnestofficial@gmail.com</a> with your order number and clear photos or video evidence. We will review the claim and, at our sole discretion, may offer a replacement or other remedy. This does not create a general right to refunds.</p>

                <h2>10. Acceptable Use</h2>
                <p>You agree not to:</p>
                <ul>
                    <li>Use the Website for unlawful, fraudulent, or abusive purposes</li>
                    <li>Attempt to gain unauthorized access to our systems or customer accounts</li>
                    <li>Interfere with the security or proper functioning of the Website</li>
                    <li>Copy, scrape, reproduce, or exploit Website content without permission</li>
                    <li>Resell our products in a manner that misrepresents HillNest or violates applicable law</li>
                </ul>

                <h2>11. Intellectual Property</h2>
                <p>All content on the Website, including text, logos, images, product names, packaging designs, and branding, is owned by or licensed to HillNest and protected by applicable intellectual property laws. You may not use our branding or content without prior written consent.</p>

                <h2>12. Disclaimer</h2>
                <p>Our products are food items intended for consumption in accordance with their labels and general culinary use. Product benefits described on the Website are for informational purposes only and are not medical advice. Consult a qualified healthcare professional before making dietary changes, especially if you have allergies, medical conditions, or are pregnant or nursing.</p>
                <p>The Website is provided on an "as is" and "as available" basis. To the fullest extent permitted by law, we disclaim warranties of merchantability, fitness for a particular purpose, and non-infringement.</p>

                <h2>13. Limitation of Liability</h2>
                <p>To the maximum extent permitted by applicable law, HillNest shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of the Website or purchase of our products. Our total liability for any claim relating to an order shall not exceed the amount you paid for that order.</p>

                <h2>14. Indemnity</h2>
                <p>You agree to indemnify and hold harmless HillNest, its founders, employees, and partners from claims, losses, or expenses arising out of your misuse of the Website, violation of these Terms, or infringement of any third-party rights.</p>

                <h2>15. Governing Law and Disputes</h2>
                <p>These Terms are governed by the laws of India. Any dispute arising out of or relating to these Terms or your use of the Website shall be subject to the exclusive jurisdiction of the courts located in Himachal Pradesh, India, unless applicable consumer protection law requires otherwise.</p>

                <h2>16. Changes to These Terms</h2>
                <p>We may revise these Terms at any time by posting an updated version on this page. Your continued use of the Website after changes are posted constitutes acceptance of the revised Terms.</p>

                <h2>17. Contact</h2>
                <p>For questions about these Terms, contact us at:</p>
                <p>
                    <strong>HillNest</strong><br>
                    Chhajpur, Upper Shimla, Himachal Pradesh, India<br>
                    Email: <a href="mailto:hillnestofficial@gmail.com">hillnestofficial@gmail.com</a>
                </p>
            </div>
        </article>

        <nav class="legal-nav" aria-label="Related policies">
            <a href="{{ route('privacy') }}">Privacy Policy</a>
            <a href="{{ route('refund') }}">Refund Policy</a>
        </nav>
    </div>
</section>
@endsection

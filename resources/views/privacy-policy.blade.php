@extends('layouts.app')

@section('title', 'Privacy Policy — HillNest')

@section('content')
@include('partials.legal-header', [
    'title' => 'Privacy Policy',
    'eyebrow' => 'Your Privacy',
    'subtitle' => 'How HillNest collects, uses, and protects your personal information.',
])

            <div class="legal-content">
                <p>Welcome to HillNest. We respect your privacy and are committed to protecting the personal information you share with us when you visit our website, create an account, or place an order. This Privacy Policy explains what data we collect, how we use it, and the choices you have.</p>
                <p>By using <strong>hillnest.in</strong> (the "Website") or purchasing our products, you agree to the practices described in this policy.</p>

                <h2>1. Who We Are</h2>
                <p>HillNest is a direct-to-consumer brand based in Chhajpur, Upper Shimla, Himachal Pradesh, India. We sell pure A2 bilona cow ghee and related food products through our online store.</p>
                <p>For privacy-related questions, contact us at <a href="mailto:hillnestofficial@gmail.com">hillnestofficial@gmail.com</a>.</p>

                <h2>2. Information We Collect</h2>
                <p>We may collect the following types of information:</p>
                <ul>
                    <li><strong>Account information:</strong> name, email address, and password when you register.</li>
                    <li><strong>Order and delivery information:</strong> shipping address, phone number, order details, and any notes you provide at checkout.</li>
                    <li><strong>Payment information:</strong> payments are processed securely by Razorpay. We do not store your full card, UPI, or bank account details on our servers.</li>
                    <li><strong>Communications:</strong> messages you send us by email or through contact forms.</li>
                    <li><strong>Technical data:</strong> IP address, browser type, device information, pages visited, and cookies or similar technologies used to operate and improve the Website.</li>
                </ul>

                <h2>3. How We Use Your Information</h2>
                <p>We use your information to:</p>
                <ul>
                    <li>Process and deliver your orders</li>
                    <li>Communicate order updates, delivery status, and customer support responses</li>
                    <li>Manage your account and order history</li>
                    <li>Prevent fraud, abuse, and unauthorized transactions</li>
                    <li>Improve our Website, products, and customer experience</li>
                    <li>Send marketing communications only where you have opted in or where permitted by law</li>
                    <li>Comply with applicable legal and tax obligations</li>
                </ul>

                <h2>4. Sharing of Information</h2>
                <p>We do not sell your personal information. We may share limited data with trusted third parties only when necessary to operate our business, including:</p>
                <ul>
                    <li><strong>Payment processors</strong> (such as Razorpay) to complete transactions</li>
                    <li><strong>Courier and logistics partners</strong> to deliver your orders</li>
                    <li><strong>Technology providers</strong> that host, secure, or support our Website</li>
                    <li><strong>Legal or regulatory authorities</strong> when required by applicable law</li>
                </ul>
                <p>These partners are expected to handle your data securely and only for the purpose for which it was shared.</p>

                <h2>5. Cookies and Similar Technologies</h2>
                <p>We use cookies and similar tools to keep you signed in, remember cart items, understand Website traffic, and improve performance. You can control cookies through your browser settings, though disabling them may affect certain features such as checkout or account login.</p>

                <h2>6. Data Retention</h2>
                <p>We retain personal information for as long as needed to fulfill orders, provide customer support, maintain business records, and meet legal requirements. When data is no longer required, we take reasonable steps to delete or anonymize it.</p>

                <h2>7. Data Security</h2>
                <p>We implement reasonable administrative, technical, and physical safeguards to protect your information. However, no method of transmission or storage over the internet is completely secure, and we cannot guarantee absolute security.</p>

                <h2>8. Your Rights and Choices</h2>
                <p>Depending on applicable law, you may have the right to:</p>
                <ul>
                    <li>Access, update, or correct your account information through your profile or by contacting us</li>
                    <li>Request deletion of your account, subject to legal and operational requirements such as completed order records</li>
                    <li>Opt out of promotional emails by using the unsubscribe link or contacting us directly</li>
                </ul>
                <p>To exercise these rights, email us at <a href="mailto:hillnestofficial@gmail.com">hillnestofficial@gmail.com</a>.</p>

                <h2>9. Children's Privacy</h2>
                <p>Our Website and products are not directed to individuals under 18 years of age. We do not knowingly collect personal information from children. If you believe a child has provided us with personal data, please contact us so we can take appropriate action.</p>

                <h2>10. Third-Party Links</h2>
                <p>Our Website may contain links to third-party websites or social media platforms. We are not responsible for the privacy practices of those sites and encourage you to review their policies separately.</p>

                <h2>11. Changes to This Policy</h2>
                <p>We may update this Privacy Policy from time to time. The revised version will be posted on this page with an updated "Last updated" date. Continued use of the Website after changes are posted constitutes acceptance of the updated policy.</p>

                <h2>12. Contact Us</h2>
                <p>If you have questions about this Privacy Policy or how we handle your data, please contact:</p>
                <p>
                    <strong>HillNest</strong><br>
                    Chhajpur, Upper Shimla, Himachal Pradesh, India<br>
                    Email: <a href="mailto:hillnestofficial@gmail.com">hillnestofficial@gmail.com</a>
                </p>
            </div>
        </article>

        <nav class="legal-nav" aria-label="Related policies">
            <a href="{{ route('terms') }}">Terms of Use</a>
            <a href="{{ route('refund') }}">Refund Policy</a>
        </nav>
    </div>
</section>
@endsection

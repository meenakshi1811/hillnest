<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HillNest — Pure Himalayan Ghee</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --gold: #C9973A;
      --gold-light: #E8C47A;
      --gold-pale: #F5E8C8;
      --forest: #1E3B2F;
      --forest-mid: #2D5240;
      --forest-light: #3E6B52;
      --cream: #FAF5EC;
      --cream-dark: #F0E8D6;
      --brown: #6B4F30;
      --text: #2A1F14;
      --text-mid: #5C4A34;
      --text-light: #8A7560;
      --white: #FFFFFF;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Jost', sans-serif;
      background-color: var(--cream);
      color: var(--text);
      overflow-x: hidden;
    }

    /* ── ANNOUNCEMENT BAR ── */
    .announcement {
      background: var(--forest);
      color: var(--gold-light);
      text-align: center;
      padding: 10px 20px;
      font-family: 'Jost', sans-serif;
      font-size: 13px;
      letter-spacing: 2px;
      text-transform: uppercase;
    }
    .announcement span { color: var(--gold-pale); }

    /* ── HEADER ── */
    header {
      background: var(--cream);
      border-bottom: 1px solid rgba(201,151,58,0.25);
      position: sticky;
      top: 0;
      z-index: 100;
      backdrop-filter: blur(10px);
    }

    .header-inner {
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 40px;
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      align-items: center;
      height: 90px;
    }

    nav.nav-left {
      display: flex;
      gap: 36px;
      align-items: center;
    }

    nav.nav-right {
      display: flex;
      gap: 24px;
      align-items: center;
      justify-content: flex-end;
    }

    nav a {
      font-family: 'Jost', sans-serif;
      font-size: 12.5px;
      font-weight: 500;
      letter-spacing: 2px;
      text-transform: uppercase;
      text-decoration: none;
      color: var(--text);
      position: relative;
      transition: color 0.3s;
    }

    nav a::after {
      content: '';
      position: absolute;
      bottom: -3px;
      left: 0;
      width: 0;
      height: 1px;
      background: var(--gold);
      transition: width 0.3s ease;
    }

    nav a:hover { color: var(--gold); }
    nav a:hover::after { width: 100%; }

    .logo-wrap {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .logo-wrap img {
      height: 94px;
      width: auto;
      object-fit: contain;
    }

    .nav-icon-btn {
      background: none;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 6px;
      font-family: 'Jost', sans-serif;
      font-size: 12.5px;
      font-weight: 500;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: var(--text);
      transition: color 0.3s;
      text-decoration: none;
    }

    .nav-icon-btn:hover { color: var(--gold); }

    .cart-btn {
      background: var(--forest);
      color: var(--gold-pale) !important;
      padding: 10px 20px;
      border-radius: 2px;
      letter-spacing: 1.5px;
      font-size: 11.5px;
      position: relative;
      transition: background 0.3s !important;
    }

    .cart-btn:hover { background: var(--forest-light) !important; color: var(--white) !important; }

    .cart-badge {
      background: var(--gold);
      color: var(--white);
      font-size: 9px;
      font-weight: 700;
      width: 17px;
      height: 17px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-left: 4px;
    }

    /* ── HERO ── */
    .hero {
      position: relative;
      min-height: 92vh;
      display: flex;
      align-items: center;
      overflow: hidden;
      background: var(--cream);
    }

    .hero-bg-pattern {
      position: absolute;
      inset: 0;
      background-image:
        radial-gradient(ellipse 80% 60% at 70% 40%, rgba(201,151,58,0.07) 0%, transparent 70%),
        radial-gradient(ellipse 60% 80% at 20% 70%, rgba(30,59,47,0.05) 0%, transparent 60%);
      pointer-events: none;
    }

    .hero-grain {
      position: absolute;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
      opacity: 0.4;
      pointer-events: none;
    }

    .hero-inner {
      max-width: 1280px;
      margin: 0 auto;
      padding: 80px 40px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 80px;
      align-items: center;
      position: relative;
      z-index: 2;
    }

    .hero-content { animation: fadeSlideUp 0.9s ease both; }

    .hero-eyebrow {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 22px;
    }

    .eyebrow-line {
      width: 40px;
      height: 1px;
      background: var(--gold);
    }

    .eyebrow-text {
      font-family: 'Jost', sans-serif;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 3.5px;
      text-transform: uppercase;
      color: var(--gold);
    }

    .hero h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(48px, 6vw, 80px);
      font-weight: 700;
      line-height: 1.08;
      color: var(--forest);
      margin-bottom: 28px;
    }

    .hero h1 em {
      font-style: italic;
      color: var(--gold);
    }

    .hero-desc {
      font-family: 'Cormorant Garamond', serif;
      font-size: 20px;
      font-weight: 400;
      line-height: 1.7;
      color: var(--text-mid);
      max-width: 420px;
      margin-bottom: 44px;
    }

    .hero-actions { display: flex; gap: 18px; align-items: center; flex-wrap: wrap; }

    .btn-primary {
      background: var(--forest);
      color: var(--gold-pale);
      padding: 16px 36px;
      font-family: 'Jost', sans-serif;
      font-size: 12px;
      font-weight: 600;
      letter-spacing: 2.5px;
      text-transform: uppercase;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn-primary::before {
      content: '';
      position: absolute;
      inset: 0;
      background: var(--gold);
      transform: translateX(-100%);
      transition: transform 0.4s ease;
    }

    .btn-primary span { position: relative; z-index: 1; }
    .btn-primary:hover::before { transform: translateX(0); }
    .btn-primary:hover { color: var(--forest); }

    .btn-ghost {
      color: var(--forest);
      font-family: 'Jost', sans-serif;
      font-size: 12px;
      font-weight: 500;
      letter-spacing: 2px;
      text-transform: uppercase;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 8px;
      border-bottom: 1px solid var(--gold);
      padding-bottom: 2px;
      transition: color 0.3s, gap 0.3s;
    }

    .btn-ghost:hover { color: var(--gold); gap: 14px; }

    .hero-trust {
      display: flex;
      gap: 32px;
      margin-top: 52px;
      padding-top: 32px;
      border-top: 1px solid rgba(201,151,58,0.2);
    }

    .trust-item { text-align: center; }

    .trust-num {
      font-family: 'Playfair Display', serif;
      font-size: 26px;
      font-weight: 700;
      color: var(--gold);
      display: block;
    }

    .trust-label {
      font-family: 'Jost', sans-serif;
      font-size: 10.5px;
      font-weight: 500;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: var(--text-light);
      margin-top: 2px;
    }

    /* Hero Visual */
    .hero-visual {
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      animation: fadeSlideUp 0.9s 0.2s ease both;
    }

    .hero-circle-bg {
      position: absolute;
      width: 480px;
      height: 480px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(201,151,58,0.08) 0%, transparent 70%);
      border: 1px solid rgba(201,151,58,0.15);
    }

    .hero-circle-bg::before {
      content: '';
      position: absolute;
      inset: 30px;
      border-radius: 50%;
      border: 1px dashed rgba(201,151,58,0.2);
    }

    .hero-jar-container {
      position: relative;
      z-index: 2;
      text-align: center;
    }

    .hero-jar {
      font-size: 180px;
      line-height: 1;
      filter: drop-shadow(0 20px 60px rgba(30,59,47,0.12));
      animation: float 4s ease-in-out infinite;
    }

    .hero-badge {
      position: absolute;
      top: -20px;
      right: -30px;
      background: var(--gold);
      color: var(--forest);
      width: 90px;
      height: 90px;
      border-radius: 50%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-family: 'Playfair Display', serif;
      font-size: 13px;
      font-weight: 700;
      line-height: 1.3;
      text-align: center;
      box-shadow: 0 4px 20px rgba(201,151,58,0.4);
      animation: pulse 3s ease-in-out infinite;
    }

    .hero-features {
      position: absolute;
      left: -40px;
      bottom: 30px;
      background: white;
      border: 1px solid rgba(201,151,58,0.2);
      border-radius: 4px;
      padding: 16px 20px;
      box-shadow: 0 8px 32px rgba(30,59,47,0.1);
    }

    .hero-feature-row {
      display: flex;
      align-items: center;
      gap: 10px;
      font-family: 'Jost', sans-serif;
      font-size: 12px;
      font-weight: 500;
      color: var(--text-mid);
      letter-spacing: 0.5px;
    }

    .hero-feature-row + .hero-feature-row { margin-top: 10px; }

    .feature-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--gold);
      flex-shrink: 0;
    }

    /* ── STRIP ── */
    .strip {
      background: var(--forest);
      padding: 18px 0;
      overflow: hidden;
    }

    .strip-track {
      display: flex;
      gap: 0;
      animation: scrollStrip 22s linear infinite;
      width: max-content;
    }

    .strip-item {
      display: flex;
      align-items: center;
      gap: 20px;
      padding: 0 48px;
      font-family: 'Jost', sans-serif;
      font-size: 11.5px;
      font-weight: 500;
      letter-spacing: 2.5px;
      text-transform: uppercase;
      color: var(--gold-light);
      white-space: nowrap;
      border-right: 1px solid rgba(201,151,58,0.25);
    }

    .strip-dot {
      width: 4px;
      height: 4px;
      border-radius: 50%;
      background: var(--gold);
      flex-shrink: 0;
    }

    /* ── PRODUCTS SECTION ── */
    .section {
      padding: 100px 40px;
      max-width: 1280px;
      margin: 0 auto;
    }

    .section-header {
      text-align: center;
      margin-bottom: 64px;
    }

    .section-eyebrow {
      font-family: 'Jost', sans-serif;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 3.5px;
      text-transform: uppercase;
      color: var(--gold);
      display: block;
      margin-bottom: 14px;
    }

    .section-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(34px, 4vw, 52px);
      font-weight: 700;
      color: var(--forest);
      line-height: 1.15;
    }

    .section-title em { font-style: italic; color: var(--gold); }

    .section-subtitle {
      font-family: 'Cormorant Garamond', serif;
      font-size: 18px;
      color: var(--text-light);
      margin-top: 14px;
      max-width: 520px;
      margin-left: auto;
      margin-right: auto;
      line-height: 1.6;
    }

    .divider-ornament {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 16px;
      margin-top: 20px;
    }

    .divider-ornament::before, .divider-ornament::after {
      content: '';
      width: 60px;
      height: 1px;
      background: linear-gradient(to right, transparent, var(--gold));
    }
    .divider-ornament::after { background: linear-gradient(to left, transparent, var(--gold)); }
    .divider-ornament span { color: var(--gold); font-size: 16px; }

    /* Product Grid */
    .products-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 28px;
    }

    .product-card {
      background: var(--white);
      border: 1px solid rgba(201,151,58,0.15);
      overflow: hidden;
      transition: transform 0.4s ease, box-shadow 0.4s ease;
      position: relative;
      cursor: pointer;
    }

    .product-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 20px 60px rgba(30,59,47,0.12);
    }

    .product-card.featured {
      grid-column: span 1;
      border-color: rgba(201,151,58,0.35);
    }

    .product-img-wrap {
      background: var(--cream);
      padding: 48px 32px 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      height: 260px;
    }

    .product-emoji { font-size: 100px; filter: drop-shadow(0 8px 24px rgba(30,59,47,0.1)); }

    .product-tag {
      position: absolute;
      top: 16px;
      left: 16px;
      background: var(--forest);
      color: var(--gold-light);
      font-family: 'Jost', sans-serif;
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 2px;
      text-transform: uppercase;
      padding: 5px 12px;
    }

    .product-tag.new { background: var(--gold); color: var(--forest); }

    .product-info {
      padding: 24px 28px 28px;
      border-top: 1px solid rgba(201,151,58,0.12);
    }

    .product-name {
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      font-weight: 600;
      color: var(--forest);
      margin-bottom: 8px;
    }

    .product-desc {
      font-family: 'Cormorant Garamond', serif;
      font-size: 15px;
      color: var(--text-light);
      line-height: 1.6;
      margin-bottom: 20px;
    }

    .product-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .product-price {
      font-family: 'Playfair Display', serif;
      font-size: 24px;
      font-weight: 700;
      color: var(--forest);
    }

    .product-price .currency {
      font-size: 14px;
      vertical-align: super;
      font-weight: 400;
      color: var(--text-mid);
    }

    .product-price .unit {
      font-family: 'Jost', sans-serif;
      font-size: 12px;
      font-weight: 400;
      color: var(--text-light);
    }

    .add-cart-btn {
      background: var(--forest);
      color: var(--gold-pale);
      border: none;
      padding: 12px 22px;
      font-family: 'Jost', sans-serif;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .add-cart-btn:hover { background: var(--gold); color: var(--forest); }

    /* ── WHY SECTION ── */
    .why-section {
      background: var(--forest);
      padding: 100px 40px;
      position: relative;
      overflow: hidden;
    }

    .why-section::before {
      content: '';
      position: absolute;
      top: -100px;
      right: -100px;
      width: 500px;
      height: 500px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(201,151,58,0.08) 0%, transparent 70%);
      pointer-events: none;
    }

    .why-inner {
      max-width: 1280px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 80px;
      align-items: center;
    }

    .why-content .section-eyebrow { color: var(--gold-light); }

    .why-content .section-title { color: var(--white); }

    .why-content .section-title em { color: var(--gold-light); }

    .why-text {
      font-family: 'Cormorant Garamond', serif;
      font-size: 19px;
      color: rgba(255,255,255,0.7);
      line-height: 1.75;
      margin-top: 20px;
      margin-bottom: 44px;
    }

    .why-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .why-card {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(201,151,58,0.2);
      padding: 24px;
      transition: background 0.3s;
    }

    .why-card:hover { background: rgba(201,151,58,0.08); }

    .why-icon { font-size: 28px; margin-bottom: 12px; }

    .why-card-title {
      font-family: 'Playfair Display', serif;
      font-size: 17px;
      font-weight: 600;
      color: var(--gold-light);
      margin-bottom: 8px;
    }

    .why-card-text {
      font-family: 'Jost', sans-serif;
      font-size: 13px;
      color: rgba(255,255,255,0.55);
      line-height: 1.6;
    }

    /* Process Visual */
    .process-visual {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .process-step {
      display: flex;
      gap: 20px;
      align-items: flex-start;
    }

    .step-num {
      font-family: 'Playfair Display', serif;
      font-size: 42px;
      font-weight: 700;
      color: rgba(201,151,58,0.2);
      line-height: 1;
      width: 50px;
      flex-shrink: 0;
    }

    .step-content { padding-top: 6px; }

    .step-title {
      font-family: 'Playfair Display', serif;
      font-size: 19px;
      font-weight: 600;
      color: var(--white);
      margin-bottom: 6px;
    }

    .step-desc {
      font-family: 'Jost', sans-serif;
      font-size: 13.5px;
      color: rgba(255,255,255,0.5);
      line-height: 1.6;
    }

    .step-divider {
      width: 1px;
      height: 32px;
      background: rgba(201,151,58,0.2);
      margin-left: 25px;
    }

    /* ── TESTIMONIALS ── */
    .testimonials-bg {
      background: var(--cream-dark);
      padding: 100px 40px;
    }

    .testimonials-inner {
      max-width: 1280px;
      margin: 0 auto;
    }

    .testimonials-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px;
      margin-top: 60px;
    }

    .testimonial-card {
      background: var(--white);
      border: 1px solid rgba(201,151,58,0.15);
      padding: 36px 32px;
      position: relative;
    }

    .testimonial-quote {
      font-family: 'Playfair Display', serif;
      font-size: 64px;
      color: var(--gold-pale);
      line-height: 0.5;
      margin-bottom: 16px;
    }

    .testimonial-text {
      font-family: 'Cormorant Garamond', serif;
      font-size: 18px;
      line-height: 1.7;
      color: var(--text-mid);
      margin-bottom: 28px;
    }

    .testimonial-stars {
      color: var(--gold);
      font-size: 14px;
      letter-spacing: 2px;
      margin-bottom: 16px;
    }

    .testimonial-author {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .author-avatar {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background: var(--forest);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Playfair Display', serif;
      font-size: 16px;
      color: var(--gold-light);
      font-weight: 600;
    }

    .author-name {
      font-family: 'Jost', sans-serif;
      font-size: 13px;
      font-weight: 600;
      color: var(--text);
      letter-spacing: 0.5px;
    }

    .author-place {
      font-family: 'Jost', sans-serif;
      font-size: 11.5px;
      color: var(--text-light);
      letter-spacing: 0.3px;
    }

    /* ── CTA BANNER ── */
    .cta-banner {
      background: var(--gold);
      padding: 72px 40px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .cta-banner::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image: repeating-linear-gradient(45deg, transparent, transparent 30px, rgba(255,255,255,0.03) 30px, rgba(255,255,255,0.03) 60px);
    }

    .cta-banner-inner { position: relative; z-index: 1; }

    .cta-banner h2 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(32px, 4vw, 52px);
      font-weight: 700;
      color: var(--forest);
      margin-bottom: 16px;
    }

    .cta-banner p {
      font-family: 'Cormorant Garamond', serif;
      font-size: 20px;
      color: rgba(30,59,47,0.75);
      margin-bottom: 36px;
    }

    .btn-cta-dark {
      background: var(--forest);
      color: var(--gold-pale);
      padding: 18px 44px;
      font-family: 'Jost', sans-serif;
      font-size: 12px;
      font-weight: 600;
      letter-spacing: 2.5px;
      text-transform: uppercase;
      text-decoration: none;
      display: inline-block;
      transition: all 0.3s;
    }

    .btn-cta-dark:hover { background: var(--white); color: var(--forest); }

    /* ── FOOTER ── */
    footer {
      background: var(--text);
      color: rgba(255,255,255,0.7);
      padding: 72px 40px 36px;
    }

    .footer-inner {
      max-width: 1280px;
      margin: 0 auto;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1.5fr;
      gap: 48px;
      padding-bottom: 56px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .footer-logo-col img {
      height: 64px;
      margin-bottom: 20px;
      opacity: 0.9;
    }

    .footer-tagline {
      font-family: 'Cormorant Garamond', serif;
      font-size: 16px;
      line-height: 1.65;
      color: rgba(255,255,255,0.5);
      margin-bottom: 24px;
    }

    .footer-socials {
      display: flex;
      gap: 14px;
    }

    .social-btn {
      width: 36px;
      height: 36px;
      border: 1px solid rgba(255,255,255,0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s;
      text-decoration: none;
    }

    .social-btn:hover { border-color: var(--gold); background: rgba(201,151,58,0.1); }

    .footer-col-title {
      font-family: 'Jost', sans-serif;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: var(--gold-light);
      margin-bottom: 22px;
    }

    .footer-links { list-style: none; }
    .footer-links li { margin-bottom: 12px; }

    .footer-links a {
      font-family: 'Jost', sans-serif;
      font-size: 13.5px;
      color: rgba(255,255,255,0.5);
      text-decoration: none;
      transition: color 0.3s;
    }

    .footer-links a:hover { color: var(--gold-light); }

    .footer-newsletter p {
      font-family: 'Cormorant Garamond', serif;
      font-size: 16px;
      color: rgba(255,255,255,0.5);
      line-height: 1.6;
      margin-bottom: 20px;
    }

    .newsletter-form {
      display: flex;
      gap: 0;
    }

    .newsletter-form input {
      flex: 1;
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.15);
      border-right: none;
      color: white;
      padding: 12px 16px;
      font-family: 'Jost', sans-serif;
      font-size: 13px;
      outline: none;
    }

    .newsletter-form input::placeholder { color: rgba(255,255,255,0.3); }

    .newsletter-form button {
      background: var(--gold);
      border: none;
      color: var(--forest);
      padding: 12px 18px;
      font-family: 'Jost', sans-serif;
      font-size: 12px;
      font-weight: 600;
      letter-spacing: 1px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .newsletter-form button:hover { background: var(--gold-light); }

    .footer-bottom {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-top: 32px;
      font-family: 'Jost', sans-serif;
      font-size: 12.5px;
      color: rgba(255,255,255,0.3);
    }

    .footer-bottom-links { display: flex; gap: 24px; }
    .footer-bottom-links a { color: rgba(255,255,255,0.3); text-decoration: none; transition: color 0.3s; }
    .footer-bottom-links a:hover { color: var(--gold-light); }

    /* ── CART SIDEBAR ── */
    .cart-overlay {
      position: fixed;
      inset: 0;
      background: rgba(30,59,47,0.5);
      z-index: 200;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s;
    }

    .cart-overlay.open { opacity: 1; pointer-events: all; }

    .cart-sidebar {
      position: fixed;
      top: 0;
      right: 0;
      width: 400px;
      height: 100vh;
      background: var(--cream);
      z-index: 201;
      transform: translateX(100%);
      transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      flex-direction: column;
      box-shadow: -8px 0 40px rgba(0,0,0,0.15);
    }

    .cart-sidebar.open { transform: translateX(0); }

    .cart-header {
      padding: 28px 28px 20px;
      border-bottom: 1px solid rgba(201,151,58,0.2);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .cart-title {
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      font-weight: 600;
      color: var(--forest);
    }

    .cart-close {
      background: none;
      border: 1px solid rgba(201,151,58,0.3);
      width: 36px;
      height: 36px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      color: var(--text-mid);
      transition: all 0.2s;
    }

    .cart-close:hover { background: var(--forest); color: white; }

    .cart-body { flex: 1; overflow-y: auto; padding: 24px 28px; }

    .cart-empty {
      text-align: center;
      padding: 60px 20px;
    }

    .cart-empty-icon { font-size: 48px; margin-bottom: 16px; }

    .cart-empty p {
      font-family: 'Cormorant Garamond', serif;
      font-size: 18px;
      color: var(--text-light);
    }

    .cart-item {
      display: flex;
      gap: 16px;
      padding: 16px 0;
      border-bottom: 1px solid rgba(201,151,58,0.12);
    }

    .cart-item-img {
      font-size: 40px;
      background: var(--cream-dark);
      width: 64px;
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .cart-item-details { flex: 1; }

    .cart-item-name {
      font-family: 'Playfair Display', serif;
      font-size: 15px;
      font-weight: 600;
      color: var(--forest);
      margin-bottom: 4px;
    }

    .cart-item-price {
      font-family: 'Jost', sans-serif;
      font-size: 14px;
      color: var(--gold);
      font-weight: 600;
    }

    .cart-qty {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-top: 10px;
    }

    .qty-btn {
      width: 26px;
      height: 26px;
      border: 1px solid rgba(201,151,58,0.3);
      background: none;
      cursor: pointer;
      font-size: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
      color: var(--text);
    }

    .qty-btn:hover { background: var(--forest); color: white; border-color: var(--forest); }

    .qty-num {
      font-family: 'Jost', sans-serif;
      font-size: 14px;
      font-weight: 600;
      color: var(--text);
      min-width: 20px;
      text-align: center;
    }

    .cart-footer {
      padding: 20px 28px 28px;
      border-top: 1px solid rgba(201,151,58,0.2);
    }

    .cart-total {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .cart-total-label {
      font-family: 'Jost', sans-serif;
      font-size: 12px;
      font-weight: 600;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--text-mid);
    }

    .cart-total-val {
      font-family: 'Playfair Display', serif;
      font-size: 24px;
      font-weight: 700;
      color: var(--forest);
    }

    .checkout-btn {
      width: 100%;
      background: var(--forest);
      color: var(--gold-pale);
      border: none;
      padding: 18px;
      font-family: 'Jost', sans-serif;
      font-size: 12px;
      font-weight: 600;
      letter-spacing: 2.5px;
      text-transform: uppercase;
      cursor: pointer;
      transition: background 0.3s;
    }

    .checkout-btn:hover { background: var(--gold); color: var(--forest); }

    /* Animations */
    @keyframes fadeSlideUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-16px); }
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); box-shadow: 0 4px 20px rgba(201,151,58,0.4); }
      50% { transform: scale(1.05); box-shadow: 0 8px 30px rgba(201,151,58,0.6); }
    }

    @keyframes scrollStrip {
      from { transform: translateX(0); }
      to { transform: translateX(-50%); }
    }

    @media (max-width: 1024px) {
      .hero-inner { grid-template-columns: 1fr; gap: 48px; }
      .why-inner { grid-template-columns: 1fr; }
      .products-grid { grid-template-columns: 1fr 1fr; }
      .testimonials-grid { grid-template-columns: 1fr 1fr; }
      .footer-grid { grid-template-columns: 1fr 1fr; gap: 36px; }
      .hero-visual { display: none; }
      .header-inner { grid-template-columns: auto 1fr auto; }
      .logo-wrap { justify-content: flex-start; margin-left: 20px; }
    }

    @media (max-width: 640px) {
      .products-grid, .testimonials-grid { grid-template-columns: 1fr; }
      .section { padding: 64px 20px; }
      .footer-grid { grid-template-columns: 1fr; }
      .cart-sidebar { width: 100%; }
      nav.nav-left { display: none; }
    }
  </style>
</head>
<body>

<!-- Announcement Bar -->
<div class="announcement">
  <span>🌿</span> Free delivery on orders above ₹999 &nbsp;·&nbsp; Pure A2 Cow Ghee from Upper Shimla <span>🌿</span>
</div>

<!-- Header -->
<header id="header">
  <div class="header-inner">
    <nav class="nav-left">
      <a href="http://127.0.0.1:8000/">Home</a>
      <a href="http://127.0.0.1:8000/shop">Shop</a>
      <a href="http://127.0.0.1:8000/about">Our Story</a>
      <a href="http://127.0.0.1:8000/shop#collection">Ghee</a>
    </nav>

    <div class="logo-wrap">
      <img src="https://i.ibb.co/twd2Wfm2/1780558528946-image.png"
           alt="HillNest — Pure Himalayan Ghee"
           onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='block'" />
      <span id="logo-fallback" style="display:none; font-family:'Playfair Display',serif; font-size:24px; font-weight:700; color:var(--forest);">Hill<span style="color:var(--gold)">Nest</span></span>
    </div>

    <nav class="nav-right">
      <a href="#" class="nav-icon-btn">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      </a>
      <a href="#" class="nav-icon-btn">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Login
      </a>
      <a href="#" class="nav-icon-btn cart-btn" onclick="toggleCart(event)">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        Cart
        <span class="cart-badge" id="cart-count">0</span>
      </a>
    </nav>
  </div>
</header>

<!-- HERO -->
<section class="hero">
  <div class="hero-bg-pattern"></div>
  <div class="hero-grain"></div>
  <div class="hero-inner">
    <div class="hero-content">
      <div class="hero-eyebrow">
        <span class="eyebrow-line"></span>
        <span class="eyebrow-text">From Upper Shimla, Himalayas</span>
      </div>
      <h1>Pure <em>Himalayan</em><br>Ghee, Crafted<br>With Love</h1>
      <p class="hero-desc">
        Handcrafted in the pristine valleys of Upper Shimla using the ancient Bilona method. Every jar carries the warmth of mountains and centuries of tradition.
      </p>
      <div class="hero-actions">
        <a href="http://127.0.0.1:8000/shop" class="btn-primary"><span>Shop Now</span></a>
        <a href="http://127.0.0.1:8000/about" class="btn-ghost">Our Story →</a>
      </div>
      <div class="hero-trust">
        <div class="trust-item">
          <span class="trust-num">100%</span>
          <span class="trust-label">Pure A2 Cow</span>
        </div>
        <div class="trust-item">
          <span class="trust-num">2000+</span>
          <span class="trust-label">Happy Families</span>
        </div>
        <div class="trust-item">
          <span class="trust-num">8000ft</span>
          <span class="trust-label">Altitude Source</span>
        </div>
      </div>
    </div>

    <div class="hero-visual">
      <div class="hero-circle-bg"></div>
      <div class="hero-jar-container">
        <div class="hero-jar">🫙</div>
        <div class="hero-badge">
          Bilona<br>Method
        </div>
        <div class="hero-features">
          <div class="hero-feature-row">
            <span class="feature-dot"></span>
            No additives, no preservatives
          </div>
          <div class="hero-feature-row">
            <span class="feature-dot"></span>
            Hand-churned in small batches
          </div>
          <div class="hero-feature-row">
            <span class="feature-dot"></span>
            Organic, grass-fed Himalayan cows
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Strip -->
<div class="strip">
  <div class="strip-track" id="strip">
    <div class="strip-item"><span class="strip-dot"></span>Pure Organic Ghee</div>
    <div class="strip-item"><span class="strip-dot"></span>Bilona Method</div>
    <div class="strip-item"><span class="strip-dot"></span>Upper Shimla</div>
    <div class="strip-item"><span class="strip-dot"></span>A2 Cow Milk</div>
    <div class="strip-item"><span class="strip-dot"></span>Small Batch Crafted</div>
    <div class="strip-item"><span class="strip-dot"></span>No Preservatives</div>
    <div class="strip-item"><span class="strip-dot"></span>Himalayan Purity</div>
    <div class="strip-item"><span class="strip-dot"></span>Nurtured by Nature</div>
    <div class="strip-item"><span class="strip-dot"></span>Pure Organic Ghee</div>
    <div class="strip-item"><span class="strip-dot"></span>Bilona Method</div>
    <div class="strip-item"><span class="strip-dot"></span>Upper Shimla</div>
    <div class="strip-item"><span class="strip-dot"></span>A2 Cow Milk</div>
    <div class="strip-item"><span class="strip-dot"></span>Small Batch Crafted</div>
    <div class="strip-item"><span class="strip-dot"></span>No Preservatives</div>
    <div class="strip-item"><span class="strip-dot"></span>Himalayan Purity</div>
    <div class="strip-item"><span class="strip-dot"></span>Nurtured by Nature</div>
  </div>
</div>

<!-- Products -->
<section id="collection">
  <div class="section">
    <div class="section-header">
      <span class="section-eyebrow">Our Collection</span>
      <h2 class="section-title">The <em>Finest</em> Himalayan Ghee</h2>
      <p class="section-subtitle">Each variety lovingly crafted from the milk of free-grazing cows, slow-cooked to golden perfection.</p>
      <div class="divider-ornament"><span>❧</span></div>
    </div>

    <div class="products-grid">

      <div class="product-card featured">
        <div class="product-img-wrap">
          <span class="product-tag new">Bestseller</span>
          <span class="product-emoji">🫙</span>
        </div>
        <div class="product-info">
          <div class="product-name">A2 Desi Cow Ghee</div>
          <div class="product-desc">Our flagship — slow-churned from the curd of grass-fed Gir cows. Nutty aroma, golden hue, incomparable richness.</div>
          <div class="product-footer">
            <div class="product-price">
              <span class="currency">₹</span>699 <span class="unit">/ 250g</span>
            </div>
            <button class="add-cart-btn" onclick="addToCart('A2 Desi Cow Ghee', 699)">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
              Add to Cart
            </button>
          </div>
        </div>
      </div>

      <div class="product-card">
        <div class="product-img-wrap">
          <span class="product-tag">Pure</span>
          <span class="product-emoji">✨</span>
        </div>
        <div class="product-info">
          <div class="product-name">Bilona Ghee — 500g</div>
          <div class="product-desc">Double the goodness, made using the ancient wooden churner method for deep, complex flavour.</div>
          <div class="product-footer">
            <div class="product-price">
              <span class="currency">₹</span>1199 <span class="unit">/ 500g</span>
            </div>
            <button class="add-cart-btn" onclick="addToCart('Bilona Ghee 500g', 1199)">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
              Add to Cart
            </button>
          </div>
        </div>
      </div>

      <div class="product-card">
        <div class="product-img-wrap">
          <span class="product-tag new">New</span>
          <span class="product-emoji">🌿</span>
        </div>
        <div class="product-info">
          <div class="product-name">Herb-Infused Ghee</div>
          <div class="product-desc">Infused with Himalayan herbs — turmeric, ashwagandha & moringa. Wellness in every spoonful.</div>
          <div class="product-footer">
            <div class="product-price">
              <span class="currency">₹</span>849 <span class="unit">/ 250g</span>
            </div>
            <button class="add-cart-btn" onclick="addToCart('Herb-Infused Ghee', 849)">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
              Add to Cart
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Why HillNest -->
<section class="why-section">
  <div class="why-inner">
    <div class="why-content">
      <span class="section-eyebrow">Why HillNest</span>
      <h2 class="section-title">Purity You Can<br><em>Taste & Trust</em></h2>
      <p class="why-text">
        At 8,000 feet above sea level, our cows roam free on pristine alpine meadows. No hormones, no shortcuts — just nature's best, brought to your table with generations of knowledge.
      </p>
      <div class="why-grid">
        <div class="why-card">
          <div class="why-icon">🐄</div>
          <div class="why-card-title">Free-Grazing A2 Cows</div>
          <div class="why-card-text">Our cows graze on natural high-altitude pastures, producing milk rich in nutrients and flavour.</div>
        </div>
        <div class="why-card">
          <div class="why-icon">🏔️</div>
          <div class="why-card-title">Himalayan Origin</div>
          <div class="why-card-text">Sourced directly from our farm in Upper Shimla — pure air, pure water, pure ghee.</div>
        </div>
        <div class="why-card">
          <div class="why-icon">⚗️</div>
          <div class="why-card-title">Bilona Crafted</div>
          <div class="why-card-text">Ancient Vedic method: curd churned by hand in a wooden churner before slow clarification.</div>
        </div>
        <div class="why-card">
          <div class="why-icon">🌱</div>
          <div class="why-card-title">Zero Chemicals</div>
          <div class="why-card-text">No additives, artificial flavours, or preservatives. Ever. What you see is what you get.</div>
        </div>
      </div>
    </div>

    <div class="process-visual">
      <div class="process-step">
        <span class="step-num">01</span>
        <div class="step-content">
          <div class="step-title">Fresh A2 Milk</div>
          <div class="step-desc">Collected each morning from free-grazing cows in Upper Shimla's pristine valleys.</div>
        </div>
      </div>
      <div class="step-divider"></div>
      <div class="process-step">
        <span class="step-num">02</span>
        <div class="step-content">
          <div class="step-title">Hand-Churned Curd</div>
          <div class="step-desc">Milk is cultured overnight, then churned using a traditional wooden Bilona churner.</div>
        </div>
      </div>
      <div class="step-divider"></div>
      <div class="process-step">
        <span class="step-num">03</span>
        <div class="step-content">
          <div class="step-title">Slow Clarification</div>
          <div class="step-desc">Butter is gently simmered over a wood fire until golden, aromatic ghee is born.</div>
        </div>
      </div>
      <div class="step-divider"></div>
      <div class="process-step">
        <span class="step-num">04</span>
        <div class="step-content">
          <div class="step-title">Poured with Love</div>
          <div class="step-desc">Strained, cooled, and sealed in glass jars — ready to bring the mountains to your kitchen.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="testimonials-bg">
  <div class="testimonials-inner">
    <div class="section-header">
      <span class="section-eyebrow">What Our Customers Say</span>
      <h2 class="section-title">Loved by <em>Families</em> Across India</h2>
      <div class="divider-ornament"><span>❧</span></div>
    </div>

    <div class="testimonials-grid">
      <div class="testimonial-card">
        <div class="testimonial-quote">"</div>
        <div class="testimonial-stars">★★★★★</div>
        <div class="testimonial-text">The aroma alone took me back to my grandmother's kitchen. This is what real ghee should taste like. We've been ordering every month since we discovered HillNest.</div>
        <div class="testimonial-author">
          <div class="author-avatar">P</div>
          <div>
            <div class="author-name">Priya Sharma</div>
            <div class="author-place">Delhi</div>
          </div>
        </div>
      </div>

      <div class="testimonial-card">
        <div class="testimonial-quote">"</div>
        <div class="testimonial-stars">★★★★★</div>
        <div class="testimonial-text">As a nutritionist, I recommend only the best to my clients. HillNest's A2 ghee is the only one I trust — pure, rich, and genuinely made with care.</div>
        <div class="testimonial-author">
          <div class="author-avatar">R</div>
          <div>
            <div class="author-name">Dr. Rajan Mehta</div>
            <div class="author-place">Mumbai</div>
          </div>
        </div>
      </div>

      <div class="testimonial-card">
        <div class="testimonial-quote">"</div>
        <div class="testimonial-stars">★★★★★</div>
        <div class="testimonial-text">You can literally see the difference — that beautiful golden colour, the granular texture in winters. Absolutely nothing compares to HillNest for our daily cooking.</div>
        <div class="testimonial-author">
          <div class="author-avatar">A</div>
          <div>
            <div class="author-name">Ananya Nair</div>
            <div class="author-place">Bangalore</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Banner -->
<div class="cta-banner">
  <div class="cta-banner-inner">
    <h2>Taste the Mountains Today</h2>
    <p>Free delivery on your first order. Pure Himalayan goodness at your doorstep.</p>
    <a href="http://127.0.0.1:8000/shop" class="btn-cta-dark">Explore the Collection</a>
  </div>
</div>

<!-- Footer -->
<footer>
  <div class="footer-inner">
    <div class="footer-grid">
      <div class="footer-logo-col">
        <img src="https://i.ibb.co/twd2Wfm2/1780558528946-image.png" alt="HillNest"
             onerror="this.style.display='none'; document.getElementById('footer-logo-fallback').style.display='block'" />
        <span id="footer-logo-fallback" style="display:none; font-family:'Playfair Display',serif; font-size:22px; font-weight:700; color:white;">Hill<span style="color:#E8C47A">Nest</span></span>
        <p class="footer-tagline">Pure • Organic • Himalayan<br>Nurtured by Nature, Made with Love.<br>From the heart of Upper Shimla.</p>
        <div class="footer-socials">
          <a href="#" class="social-btn">𝕏</a>
          <a href="#" class="social-btn">f</a>
          <a href="#" class="social-btn">in</a>
          <a href="#" class="social-btn">▶</a>
        </div>
      </div>

      <div>
        <div class="footer-col-title">Shop</div>
        <ul class="footer-links">
          <li><a href="#">A2 Desi Cow Ghee</a></li>
          <li><a href="#">Bilona Ghee</a></li>
          <li><a href="#">Herb-Infused Ghee</a></li>
          <li><a href="#">Gift Hampers</a></li>
          <li><a href="#">Bulk Orders</a></li>
        </ul>
      </div>

      <div>
        <div class="footer-col-title">Company</div>
        <ul class="footer-links">
          <li><a href="http://127.0.0.1:8000/about">Our Story</a></li>
          <li><a href="#">The Farm</a></li>
          <li><a href="#">Bilona Process</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Contact Us</a></li>
        </ul>
      </div>

      <div class="footer-newsletter">
        <div class="footer-col-title">Stay in the Loop</div>
        <p>Get recipes, wellness tips, and first access to new arrivals straight to your inbox.</p>
        <div class="newsletter-form">
          <input type="email" placeholder="your@email.com" />
          <button>Subscribe</button>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <span>© 2025 HillNest. All rights reserved.</span>
      <div class="footer-bottom-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Use</a>
        <a href="#">Refund Policy</a>
      </div>
    </div>
  </div>
</footer>

<!-- Cart Sidebar -->
<div class="cart-overlay" id="cart-overlay" onclick="toggleCart(event)"></div>
<div class="cart-sidebar" id="cart-sidebar">
  <div class="cart-header">
    <span class="cart-title">Your Cart</span>
    <button class="cart-close" onclick="toggleCart(event)">✕</button>
  </div>
  <div class="cart-body" id="cart-body">
    <div class="cart-empty">
      <div class="cart-empty-icon">🫙</div>
      <p>Your cart is beautifully empty.<br>Let's fill it with golden goodness!</p>
    </div>
  </div>
  <div class="cart-footer" id="cart-footer" style="display:none;">
    <div class="cart-total">
      <span class="cart-total-label">Total</span>
      <span class="cart-total-val" id="cart-total">₹0</span>
    </div>
    <button class="checkout-btn">Proceed to Checkout</button>
  </div>
</div>

<script>
  // ── Cart Logic ──
  let cart = [];

  function toggleCart(e) {
    if (e) e.preventDefault();
    const sidebar = document.getElementById('cart-sidebar');
    const overlay = document.getElementById('cart-overlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
    document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
  }

  function addToCart(name, price) {
    const existing = cart.find(i => i.name === name);
    if (existing) {
      existing.qty++;
    } else {
      cart.push({ name, price, qty: 1, emoji: '🫙' });
    }
    renderCart();
    // Open sidebar
    document.getElementById('cart-sidebar').classList.add('open');
    document.getElementById('cart-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function changeQty(index, delta) {
    cart[index].qty += delta;
    if (cart[index].qty <= 0) cart.splice(index, 1);
    renderCart();
  }

  function renderCart() {
    const body = document.getElementById('cart-body');
    const footer = document.getElementById('cart-footer');
    const countEl = document.getElementById('cart-count');
    const totalEl = document.getElementById('cart-total');

    const totalCount = cart.reduce((s, i) => s + i.qty, 0);
    const totalPrice = cart.reduce((s, i) => s + i.price * i.qty, 0);

    countEl.textContent = totalCount;

    if (cart.length === 0) {
      body.innerHTML = `<div class="cart-empty"><div class="cart-empty-icon">🫙</div><p>Your cart is beautifully empty.<br>Let's fill it with golden goodness!</p></div>`;
      footer.style.display = 'none';
    } else {
      footer.style.display = 'block';
      totalEl.textContent = '₹' + totalPrice.toLocaleString('en-IN');
      body.innerHTML = cart.map((item, i) => `
        <div class="cart-item">
          <div class="cart-item-img">${item.emoji}</div>
          <div class="cart-item-details">
            <div class="cart-item-name">${item.name}</div>
            <div class="cart-item-price">₹${item.price.toLocaleString('en-IN')}</div>
            <div class="cart-qty">
              <button class="qty-btn" onclick="changeQty(${i}, -1)">−</button>
              <span class="qty-num">${item.qty}</span>
              <button class="qty-btn" onclick="changeQty(${i}, 1)">+</button>
            </div>
          </div>
        </div>
      `).join('');
    }
  }

  // Sticky header shadow on scroll
  window.addEventListener('scroll', () => {
    const header = document.getElementById('header');
    if (window.scrollY > 10) {
      header.style.boxShadow = '0 2px 24px rgba(30,59,47,0.08)';
    } else {
      header.style.boxShadow = 'none';
    }
  });
</script>
</body>
</html>

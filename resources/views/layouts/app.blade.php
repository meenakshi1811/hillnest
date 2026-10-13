<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  @php
    // yieldContent() returns already-escaped text, so these are printed raw below.
    $metaTitle = trim($__env->yieldContent('title', 'HillNest — Pure A2 Bilona Ghee from Upper Shimla'));
    $metaDescription = trim($__env->yieldContent('meta_description', 'Pure A2 bilona ghee, hand-churned in village homes of Upper Shimla. Small-batch, slow-cooked over wood fire, no preservatives. Delivered across India.'));
    $metaImage = trim($__env->yieldContent('og_image', asset('images/og-image.jpg')));
  @endphp
  <title>{!! $metaTitle !!}</title>
  <meta name="description" content="{!! $metaDescription !!}" />
  @hasSection('robots')
  <meta name="robots" content="@yield('robots')" />
  @endif
  <link rel="canonical" href="{{ url()->current() }}" />
  <meta property="og:site_name" content="HillNest" />
  <meta property="og:type" content="@yield('og_type', 'website')" />
  <meta property="og:title" content="{!! $metaTitle !!}" />
  <meta property="og:description" content="{!! $metaDescription !!}" />
  <meta property="og:url" content="{{ url()->current() }}" />
  <meta property="og:image" content="{!! $metaImage !!}" />
  <meta property="og:locale" content="en_IN" />
  <meta name="twitter:card" content="summary_large_image" />
  <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" />
  <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}" />
  @stack('structured_data')
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}" rel="stylesheet" />
  <!-- Meta Pixel Code -->
  <script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '1102097026017807');
  fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=1102097026017807&ev=PageView&noscript=1"
  /></noscript>
  <!-- End Meta Pixel Code -->
</head>
<body class="min-h-screen flex flex-col bg-cream">
    @if(session()->has('impersonator_id'))
        <div class="impersonation-banner" role="status">
            <p>You are viewing <strong>{{ auth()->user()?->name }}</strong>’s account as admin.</p>
            <form method="POST" action="{{ route('impersonation.leave') }}">
                @csrf
                <button type="submit">Return to admin</button>
            </form>
        </div>
    @endif

    @include('partials.site-header')

    @if(session('success'))
        <div class="bg-emerald-50 border-b border-emerald-200 px-4 py-3 text-center text-base text-emerald-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-b border-red-200 px-4 py-3 text-center text-base text-red-800">{{ session('error') }}</div>
    @endif

    <main class="flex-1 site-main">
        @yield('content')
    </main>

    @include('partials.site-footer')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    @stack('scripts')
</body>
</html>

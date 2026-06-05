<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Hillnest — Pure bilona cow ghee from upper Shimla. Himalayan purity, traditional churn, delivered across India.">
    <title>@yield('title', 'Hillnest | Pure Bilona Cow Ghee | Upper Shimla')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="min-h-screen flex flex-col bg-cream">
    @include('partials.site-header')

    @if(session('success'))
        <div class="bg-emerald-50 border-b border-emerald-200 px-4 py-3 text-center text-base text-emerald-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-b border-red-200 px-4 py-3 text-center text-base text-red-800">{{ session('error') }}</div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.site-footer')
</body>
</html>

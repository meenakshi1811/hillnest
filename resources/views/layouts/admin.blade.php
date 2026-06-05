<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — Hillnest</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-page min-h-screen bg-stone-100">
    <div class="flex min-h-screen">
        <aside class="hidden w-64 shrink-0 flex-col bg-forest-800 text-white lg:flex">
            <div class="flex items-center gap-3 border-b border-forest-700 px-6 py-5">
                <img src="{{ hillnest_logo() }}" alt="Hillnest" class="h-10 w-10 rounded-full ring-2 ring-hill-500/40">
                <div>
                    <span class="font-display font-bold">Hillnest</span>
                    <span class="block text-xs text-hill-300">Admin Portal</span>
                </div>
            </div>
            <nav class="flex-1 space-y-1 p-4 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 {{ request()->routeIs('admin.dashboard') ? 'bg-forest-700 text-hill-300' : 'text-hill-100 hover:bg-forest-700/60' }}">Dashboard</a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 {{ request()->routeIs('admin.orders.*') ? 'bg-forest-700 text-hill-300' : 'text-hill-100 hover:bg-forest-700/60' }}">Orders</a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 {{ request()->routeIs('admin.users.*') ? 'bg-forest-700 text-hill-300' : 'text-hill-100 hover:bg-forest-700/60' }}">Customers</a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 {{ request()->routeIs('admin.products.*') ? 'bg-forest-700 text-hill-300' : 'text-hill-100 hover:bg-forest-700/60' }}">Products</a>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 {{ request()->routeIs('admin.reports.*') ? 'bg-forest-700 text-hill-300' : 'text-hill-100 hover:bg-forest-700/60' }}">Reports</a>
            </nav>
            <div class="border-t border-forest-700 p-4 space-y-2">
                <a href="{{ route('home') }}" class="block text-xs text-hill-300 hover:text-white">← View Storefront</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-red-300 hover:text-red-200">Logout</button>
                </form>
            </div>
        </aside>

        <div class="flex flex-1 flex-col">
            <header class="border-b border-stone-200 bg-white px-4 py-4 sm:px-6 lg:hidden">
                <div class="flex items-center justify-between">
                    <span class="font-display font-bold text-forest-800">Hillnest Admin</span>
                    <nav class="flex gap-3 text-xs">
                        <a href="{{ route('admin.dashboard') }}" class="text-forest-700">Dash</a>
                        <a href="{{ route('admin.orders.index') }}" class="text-forest-700">Orders</a>
                        <a href="{{ route('admin.reports.index') }}" class="text-forest-700">Reports</a>
                    </nav>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Hillnest</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @stack('styles')
</head>
<body class="admin-page">
    <div class="admin-shell">
        <aside class="admin-sidebar" aria-label="Admin navigation">
            <div class="admin-sidebar__brand">
                <img src="{{ hillnest_logo() }}" alt="Hillnest" class="admin-sidebar__logo">
                <div>
                    <p class="admin-sidebar__title">Hillnest</p>
                    <span class="admin-sidebar__subtitle">Admin Portal</span>
                </div>
            </div>
            <nav class="admin-sidebar__nav">
                @include('admin.partials.sidebar-nav')
            </nav>
            @include('admin.partials.sidebar-footer')
        </aside>

        <div class="admin-main-wrap">
            <header class="admin-mobile-header">
                <span class="admin-mobile-header__brand">Hillnest Admin</span>
                <button type="button" class="admin-mobile-header__toggle" id="admin-menu-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="admin-mobile-drawer">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </header>

            <div class="admin-mobile-drawer" id="admin-mobile-drawer" aria-hidden="true">
                <div class="admin-mobile-drawer__backdrop" id="admin-menu-backdrop"></div>
                <div class="admin-mobile-drawer__panel">
                    <div class="admin-mobile-drawer__head">
                        <span class="admin-sidebar__subtitle">Admin Menu</span>
                        <button type="button" class="admin-mobile-drawer__close" id="admin-menu-close" aria-label="Close menu">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <nav class="admin-mobile-drawer__nav">
                        @include('admin.partials.sidebar-nav')
                    </nav>
                    @include('admin.partials.sidebar-footer')
                </div>
            </div>

            <main class="admin-main">
                @if(session('success'))
                    <div class="admin-flash">{{ session('success') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var drawer = document.getElementById('admin-mobile-drawer');
        var toggle = document.getElementById('admin-menu-toggle');
        var closeBtn = document.getElementById('admin-menu-close');
        var backdrop = document.getElementById('admin-menu-backdrop');

        if (!drawer || !toggle) return;

        function openDrawer() {
            drawer.classList.add('is-open');
            drawer.setAttribute('aria-hidden', 'false');
            toggle.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer() {
            drawer.classList.remove('is-open');
            drawer.setAttribute('aria-hidden', 'true');
            toggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        toggle.addEventListener('click', openDrawer);
        closeBtn.addEventListener('click', closeDrawer);
        backdrop.addEventListener('click', closeDrawer);

        drawer.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', closeDrawer);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeDrawer();
        });
    });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/admin-datatables.js') }}"></script>
    @stack('scripts')
</body>
</html>

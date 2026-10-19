<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') — Harivanshi Seva Kunj</title>
    <meta name="robots" content="noindex, nofollow">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Font Awesome 6 Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Assets --}}
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="admin-body">

    {{-- Ambient Flowing Sacred Vrindavan Background Animation --}}
    @include('admin.components.flowing-bg')

    <div class="admin-layout">
        {{-- Mobile Sidebar Backdrop --}}
        <div class="admin-sidebar-backdrop" id="admin-sidebar-backdrop" aria-hidden="true"></div>

        {{-- Reusable Admin Sidebar --}}
        @include('admin.components.sidebar')

        {{-- Main Admin Work Area --}}
        <div class="admin-main">
            {{-- Reusable Top Navigation --}}
            @include('admin.components.top-nav', [
                'title' => View::yieldContent('page_title', 'Dashboard')
            ])

            {{-- Main Content Container --}}
            <main class="admin-content" id="admin-main-content" role="main" tabindex="-1">
                {{-- Reusable Flash Alerts --}}
                @include('admin.components.flash-messages')

                {{-- Page Breadcrumbs --}}
                @yield('breadcrumbs')

                {{-- Primary Page Content --}}
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Accessible Mobile Navigation Script --}}
    <script>
        (function() {
            const sidebar = document.getElementById('admin-sidebar');
            const toggleBtn = document.getElementById('admin-menu-toggle');
            const closeBtn = document.getElementById('admin-sidebar-close');
            const backdrop = document.getElementById('admin-sidebar-backdrop');

            if (!sidebar || !toggleBtn || !backdrop) return;

            function openSidebar() {
                sidebar.classList.add('is-open');
                backdrop.classList.add('is-active');
                toggleBtn.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('is-open');
                backdrop.classList.remove('is-active');
                toggleBtn.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }

            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (sidebar.classList.contains('is-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            closeBtn?.addEventListener('click', closeSidebar);
            backdrop.addEventListener('click', closeSidebar);

            // Close on Escape key press
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
                    closeSidebar();
                }
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>

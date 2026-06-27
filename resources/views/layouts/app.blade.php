<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="{{ session('theme', 'light') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lashbook2 — profesionāla skropstu pieaudzēšana, pieraksti un lojalitātes programma.">
    <title>@yield('title', 'Lashbook2')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-bg text-[rgb(var(--text))] min-h-screen" data-testid="app-shell">
    <div class="flex min-h-screen flex-col md:flex-row">
        <div id="sidebar-wrap" class="fixed inset-y-0 left-0 z-30 w-[min(100%,20rem)] -translate-x-full md:relative md:translate-x-0 md:w-72 transition-transform duration-300 ease-out">
            @include('layouts.sidebar')
        </div>

        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-20 hidden md:hidden" aria-hidden="true"></div>

        <div class="flex-1 min-w-0" data-testid="main-content">
            <header class="sticky top-0 z-10 md:hidden glass-panel border-b px-4 py-3 flex items-center justify-between" style="border-color: rgb(var(--border));">
                <button id="sidebar-open" type="button" aria-label="Open menu" class="w-11 h-11 rounded-xl bg-[rgb(var(--card))] border flex items-center justify-center" style="border-color: rgb(var(--border));">
                    <span class="text-xl leading-none">☰</span>
                </button>
                <span class="brand-badge">Lashbook2</span>
            </header>

            <main class="mx-auto w-full max-w-7xl px-4 py-5 sm:px-6 md:px-8 md:py-8">
                @if (session('success'))
                    <div class="alert-success" role="status">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert-error" role="alert">{{ session('error') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const sidebarWrap = document.getElementById('sidebar-wrap');
        const overlay = document.getElementById('sidebar-overlay');
        const openBtn = document.getElementById('sidebar-open');
        const closeBtn = document.getElementById('sidebar-close');

        function openSidebar() {
            sidebarWrap?.classList.remove('-translate-x-full');
            overlay?.classList.remove('hidden');
            document.body.classList.add('sidebar-open');
        }

        function closeSidebar() {
            sidebarWrap?.classList.add('-translate-x-full');
            overlay?.classList.add('hidden');
            document.body.classList.remove('sidebar-open');
        }

        openBtn?.addEventListener('click', openSidebar);
        overlay?.addEventListener('click', closeSidebar);
        closeBtn?.addEventListener('click', closeSidebar);
    </script>
</body>
</html>

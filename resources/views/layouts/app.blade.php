<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="{{ session('theme', 'light') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LashBook')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
</head>
<body class="bg-[rgb(var(--background))] text-[rgb(var(--text))] min-h-screen">
    <div class="flex h-screen">
        <div id="sidebar-wrap" class="fixed md:relative z-30 -translate-x-full md:translate-x-0 transition-transform duration-200">
            @include('layouts.sidebar')
        </div>

        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden"></div>

        <div class="flex-1 overflow-auto">
            <div class="md:hidden p-4">
                <button id="sidebar-open" class="w-11 h-11 rounded-xl bg-[rgb(var(--card))] border border-zinc-300 dark:border-zinc-700 flex items-center justify-center">
                    <span class="text-xl leading-none">⋮</span>
                </button>
            </div>

            @if (session('success'))
                <div class="mx-6 mt-2 p-4 rounded-2xl bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mx-6 mt-2 p-4 rounded-2xl bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-200">
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </div>
    </div>
    <script>
        const sidebarWrap = document.getElementById('sidebar-wrap');
        const overlay = document.getElementById('sidebar-overlay');
        const openBtn = document.getElementById('sidebar-open');

        function openSidebar() {
            sidebarWrap?.classList.remove('-translate-x-full');
            overlay?.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebarWrap?.classList.add('-translate-x-full');
            overlay?.classList.add('hidden');
        }

        openBtn?.addEventListener('click', openSidebar);
        overlay?.addEventListener('click', closeSidebar);
    </script>
</body>
</html>
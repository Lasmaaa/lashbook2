<div id="sidebar" class="w-72 bg-[rgb(var(--card))] border-r h-full fixed md:relative transition-all" style="border-color: rgb(var(--border));">
    <div class="p-6">
        <h1 class="text-3xl font-bold tracking-tight bg-clip-text text-transparent" style="background-image: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--accent)));">LashBook</h1>
        <p class="text-sm mt-1 text-muted">Professional Lash Booking</p>
        
        <nav class="mt-10 space-y-2">
            <a href="{{ auth()->user()->isAdmin() ? route('admin.index') : route('user.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-violet-100/70 dark:hover:bg-violet-900/40 font-medium transition">
                <span>🏠</span> <span>{{ __('Dashboard') }}</span>
            </a>
            @if(!auth()->user()->isAdmin())
                <a href="{{ route('calendar') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-violet-100/70 dark:hover:bg-violet-900/40 font-medium transition">
                    <span>📅</span> <span>{{ __('Pierakstīties') }}</span>
                </a>
                <a href="{{ route('loyalty') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-violet-100/70 dark:hover:bg-violet-900/40 font-medium transition">
                    <span>⭐</span> <span>Loyalty Card</span>
                </a>
            @else
                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-violet-100/70 dark:hover:bg-violet-900/40 font-medium transition">
                    <span>👥</span> <span>{{ __('ui.admin_procedures') }}</span>
                </a>
                <a href="{{ route('admin.loyalty') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-violet-100/70 dark:hover:bg-violet-900/40 font-medium transition">
                    <span>⭐</span> <span>{{ __('ui.admin_loyalty_scan') }}</span>
                </a>
                <a href="{{ route('admin.action-panel') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-violet-100/70 dark:hover:bg-violet-900/40 font-medium transition">
                    <span>📊</span> <span>{{ __('ui.admin_action_panel') }}</span>
                </a>
            @endif
        </nav>

        <div class="mt-10 space-y-3 border-t pt-6" style="border-color: rgb(var(--border));">
            <form method="POST" action="{{ route('theme.toggle') }}">
                @csrf
                <button type="submit" class="w-full px-4 py-3 rounded-2xl bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-left transition">
                    {{ session('theme', 'light') === 'dark' ? '☀️ Light mode' : '🌙 Dark mode' }}
                </button>
            </form>

            <form method="POST" action="{{ route('language.set') }}">
                @csrf
                <p class="text-xs uppercase tracking-wide text-zinc-500 mb-2">Valoda</p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="submit" name="lang" value="lv" class="px-3 py-2 rounded-xl border text-sm transition {{ app()->getLocale() === 'lv' ? 'bg-violet-600 text-white border-violet-600' : 'border-zinc-300 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">LV</button>
                    <button type="submit" name="lang" value="en" class="px-3 py-2 rounded-xl border text-sm transition {{ app()->getLocale() === 'en' ? 'bg-violet-600 text-white border-violet-600' : 'border-zinc-300 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">EN</button>
                    <button type="submit" name="lang" value="ru" class="px-3 py-2 rounded-xl border text-sm transition {{ app()->getLocale() === 'ru' ? 'bg-violet-600 text-white border-violet-600' : 'border-zinc-300 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">RU</button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                @csrf
                <button type="submit" class="w-full px-4 py-3 rounded-2xl bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-200 hover:bg-rose-200 dark:hover:bg-rose-900/60 transition text-left">
                    🚪 Logout
                </button>
            </form>
        </div>
    </div>
</div>
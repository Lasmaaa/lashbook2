<aside id="sidebar" data-testid="sidebar" class="w-full md:w-72 bg-[rgb(var(--card))] border-r h-full fixed md:relative overflow-y-auto" style="border-color: rgb(var(--border));">
    <div class="relative p-5 sm:p-6 min-h-full flex flex-col">
        <button id="sidebar-close" type="button" aria-label="Close menu" class="absolute top-5 right-5 inline-flex items-center justify-center h-10 w-10 rounded-xl border md:hidden" style="border-color: rgb(var(--border)); background: rgb(var(--card));">
            <span aria-hidden="true">×</span>
        </button>

        <div class="pr-10 md:pr-0">
            <span class="brand-badge mb-3">Studio</span>
            <h1 data-testid="brand-title" class="text-3xl font-bold font-display bg-clip-text text-transparent" style="background-image: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--accent)));">Lashbook2</h1>
            <p class="text-sm mt-2 text-muted leading-relaxed">{{ __('ui.professional_booking') }}</p>
        </div>
        
        <nav class="mt-8 space-y-1.5 flex-1" aria-label="Main navigation">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.action-panel') }}" class="nav-link {{ request()->routeIs('admin.action-panel') ? 'active' : '' }}">
                    <span aria-hidden="true">📊</span> <span>{{ __('ui.admin_action_panel') }}</span>
                </a>
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <span aria-hidden="true">👥</span> <span>{{ __('ui.admin_users') }}</span>
                </a>
                <a href="{{ route('admin.bookings') }}" class="nav-link {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
                    <span aria-hidden="true">📅</span> <span>{{ __('ui.admin_bookings') }}</span>
                </a>
                <a href="{{ route('admin.procedures') }}" class="nav-link {{ request()->routeIs('admin.procedures*') ? 'active' : '' }}">
                    <span aria-hidden="true">💅</span> <span>{{ __('ui.admin_procedures_calendar') }}</span>
                </a>
                <a href="{{ route('admin.loyalty') }}" class="nav-link {{ request()->routeIs('admin.loyalty*') ? 'active' : '' }}">
                    <span aria-hidden="true">⭐</span> <span>{{ __('ui.admin_loyalty_scan') }}</span>
                </a>
            @else
                <a href="{{ route('user.index') }}" class="nav-link {{ request()->routeIs('user.index') ? 'active' : '' }}">
                    <span aria-hidden="true">🏠</span> <span>{{ __('ui.dashboard') }}</span>
                </a>
                <a href="{{ route('calendar') }}" class="nav-link {{ request()->routeIs('calendar*') || request()->routeIs('book.*') ? 'active' : '' }}">
                    <span aria-hidden="true">📅</span> <span>{{ __('ui.book_appointment') }}</span>
                </a>
                <a href="{{ route('feedback.create') }}" class="nav-link {{ request()->routeIs('feedback.*') ? 'active' : '' }}">
                    <span aria-hidden="true">💬</span> <span>{{ __('ui.reviews') }}</span>
                </a>
                <a href="{{ route('loyalty') }}" class="nav-link {{ request()->routeIs('loyalty') ? 'active' : '' }}">
                    <span aria-hidden="true">⭐</span> <span>{{ __('ui.loyalty_card') }}</span>
                </a>
            @endif
        </nav>

        <div class="mt-8 space-y-3 border-t pt-5" style="border-color: rgb(var(--border));">
            <form method="POST" action="{{ route('theme.toggle') }}">
                @csrf
                <button type="submit" class="w-full px-4 py-3 theme-toggle-btn transition">
                    {{ session('theme', 'light') === 'dark' ? __('ui.light_mode') : __('ui.dark_mode') }}
                </button>
            </form>

            <form method="POST" action="{{ route('language.set') }}">
                @csrf
                <p class="text-xs uppercase tracking-wide text-muted mb-2">{{ __('ui.language') }}</p>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['lv' => 'LV', 'en' => 'EN', 'ru' => 'RU'] as $code => $label)
                        <button type="submit" name="lang" value="{{ $code }}" class="lang-btn px-3 py-2 rounded-xl text-sm font-semibold transition {{ app()->getLocale() === $code ? 'is-active' : '' }}">{{ $label }}</button>
                    @endforeach
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="pt-1">
                @csrf
                <button type="submit" class="logout-btn w-full px-4 py-3 rounded-2xl transition text-left font-medium">
                    🚪 {{ __('ui.logout') }}
                </button>
            </form>
        </div>
    </div>
</aside>

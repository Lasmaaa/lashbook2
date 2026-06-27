@extends('layouts.auth')

@section('content')
<div class="auth-shell">
    <div class="auth-hero" aria-hidden="true">
        <img src="https://picsum.photos/seed/lashbook-auth/1200/1600" alt="">
        <div class="auth-hero-overlay">
            <span class="brand-badge">Lashbook2</span>
            <h1>{{ __('ui.professional_booking') }}</h1>
            <p>{{ __('ui.loyalty_card_desc') }}</p>
        </div>
    </div>

    <div class="auth-panel">
        <div class="auth-card">
            <span class="brand-badge" data-testid="brand-title">Lashbook2</span>
            <h1 class="headings">{{ __('ui.login') }}</h1>
            <p class="auth-subtitle">{{ __('ui.book_appointment_desc') }}</p>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" data-testid="login-form">
                @csrf

                <div class="input-group">
                    <x-input-label for="email" :value="__('ui.email')" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="input-group">
                    <x-input-label for="password" :value="__('ui.password')" />
                    <x-text-input id="password" type="password" name="password" required />
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="flex-row">
                    <label for="remember_me" class="form-check-label flex items-center gap-2">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span>{{ __('ui.remember_me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">{{ __('ui.forgot_password') }}</a>
                    @endif
                </div>

                <button type="submit">{{ __('ui.login') }}</button>

                <div class="auth-footer">
                    <span>{{ __('ui.dont_have_account') }}</span>
                    <a href="{{ route('register') }}">{{ __('ui.register') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

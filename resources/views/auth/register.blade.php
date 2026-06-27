@extends('layouts.auth')

@section('content')
<div class="auth-shell">
    <div class="auth-hero" aria-hidden="true">
        <img src="https://picsum.photos/seed/lashbook-register/1200/1600" alt="">
        <div class="auth-hero-overlay">
            <span class="brand-badge">Lashbook2</span>
            <h1>{{ __('ui.register') }}</h1>
            <p>{{ __('ui.reviews_desc') }}</p>
        </div>
    </div>

    <div class="auth-panel">
        <div class="auth-card">
            <span class="brand-badge" data-testid="brand-title">Lashbook2</span>
            <h1 class="headings">{{ __('ui.register') }}</h1>
            <p class="auth-subtitle">{{ __('ui.dont_have_account') }}</p>

            <form method="POST" action="{{ route('register') }}" data-testid="login-form">
                @csrf

                <div class="input-group">
                    <x-input-label for="name" :value="__('ui.name')" />
                    <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="given-name" />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div class="input-group">
                    <x-input-label for="surname" :value="__('ui.surname')" />
                    <x-text-input id="surname" type="text" name="surname" :value="old('surname')" required autocomplete="family-name" />
                    <x-input-error :messages="$errors->get('surname')" />
                </div>

                <div class="input-group">
                    <x-input-label for="email" :value="__('ui.email')" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="input-group">
                    <x-input-label for="password" :value="__('ui.password')" />
                    <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="input-group">
                    <x-input-label for="password_confirmation" :value="__('ui.confirm_password')" />
                    <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" />
                </div>

                <button type="submit">{{ __('ui.register') }}</button>

                <div class="auth-footer">
                    <span>{{ __('ui.already_registered') }}</span>
                    <a href="{{ route('login') }}">{{ __('ui.login') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

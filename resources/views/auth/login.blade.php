@extends('layouts.auth')

@section('content')
<div class="container">
    <h1 class="headings">{{ __('ui.login') }}</h1>
    
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
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
            <label for="remember_me" style="display: flex; align-items: center; gap: 5px;">
                <input id="remember_me" type="checkbox" name="remember">
                <span>{{ __('ui.remember_me') }}</span>
            </label>
            
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">{{ __('ui.forgot_password') }}</a>
            @endif
        </div>

        <button type="submit">
            {{ __('ui.login') }}
        </button>
            
        <div class="flex row">
            <span>{{ __('ui.dont_have_account') }}</span>
            <a href="{{ route('register') }}">{{ __('ui.register') }}</a>
        </div>
    </form>
</div>
@endsection
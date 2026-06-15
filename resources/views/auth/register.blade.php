@extends('layouts.auth')

@section('content')
<div class="container">
    <h1 class="headings">{{ __('ui.register') }}</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="input-group">
            <x-input-label for="name" :value="__('ui.name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="input-group">
            <x-input-label for="email" :value="__('ui.email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="input-group">
            <x-input-label for="password" :value="__('ui.password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="input-group">
            <x-input-label for="password_confirmation" :value="__('ui.confirm_password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="ms-4">
            {{ __('ui.register') }}
        </button>

        <div class="flex row">
            <span>{{ __('ui.already_registered') }}</span>
            <a href="{{ route('login') }}">{{ __('ui.login') }}</a>
        </div>
    </form>
</div>
@endsection
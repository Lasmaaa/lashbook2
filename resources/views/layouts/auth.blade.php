<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('theme', 'light') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Lashbook2 — pieraksti, lojalitātes karte un klientu panelis.">
    <title>{{ __('ui.login') }} · Lashbook2</title>
    @vite(['resources/css/app.css', 'resources/css/auth.css'])
</head>
<body data-testid="app-shell">
    @yield('content')
</body>
</html>

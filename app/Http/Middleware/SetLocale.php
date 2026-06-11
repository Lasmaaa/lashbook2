<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');

        if (!$locale && $request->user()?->preferred_language) {
            $locale = $request->user()->preferred_language;
            $request->session()->put('locale', $locale);
        }

        app()->setLocale($locale ?: 'lv');

        return $next($request);
    }
}

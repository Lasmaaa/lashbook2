<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function set(Request $request)
    {
        $validated = $request->validate([
            'lang' => 'required|in:lv,en,ru',
        ]);

        $locale = $validated['lang'];

        session(['locale' => $locale]);
        app()->setLocale($locale);

        if ($request->user()) {
            $request->user()->update([
                'preferred_language' => $locale,
            ]);
        }

        return back()->with('success', __('Language updated.'));
    }
}
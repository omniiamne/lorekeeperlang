<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        \Log::info('=== MIDDLEWARE SetLocale START ===');
        \Log::info('Request URI: ' . $request->fullUrl());
        \Log::info('Session locale (before): ' . ($request->session()->get('locale') ?? 'null'));
        \Log::info('Segment 1: ' . ($request->segment(1) ?? 'null'));

        $locale = $request->session()->get('locale');

        if (!$locale && $request->segment(1)) {
            $locale = $request->segment(1);
        }

        if (!$locale) {
            $locale = config('app.locale');
        }

        if (!in_array($locale, ['en', 'ru'])) {
            $locale = 'en';
        }

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        \Log::info('Locale set to: ' . $locale);
        \Log::info('App locale now: ' . app()->getLocale());
        \Log::info('Session locale now: ' . session('locale'));
        \Log::info('=== MIDDLEWARE SetLocale END ===');

        return $next($request);
    }
}

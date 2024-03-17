<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('services.should_have_localization')) {
            if (Auth::check()) {
                $locale = Auth::user()->language;
            } else {
                $locale = session('locale', config('app.locale'));
            }

            app()->setLocale($locale);
            Session::put('locale', $locale);
        }

        return $next($request);
    }
}

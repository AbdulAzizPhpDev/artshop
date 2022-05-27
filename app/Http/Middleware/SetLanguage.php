<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->segment(1) == 'ru' or $request->segment(1) == 'uz') {
            app()->setLocale($request->segment(1));

        } elseif (session()->has('lang')) {
            app()->setLocale(session()->get('lang'));
        }

        return $next($request);
    }
}

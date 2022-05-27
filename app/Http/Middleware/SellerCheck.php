<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SellerCheck
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
        if (Auth::check() && Auth::user()->role_id === 3 && !Auth::user()->is_first) {

            return $next($request);
        }

        return redirect()->route('seller.company.about',['lang'=>app()->getLocale()])->withErrors([
            'alard' => __('about_com_alard')
        ]);
    }
}

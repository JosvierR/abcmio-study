<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->role!=='admin' || Auth::user()->role!=='super'){
            return redirect()->route('home', app()->getLocale());
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;

class SetLocale
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
        if ($request->header('locale')) {
            app()->setLocale($request->header('locale'));
        }else{
            app()->setLocale(config('app.locale'));
        }
        return $next($request);
    }
}

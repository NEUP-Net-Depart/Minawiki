<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfSuperAdmin
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
        if($request->session()->has('user.power') && $request->session()->get('user.power') > 1)
            return $next($request);
        else
            abort(404);
    }
}

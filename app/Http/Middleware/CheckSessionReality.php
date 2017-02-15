<?php

namespace App\Http\Middleware;

use Closure;

class CheckSessionReality
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
        //Check if session exist
        if(!$request->session()->has('user.sessionReality') || $request->session()->get('user.sessionReality') != true)
        {
            return redirect('/auth/confirm?continue=' . urlencode($request->getRequestUri()));
        }
        return $next($request);
    }
}

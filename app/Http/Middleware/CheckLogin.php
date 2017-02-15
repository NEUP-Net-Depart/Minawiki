<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
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
        if(!$request->session()->has('user.id') && false === strpos($request->getRequestUri(), '/auth/login') && false === strpos($request->getRequestUri(), '/auth/register'))
        {
            return redirect('/auth/login?continue=' . urlencode($request->getRequestUri()));
        }
        return $next($request);
    }
}

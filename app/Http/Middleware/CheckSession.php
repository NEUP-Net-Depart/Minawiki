<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class CheckSession
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
        if(!$request->session()->has('user.id'))
        {
            //Check if token in cookie
            if(!empty($request->cookie('user.token')))
            {
                $user = User::where('token', $request->cookie('user.token'))->first();
                $request->session()->put('user.id', $user->id);
            }
        }
        return $next($request);
    }
}

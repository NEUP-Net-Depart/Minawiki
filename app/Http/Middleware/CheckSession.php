<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Cookie;

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
            if(!empty($request->cookie('user.token')) && User::where('token', $request->cookie('user.token'))->count() > 0)
            {
                $user = User::where('token', $request->cookie('user.token'))->first();
                $request->session()->put('user.id', $user->id);
                $request->session()->put('user.tel', $user->tel);
                $request->session()->put('user.theme', $user->theme);
                $request->session()->put('user.power', $user->power);
                $request->session()->put('user.admin', $user->admin_name);
                $request->session()->put('user.sessionReality', false);
            }
        }
        return $next($request);
    }
}

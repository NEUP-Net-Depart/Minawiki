<?php

namespace App\Http\Middleware;

use Closure;
use App\Page;
use App\User;

class CheckIfInstalled
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
        if(Page::where('id', 1)->count() > 0 && User::where('power', '>', 2)->count() > 0)
            return $next($request);
        else if($request->getRequestUri() != '/install')
            return redirect('/install');
        else
            return $next($request);
    }
}

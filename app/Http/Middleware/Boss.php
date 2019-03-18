<?php

namespace App\Http\Middleware;

use Closure;

class Boss
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
        if (auth()->user())
        {
            if (auth()->user()->isBoss == 1)
            {
                return $next($request);
            }
        }
        return redirect('home')->with('error', 'You haven\'t got boss access rights');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;

class Employee
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
        if (auth()->user()->isEmployee == 1 || auth()->user()->isAdmin == 1)
        {
            return $next($request);
        }
        return redirect('home')->with('error', 'You have not employee access');
    }
}

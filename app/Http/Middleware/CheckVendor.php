<?php

namespace App\Http\Middleware;

use Closure;
use App\Vendor;

class CheckVendor
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
        $user = auth()->user();
        
        if ($user !== null) {
        
            $vendor = Vendor::where('user_id', $user->id)->first();

            if (!$vendor) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $next($request);
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}

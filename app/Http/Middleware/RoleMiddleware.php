<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {
        if (!Auth::check()){
            return redirect()->route('login');
        }

        if (in_array(Auth::user()->role, $role)){
            return $next($request);
        }
        
        return back()->with('fail', '403 | Forbidden');
    }
}

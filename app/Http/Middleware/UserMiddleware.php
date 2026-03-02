<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        
        if (Auth::guard('web')->check() && !Auth::user()->is_admin) {
            return $next($request);
        }

        // Check if trying to access checkout specifically
        return redirect('/login')->with('error', 'You must register with an account to finalize your purchase.');

        // return redirect('/login');

        abort(403,'you do not have access to this page');
        
    }
}

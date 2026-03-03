<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileCompleted
{
    /**
     * Handle an incoming request.
     * Redirect to complete-profile if the user has not completed their profile.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->profile_completed_at) {
            return $next($request);
        }

        if ($request->routeIs('account.complete-profile') || $request->routeIs('account.complete-profile.store')) {
            return $next($request);
        }

        return redirect()->route('account.complete-profile');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is an admin
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            // Redirect non-admin users to homepage with error message
            return redirect()->route('homepage')
                ->with('error', 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
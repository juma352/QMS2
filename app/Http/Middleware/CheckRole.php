<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * This middleware checks if the logged-in user has a specific role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role The role to check for (e.g., 'admin').
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // First, check if the user is logged in and if their role matches the required role.
        // We use the isAdmin() helper function for convenience if the required role is 'admin'.
        if (!$request->user() || !$request->user()->isAdmin()) {
             // If the user is not an admin, we stop the request and show a 403 Forbidden error.
            abort(403, 'Unauthorized Action');
        }

        // If the user has the required role, we allow the request to proceed to its destination.
        return $next($request);
    }
}

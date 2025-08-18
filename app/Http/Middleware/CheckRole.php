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
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if the user is logged in and has one of the required roles.
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            // If the user does not have the required role, abort with a 403 error.
            abort(403, 'Unauthorized Action');
        }

        // If the user has the required role, allow the request to proceed.
        return $next($request);
    }
}

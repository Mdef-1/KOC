<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     * roles parameter is a comma-separated list: role:admin,owner
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $user = $request->user();
        if (!$user) {
            abort(403);
        }

        $allowed = collect(explode(',', $roles))
            ->map(fn($r) => trim($r))
            ->filter()
            ->contains(fn($r) => strcasecmp($user->role ?? '', $r) === 0);

        if (!$allowed) {
            abort(403);
        }

        return $next($request);
    }
}

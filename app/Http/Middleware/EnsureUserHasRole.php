<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user() || ! $request->user()->hasAnyRole(explode('|', $role))) {
            abort(403, 'Tidak punya akses.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (empty($roles)) {
            return $next($request);
        }

        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles, true)) {
            abort(403, 'Acces non autorise.');
        }

        return $next($request);
    }
}


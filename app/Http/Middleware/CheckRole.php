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

        // Gérer les rôles passés comme paramètres (ex: 'role:admin')
        $userRole = auth()->user()->role;
        
        if (!empty($roles)) {
            // Si le premier paramètre commence par 'role:', extraire le rôle requis
            if (isset($roles[0]) && str_starts_with($roles[0], 'role:')) {
                $requiredRole = substr($roles[0], 5); // Enlever 'role:'
                if ($userRole !== $requiredRole) {
                    abort(403, 'Unauthorized action.');
                }
            } else {
                // Sinon, vérifier si le rôle de l'utilisateur est dans la liste
                if (!in_array($userRole, $roles)) {
                    abort(403, 'Unauthorized action.');
                }
            }
        }

        return $next($request);
    }
}
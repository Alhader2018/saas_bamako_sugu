<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStaffMember
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->guest(route('login'))->with('error', 'Veuillez vous connecter pour accéder à l\'espace d\'administration.');
        }

        $user = auth()->user();

        if (!$user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Votre compte a été suspendu ou désactivé.');
        }

        if (!$user->isStaff()) {
            abort(403, 'Accès refusé : vous ne disposez pas des privilèges nécessaires pour accéder au back-office.');
        }

        return $next($request);
    }
}

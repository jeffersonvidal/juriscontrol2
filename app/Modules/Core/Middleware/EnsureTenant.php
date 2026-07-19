<?php

namespace App\Modules\Core\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware EnsureTenant
 * --------------------------------------------------------
 * Garante que o usuário autenticado pertence a uma empresa válida.
 * 
 * CORREÇÃO: Verifica 'is_active' (boolean) ao invés de 'status' (enum).
 */
class EnsureTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Guard Clause: usuário não autenticado
        if (! $user) {
            return redirect()->route('login');
        }

        // Super-admin (sem company_id) tem acesso total
        if (empty($user->company_id)) {
            return $next($request);
        }

        // CORREÇÃO: Verifica 'is_active' (boolean) ao invés de 'status' (enum)
        if (! $user->company || ! $user->company->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('error', 'Sua empresa está inativa. Contate o suporte.');
        }

        return $next($request);
    }
}
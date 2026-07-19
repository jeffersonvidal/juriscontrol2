<?php

namespace App\Modules\Core\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware EnsureTenant
 * --------------------------------------------------------
 * Garante que o usuário autenticado pertence a uma empresa válida.
 * Bloqueia acesso se o usuário estiver sem company_id (exceto super-admin).
 * Regra do playbook: "NUNCA permitir acesso a registros de outra empresa".
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

        // Verifica se a empresa do usuário ainda existe e está ativa
        if (! $user->company || $user->company->status !== 'active') {
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
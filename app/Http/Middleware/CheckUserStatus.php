<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware CheckUserStatus
 * --------------------------------------------------------
 * Verifica se o usuário autenticado e sua empresa estão ativos.
 * Regra do playbook: "NUNCA permitir acesso a registros de outra empresa"
 * (estendido para: NUNCA permitir acesso de user/empresa inativos).
 *
 * Guard Clauses (Fail Fast):
 *  1. Usuário inativo → logout + mensagem
 *  2. Empresa inativa → logout + mensagem
 */
class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Guard Clause: usuário não autenticado
        if (! $user) {
            return redirect()->route('login');
        }

        // Guard Clause: super-admin (sem company_id) bypass
        if (empty($user->company_id)) {
            return $next($request);
        }

        // Guard Clause: usuário inativo
        if ($user->status !== 'active') {
            return $this->logoutWithMessage($request, 'Sua conta foi desativada. Contate o administrador.');
        }

        // Guard Clause: empresa inativa ou inexistente
        if (! $user->company || $user->company->status !== 'active') {
            return $this->logoutWithMessage($request, 'Sua empresa está inativa. Contate o suporte.');
        }

        return $next($request);
    }

    /**
     * Realiza logout e redireciona com mensagem de erro.
     */
    private function logoutWithMessage(Request $request, string $message): \Illuminate\Http\RedirectResponse
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('error', $message);
    }
}
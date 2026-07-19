<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware CheckUserStatus
 * --------------------------------------------------------
 * Verifica se o usuário autenticado e sua empresa estão ativos.
 * 
 * CORREÇÃO: Adiciona debug para identificar o problema.
 */
class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (empty($user->company_id)) {
            return $next($request);
        }

        if ($user->status !== 'active') {
            return $this->logoutWithMessage($request, 'Sua conta foi desativada. Contate o administrador.');
        }

        // DEBUG: Log temporário para identificar o problema
        \Log::debug('CheckUserStatus Debug', [
            'user_id' => $user->id,
            'company_id' => $user->company_id,
            'company' => $user->company ? [
                'id' => $user->company->id,
                'trade_name' => $user->company->trade_name,
                'is_active' => $user->company->is_active,
                'is_active_type' => gettype($user->company->is_active),
            ] : null,
        ]);

        // CORREÇÃO: Verifica 'is_active' (boolean)
        if (! $user->company || ! $user->company->is_active) {
            return $this->logoutWithMessage($request, 'Sua empresa está inativa. Contate o suporte.');
        }

        return $next($request);
    }

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
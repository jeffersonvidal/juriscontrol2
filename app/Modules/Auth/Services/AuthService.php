<?php

namespace App\Modules\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * AuthService
 * --------------------------------------------------------
 * Camada de negócio do login.
 * Regra do playbook: "Controller NUNCA contém regras de negócio".
 * 
 * CORREÇÃO: verifica 'is_active' (boolean) ao invés de 'status' (enum).
 */
class AuthService
{
    /**
     * Autentica o usuário.
     */
    public function login(string $email, string $password): array
    {
        // ============================================================
        // Guard Clause 1: buscar usuário por e-mail
        // ============================================================
        $user = User::withoutGlobalScopes()->where('email', $email)->first();

        // Guard Clause 2: usuário não existe
        if (! $user) {
            return [
                'success'  => false,
                'message'  => 'E-mail ou senha inválidos.',
                'redirect' => null,
            ];
        }

        // ============================================================
        // Guard Clause 3: verificar senha
        // ============================================================
        if (! Hash::check($password, $user->password)) {
            return [
                'success'  => false,
                'message'  => 'E-mail ou senha inválidos.',
                'redirect' => null,
            ];
        }

        // ============================================================
        // Guard Clause 4: usuário inativo
        // ============================================================
        if ($user->status !== 'active') {
            return [
                'success'  => false,
                'message'  => 'Sua conta está inativa. Contate o administrador.',
                'redirect' => null,
            ];
        }

        // ============================================================
        // Guard Clause 5: empresa inativa
        // CORREÇÃO: verifica 'is_active' (boolean) ao invés de 'status' (enum)
        // ============================================================
        if (! empty($user->company_id)) {
            $company = $user->company;

            if (! $company) {
                return [
                    'success'  => false,
                    'message'  => 'Empresa não encontrada. Contate o suporte.',
                    'redirect' => null,
                ];
            }

            // CORREÇÃO: campo 'is_active' (boolean) ao invés de 'status' (enum)
            if (! $company->is_active) {
                return [
                    'success'  => false,
                    'message'  => 'Sua empresa está inativa. Contate o suporte.',
                    'redirect' => null,
                ];
            }
        }

        // ============================================================
        // Autenticação bem-sucedida
        // ============================================================
        Auth::login($user, remember: false);

        // Regenera session (prevenção contra session fixation)
        request()->session()->regenerate();

        return [
            'success'  => true,
            'message'  => 'Bem-vindo, ' . $user->name . '!',
            'redirect' => route('dashboard'),
        ];
    }

    /**
     * Realiza o logout do usuário.
     */
    public function logout(): void
    {
        Auth::logout();

        // Invalida a sessão atual
        request()->session()->invalidate();

        // Regenera o token CSRF (segurança)
        request()->session()->regenerateToken();
    }
}
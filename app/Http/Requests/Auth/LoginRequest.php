<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * LoginRequest
 * --------------------------------------------------------
 * Validação do formulário de login.
 * Regras do playbook:
 *  - "Método authorize() em TODO Form Request"
 *  - "Validação de company_id em Form Requests" (aqui: email existe e está ativo)
 *  - Guard Clauses (Fail Fast)
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Regra: qualquer visitante pode tentar login (a validação de credenciais
     * acontece no AuthService).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação.
     */
    public function rules(): array
    {
        return [
            'email'    => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    /**
     * Mensagens customizadas (pt-BR).
     */
    public function messages(): array
    {
        return [
            'email.required'    => 'O e-mail é obrigatório.',
            'email.email'       => 'Informe um e-mail válido.',
            'email.max'         => 'O e-mail deve ter no máximo 255 caracteres.',
            'password.required' => 'A senha é obrigatória.',
            'password.min'      => 'A senha deve ter no mínimo 6 caracteres.',
        ];
    }
}
<?php

namespace App\Modules\Companies\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreCompanyRequest
 * --------------------------------------------------------
 * Validação para criação de empresa + endereços.
 * Regra do playbook: "Método authorize() em TODO Form Request".
 * 
 * ALTERAÇÕES:
 *  - Campos ajustados para nova estrutura (trade_name, corporate_reason, cnpj_cpf, is_active)
 *  - Validação de endereços (array) embutida
 */
class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can(Permission::COMPANIES_CREATE->value);
    }

    public function rules(): array
    {
        return [
            // Dados da empresa
            'trade_name'       => ['required', 'string', 'max:255'],
            'corporate_reason' => ['required', 'string', 'max:255'],
            'cnpj_cpf'         => ['required', 'string', 'max:18', 'unique:companies,cnpj_cpf'],
            'email'            => ['required', 'email', 'max:255', 'unique:companies,email'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'is_active'        => ['required', 'boolean'],

            // Endereços (array opcional)
            'addresses'                => ['nullable', 'array'],
            'addresses.*.label'        => ['nullable', 'string', 'max:50'],
            'addresses.*.street'       => ['required_with:addresses', 'string', 'max:255'],
            'addresses.*.number'       => ['nullable', 'string', 'max:20'],
            'addresses.*.complement'   => ['nullable', 'string', 'max:100'],
            'addresses.*.district'     => ['nullable', 'string', 'max:100'],
            'addresses.*.city'         => ['required_with:addresses', 'string', 'max:100'],
            'addresses.*.state'        => ['required_with:addresses', 'string', 'max:2'],
            'addresses.*.zip_code'     => ['required_with:addresses', 'string', 'max:10'],
            'addresses.*.country'      => ['nullable', 'string', 'max:50'],
            'addresses.*.is_default'   => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'cnpj_cpf.unique'    => 'Este documento (CNPJ/CPF) já está cadastrado no sistema.',
            'email.unique'       => 'Este e-mail já está cadastrado no sistema.',
            'is_active.required' => 'O status é obrigatório.',
            'addresses.*.street.required_with'   => 'A rua é obrigatória quando há endereços.',
            'addresses.*.city.required_with'     => 'A cidade é obrigatória quando há endereços.',
            'addresses.*.state.required_with'    => 'O estado é obrigatório quando há endereços.',
            'addresses.*.zip_code.required_with' => 'O CEP é obrigatório quando há endereços.',
        ];
    }
}
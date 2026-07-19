<?php

namespace App\Modules\Companies\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * UpdateCompanyRequest
 * --------------------------------------------------------
 * Validação para atualização de empresa + endereços.
 */
class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can(Permission::COMPANIES_UPDATE->value);
    }

    public function rules(): array
    {
        $companyId = $this->route('company')->id;

        return [
            // Dados da empresa
            'trade_name'       => ['required', 'string', 'max:255'],
            'corporate_reason' => ['required', 'string', 'max:255'],
            'cnpj_cpf'         => ['required', 'string', 'max:18', Rule::unique('companies', 'cnpj_cpf')->ignore($companyId)],
            'email'            => ['required', 'email', 'max:255', Rule::unique('companies', 'email')->ignore($companyId)],
            'phone'            => ['nullable', 'string', 'max:20'],
            'is_active'        => ['required', 'boolean'],

            // Endereços (array opcional)
            'addresses'                => ['nullable', 'array'],
            'addresses.*.id'           => ['nullable', 'integer', 'exists:company_addresses,id'],
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
            'addresses.*._destroy'     => ['nullable', 'boolean'], // Flag para marcar endereços para exclusão
        ];
    }

    public function messages(): array
    {
        return [
            'cnpj_cpf.unique' => 'Este documento (CNPJ/CPF) já está cadastrado no sistema.',
            'email.unique'    => 'Este e-mail já está cadastrado no sistema.',
        ];
    }
}
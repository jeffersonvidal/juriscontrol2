<?php

namespace App\Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Modules\Core\Validators\CnpjValidator;

class ClientRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     */
    public function authorize(): bool
    {
        return auth()->check(); // A autorização granular é feita pelo Policy
    }

    /**
     * Regras de validação.
     */
    public function rules(): array
    {
        // Garante que o company_id da requisição seja usado para validação única (Multi-Tenancy)
        $companyId = auth()->user()->company_id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'cnpj' => [
                'required',
                'string',
                'max:18',
                // Validação customizada usando a classe CnpjValidator
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (!CnpjValidator::isValid($value)) {
                        $fail('O CNPJ informado é inválido ou não corresponde ao padrão numérico/alfanumérico.');
                    }
                },
                // Garante unicidade considerando o isolamento do tenant
                Rule::unique('clients', 'cnpj')->where('company_id', $companyId),
            ],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Prepara os dados para validação.
     */
    protected function prepareForValidation(): void
    {
        // Garante que checkboxes sejam tratados corretamente como booleano
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
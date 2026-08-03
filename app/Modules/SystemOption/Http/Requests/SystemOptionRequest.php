<?php

namespace App\Modules\SystemOption\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SystemOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // A autorização detalhada é cuidada pelo Policy via authorizeResource no Controller
        return Auth::check();
    }

    public function rules(): array
    {
        $companyId = Auth::user()->company_id;
        $optionId = $this->route('system_option') ? $this->route('system_option')->id : null;

        return [
            'option_name' => [
                'required',
                'string',
                'max:255',
                // Garante unicidade do nome da opção dentro do contexto da empresa (ou global)
                Rule::unique('system_options', 'option_name')
                    ->where('company_id', $companyId)
                    ->ignore($optionId),
            ],
            'option_value' => 'nullable|string',
            'option_description' => 'nullable|string',
            'option_status' => 'required|boolean',
        ];
    }

    public function prepareForValidation(): void
    {
        // Trata checkbox para garantir que seja enviado como booleano válido
        if ($this->has('option_status')) {
            $this->merge([
                'option_status' => filter_var($this->option_status, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'option_name.unique' => 'Já existe uma opção com este nome para sua empresa.',
            'option_name.required' => 'O nome da opção é obrigatório.',
        ];
    }
}
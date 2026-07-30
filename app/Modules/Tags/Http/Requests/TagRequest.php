<?php

namespace App\Modules\Tags\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $routeParameter = $this->route('tag');
        $tagId = is_object($routeParameter) ? $routeParameter->id : $routeParameter;
        $companyId = auth()->user()->company_id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tags', 'name')
                    ->ignore($tagId)
                    ->where('company_id', $companyId)
            ],
            'color' => [
                'required',
                'string',
                'regex:/^#[0-9A-Fa-f]{6}$/'
            ],
            'is_active' => 'boolean', // Aceita 0, 1, '0', '1', true, false
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O título da tag é obrigatório.',
            'name.unique'   => 'Já existe uma tag com este nome nesta empresa.',
            'color.required'=> 'A cor da tag é obrigatória.',
            'color.regex'   => 'A cor deve ser um código HEX válido (Ex: #1A73E8).',
        ];
    }
    
    /**
     * Prepara os dados antes da validação.
     * Garante que is_active seja tratado como booleano, mesmo que venha como string '0' ou '1'.
     */
    public function prepareForValidation()
    {
        // Se o campo não existir (caso extremo), define como false (inativo)
        if (!$this->has('is_active')) {
            $this->merge(['is_active' => false]);
        } else {
            // Converte explicitamente para booleano (0, '0', false viram false; 1, '1', true viram true)
            $this->merge([
                'is_active' => filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN)
            ]);
        }
    }
}
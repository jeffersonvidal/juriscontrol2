<?php
namespace App\Modules\SystemOptions\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        // Validamos apenas o campo que realmente pode ser alterado pelo usuário
        return [
            'option_value' => 'nullable|string|max:1000',
        ];
    }
}
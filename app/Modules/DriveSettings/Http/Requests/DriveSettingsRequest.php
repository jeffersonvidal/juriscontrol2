<?php

namespace App\Modules\DriveSettings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriveSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // 'nullable' permite que o campo esteja vazio se o outro for preenchido
            'credentials_file' => 'nullable|file|mimes:json,txt|max:20',
            'credentials_json' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'credentials_file.file' => 'O arquivo deve ser um arquivo válido.',
            'credentials_file.mimes' => 'O arquivo deve ser do tipo JSON ou TXT.',
            'credentials_file.max' => 'O arquivo não pode ter mais que 20KB.',
        ];
    }
}
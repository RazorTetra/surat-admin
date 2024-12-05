<?php
// app/Http/Requests/LetterTemplateRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LetterTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50'],
            'content' => ['required', 'string'],
            'variables' => ['nullable', 'array'],
            'variables.*' => ['string'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:incoming,outgoing'],
            'is_active' => ['boolean']
        ];

        if ($this->isMethod('POST')) {
            $rules['code'][] = 'unique:letter_templates';
        } else {
            $rules['code'][] = 'unique:letter_templates,code,' . $this->route('id');
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama template harus diisi.',
            'code.required' => 'Kode template harus diisi.',
            'code.unique' => 'Kode template sudah digunakan.',
            'content.required' => 'Konten template harus diisi.',
            'type.required' => 'Jenis template harus dipilih.',
            'type.in' => 'Jenis template tidak valid.',
            'variables.*.string' => 'Variabel harus berupa teks.',
        ];
    }
}
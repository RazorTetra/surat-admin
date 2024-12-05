<?php
// app/Http/Requests/OutgoingLetterRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OutgoingLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'reference_number' => ['required', 'string', 'max:255'],
            'recipient' => ['required', 'string', 'max:255'],
            'recipient_address' => ['required', 'string'],
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'letter_date' => ['required', 'date'],
            'type' => ['required', 'in:official,personal,confidential'],
            'priority' => ['required', 'in:high,medium,low'],
            'notes' => ['nullable', 'string'],
            'attachments.*' => ['nullable', 'file', 'max:10240']
        ];

        if ($this->isMethod('POST')) {
            $rules['reference_number'][] = 'unique:outgoing_letters';
        } else {
            $rules['reference_number'][] = 'unique:outgoing_letters,reference_number,' . $this->route('id');
            $rules['status'] = ['required', 'in:draft,review,approved,sent'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'reference_number.required' => 'Nomor surat harus diisi.',
            'reference_number.unique' => 'Nomor surat sudah digunakan.',
            'recipient.required' => 'Penerima harus diisi.',
            'recipient_address.required' => 'Alamat penerima harus diisi.',
            'subject.required' => 'Perihal surat harus diisi.',
            'content.required' => 'Isi surat harus diisi.',
            'letter_date.required' => 'Tanggal surat harus diisi.',
            'letter_date.date' => 'Format tanggal surat tidak valid.',
            'type.required' => 'Jenis surat harus dipilih.',
            'type.in' => 'Jenis surat tidak valid.',
            'priority.required' => 'Prioritas harus dipilih.',
            'priority.in' => 'Prioritas tidak valid.',
            'attachments.*.max' => 'Ukuran lampiran tidak boleh lebih dari 10MB.',
        ];
    }
}
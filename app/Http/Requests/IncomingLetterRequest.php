<?php
// app/Http/Requests/IncomingLetterRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomingLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'reference_number' => ['required', 'string', 'max:255'],
            'origin' => ['required', 'string', 'max:255'],
            'sender' => ['required', 'string', 'max:255'],
            'recipient' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'letter_date' => ['required', 'date'],
            'received_date' => ['required', 'date'],
            'type' => ['required', 'in:official,personal,confidential'],
            'priority' => ['required', 'in:high,medium,low'],
            'notes' => ['nullable', 'string'],
            'attachments.*' => ['nullable', 'file', 'max:10240']
        ];

        if ($this->isMethod('POST')) {
            $rules['reference_number'][] = 'unique:incoming_letters';
        } else {
            $rules['reference_number'][] = 'unique:incoming_letters,reference_number,' . $this->route('id');
            $rules['status'] = ['required', 'in:received,processed,completed'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'reference_number.required' => 'Nomor surat harus diisi.',
            'reference_number.unique' => 'Nomor surat sudah digunakan.',
            'origin.required' => 'Asal surat harus diisi.',
            'sender.required' => 'Pengirim harus diisi.',
            'recipient.required' => 'Penerima harus diisi.',
            'subject.required' => 'Perihal surat harus diisi.',
            'letter_date.required' => 'Tanggal surat harus diisi.',
            'letter_date.date' => 'Format tanggal surat tidak valid.',
            'received_date.required' => 'Tanggal terima harus diisi.',
            'received_date.date' => 'Format tanggal terima tidak valid.',
            'type.required' => 'Jenis surat harus dipilih.',
            'type.in' => 'Jenis surat tidak valid.',
            'priority.required' => 'Prioritas harus dipilih.',
            'priority.in' => 'Prioritas tidak valid.',
            'attachments.*.max' => 'Ukuran lampiran tidak boleh lebih dari 10MB.',
        ];
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKuesionerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'unsur_id' => 'required|exists:unsurs,id',
            'village_id' => 'nullable|exists:villages,id',
            'question' => 'required|array|min:1',
            'question.*' => 'required|string|max:255',
            'start_date' => 'nullable|date', // HAPUS after_or_equal:today
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'unsur_id.required' => 'Unsur pelayanan wajib dipilih.',
            'question.required' => 'Minimal satu pertanyaan harus diisi.',
            'question.*.required' => 'Pertanyaan tidak boleh kosong.',
            'end_date.after_or_equal' => 'Tanggal berakhir harus setelah atau sama dengan tanggal mulai.',
        ];
    }
}

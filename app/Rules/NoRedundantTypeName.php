<?php

namespace App\Rules;

use App\Models\SatkerType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoRedundantTypeName implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $satkerTypeId = request()->input('satker_type_id');
        if (!$satkerTypeId) {
            return; // Tidak bisa divalidasi tanpa jenis satker
        }

        $satkerType = SatkerType::find($satkerTypeId);
        if (!$satkerType) {
            return; // Jenis satker tidak ditemukan
        }

        // Ambil kata pertama dari jenis satker (misal: "Kecamatan")
        $typeName = explode(' ', $satkerType->name)[0];

        // Cek jika nama yang diinput diawali dengan jenisnya (case-insensitive)
        if (str_starts_with(strtolower($value), strtolower($typeName))) {
            $fail('Nama Satuan Kerja tidak boleh diawali dengan jenisnya (contoh: untuk "Kecamatan Garut Kota", cukup tulis "Garut Kota").');
        }
    }
}

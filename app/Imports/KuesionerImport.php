<?php

namespace App\Imports;

use App\Models\Kuesioner;
use App\Models\Unsur;
use App\Models\Village;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Ramsey\Uuid\Uuid;

class KuesionerImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Lewati baris jika pertanyaan atau unsur kosong
        if (empty($row['pertanyaan']) || empty($row['unsur'])) {
            return null;
        }

        // Cari atau buat unsur baru
        $unsur = Unsur::firstOrCreate(
            ['unsur' => trim($row['unsur'])],
            ['uuid' => Uuid::uuid4()->toString()]
        );

        $village_id = null;
        $user = Auth::user();

        // Tentukan Satuan Kerja
        if ($user->role === 'admin') {
            // Jika admin, cari satker dari kolom excel. Jika tidak ada, jadi kuesioner global.
            if (!empty($row['satuan_kerja'])) {
                $village = Village::where('name', trim($row['satuan_kerja']))->first();
                $village_id = $village ? $village->id : null;
            }
        } else if ($user->role === 'satker') {
            // Jika satker, otomatis gunakan satker miliknya.
            $village_id = $user->village_id;
        }

        return new Kuesioner([
            'uuid' => Uuid::uuid4()->toString(),
            'unsur_id' => $unsur->id,
            'question' => trim($row['pertanyaan']),
            'village_id' => $village_id,
        ]);
    }
}

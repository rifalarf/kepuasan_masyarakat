<?php

namespace Database\Seeders;

use App\Models\SatkerType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SatkerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Sekretariat / Instansi / Badan / Dinas',
            'Kecamatan',
            'Kelurahan',
            'Puskesmas',
        ];

        foreach ($types as $type) {
            // Gunakan updateOrCreate untuk mencegah duplikat
            SatkerType::updateOrCreate(['name' => $type]);
        }
    }
}
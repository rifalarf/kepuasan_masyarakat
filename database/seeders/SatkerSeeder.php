<?php

namespace Database\Seeders;

use App\Models\SatkerType;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SatkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks untuk mengizinkan truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Kosongkan tabel untuk menghindari duplikasi data
        SatkerType::truncate();
        Village::truncate();

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Buat Jenis Satuan Kerja dan simpan dalam array untuk pencarian cepat
        $types = [
            'Instansi' => SatkerType::create(['name' => 'Instansi / Badan / Dinas']),
            'Kecamatan' => SatkerType::create(['name' => 'Kecamatan']),
            'Kelurahan' => SatkerType::create(['name' => 'Kelurahan']),
            'Puskesmas' => SatkerType::create(['name' => 'Puskesmas']),
        ];

        // Tentukan path ke file CSV
        $csvFile = fopen(database_path('seeders/data/daftar_nama_data_garut.csv'), 'r');

        // Lewati baris header
        fgetcsv($csvFile);

        // Loop melalui setiap baris data di file CSV
        while (($row = fgetcsv($csvFile, 2000, ',')) !== false) {
            $kategori = $row[0];
            $nama = $row[1];

            // Periksa apakah kategori ada di dalam array $types
            if (isset($types[$kategori])) {
                // Dapatkan ID dari jenis satker
                $satkerTypeId = $types[$kategori]->id;

                // Buat entri baru di tabel Village
                Village::create([
                    'satker_type_id' => $satkerTypeId,
                    'name' => $nama,
                ]);
            }
        }

        fclose($csvFile);

        // Beri pesan di konsol bahwa seeder berhasil dijalankan
        $this->command->info('SatkerSeeder berhasil dijalankan dan data dari CSV telah di-inject.');
    }
}

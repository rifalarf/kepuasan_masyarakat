<?php

namespace Database\Seeders;

use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VillageSeeder extends Seeder
{
    public function run(): void
    {
        $villages = [
            'sekretariat',
            'kecamatan',
            'kelurahan',
            'puskesmas',
            
        ];

        foreach ($villages as $village) {
            Village::create([
                'village' => $village
            ]);
        }
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;

class KuesionerTemplateExport implements WithHeadings
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'unsur',
            'pertanyaan',
            'satuan_kerja',
        ];
    }
}

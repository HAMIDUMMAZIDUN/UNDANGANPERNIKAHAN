<?php

namespace App\Exports;

use App\Models\Guest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GuestsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Pilih kolom yang mau didownload
        return Guest::select('name', 'pax', 'is_online_invited', 'is_physical_invited')->get();
    }

    public function headings(): array
    {
        return ['Nama Tamu', 'Jumlah Pax', 'Undangan Online (1=Ya)', 'Undangan Fisik (1=Ya)'];
    }
}
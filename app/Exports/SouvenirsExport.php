<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class SouvenirsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $guests;

    public function __construct($guests)
    {
        $this->guests = $guests;
    }

    public function collection()
    {
        return $this->guests;
    }

    public function headings(): array
    {
        return [
            'Nama Tamu',
            'Waktu Ambil Souvenir',
        ];
    }

    public function map($guest): array
    {
        return [
            $guest->name,
            Carbon::parse($guest->souvenir_taken_at)->isoFormat('D MMMM YYYY, HH:mm'),
        ];
    }
}

<?php

namespace App\Exports;

use App\Models\Guest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class GuestsKehadiranExport implements FromCollection, WithHeadings, WithMapping
{
    protected $guests;

    public function __construct($guests)
    {
        $this->guests = $guests;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->guests;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Mendefinisikan header kolom untuk file Excel
        return [
            'Nama Tamu',
            'Kategori',
            'Waktu Check-in',
        ];
    }

    /**
     * @param mixed $guest
     * @return array
     */
    public function map($guest): array
    {
        // Memetakan data setiap tamu ke kolom yang sesuai
        return [
            $guest->name,
            $guest->affiliation,
            $guest->check_in_time 
                ? Carbon::parse($guest->check_in_time)->isoFormat('D MMMM YYYY, HH:mm') 
                : 'Belum Hadir',
        ];
    }
}

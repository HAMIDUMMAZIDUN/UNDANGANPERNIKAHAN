<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class RsvpsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $rsvps;

    public function __construct($rsvps)
    {
        $this->rsvps = $rsvps;
    }

    public function collection()
    {
        return $this->rsvps;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Status Kehadiran',
            'Ucapan',
            'Tanggal',
        ];
    }

    public function map($rsvp): array
    {
        return [
            $rsvp->name,
            ucfirst(str_replace('_', ' ', $rsvp->status)), // Mengubah 'tidak_hadir' menjadi 'Tidak hadir'
            $rsvp->message,
            Carbon::parse($rsvp->created_at)->isoFormat('D MMMM YYYY, HH:mm'),
        ];
    }
}

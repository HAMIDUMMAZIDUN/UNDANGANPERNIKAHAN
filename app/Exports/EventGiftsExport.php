<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class EventGiftsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $gifts;

    public function __construct($gifts)
    {
        $this->gifts = $gifts;
    }

    public function collection()
    {
        return $this->gifts;
    }

    public function headings(): array
    {
        return [
            'Nama Pengirim',
            'Nominal',
            'Pesan',
            'Tanggal',
        ];
    }

    public function map($gift): array
    {
        return [
            $gift->sender_name,
            $gift->amount,
            $gift->message,
            Carbon::parse($gift->created_at)->isoFormat('D MMMM YYYY, HH:mm'),
        ];
    }
}

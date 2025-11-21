<?php

namespace App\Imports;

use App\Models\Guest;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuestsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Guest([
            // Pastikan header di Excel Anda bernama: name, pax, online, fisik
            'name'     => $row['name'], 
            'pax'      => $row['pax'] ?? 1,
            'is_online_invited' => $row['online'] ?? 0,
            'is_physical_invited' => $row['fisik'] ?? 0,
        ]);
    }
}
<?php

namespace App\Imports;

use App\Models\Guest;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GuestsImport implements ToCollection, WithStartRow
{
    private $userId;
    private $eventId;

    /**
    * Kita butuh user_id dan event_id untuk setiap tamu yang diimpor.
    * Kita akan mengirimkannya dari controller.
    */
    public function __construct(int $userId, int $eventId)
    {
        $this->userId = $userId;
        $this->eventId = $eventId;
    }

    /**
    * @return int
    */
    public function startRow(): int
    {
        // Data akan dibaca mulai dari baris ke-2 (mengabaikan header)
        return 2;
    }

    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            // Hentikan proses jika kolom nama kosong
            if (empty($row[0])) {
                continue;
            }

            Guest::create([
                'user_id'       => $this->userId,
                'event_id'      => $this->eventId,
                'name'          => $row[0], // Kolom A di Excel
                'affiliation'   => $row[1], // Kolom B di Excel
                'address'       => $row[2] ?? null, // Kolom C di Excel (Opsional)
                'phone_number'  => $row[3] ?? null, // Kolom D di Excel (Opsional)
            ]);
        }
    }
}
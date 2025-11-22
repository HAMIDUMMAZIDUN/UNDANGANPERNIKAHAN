<?php

namespace App\Imports;

use App\Models\Guest;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuestsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. LOGIKA PINTAR (Mencari berbagai kemungkinan nama kolom)
        
        // Cari kolom Nama (bisa 'name' atau 'nama_tamu')
        $name = $row['name'] ?? $row['nama_tamu'] ?? $row['nama'] ?? null;

        // Jika nama kosong/tidak ketemu, abaikan baris ini (jangan error)
        if (!$name) {
            return null;
        }

        // Cari kolom Pax (bisa 'pax' atau 'jumlah_pax')
        $pax = $row['pax'] ?? $row['jumlah_pax'] ?? 1;

        // Cari kolom Online (bisa 'online', 'is_online', atau format export panjang)
        // Catatan: Laravel Excel mengubah spasi & kurung menjadi underscore (slug)
        $online = $row['online'] ?? $row['undangan_online_1ya'] ?? $row['is_online_invited'] ?? 0;

        // Cari kolom Fisik
        $fisik = $row['fisik'] ?? $row['undangan_fisik_1ya'] ?? $row['is_physical_invited'] ?? 0;

        // 2. SIMPAN KE DATABASE
        return new Guest([
            'name'                => $name, 
            'pax'                 => $pax,
            'is_online_invited'   => $online,
            'is_physical_invited' => $fisik,
        ]);
    }
}
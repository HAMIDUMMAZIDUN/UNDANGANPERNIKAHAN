<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    /**
     * Tambahkan 'server_number', 'check_in_at', dan 'is_physical_invited'
     * agar bisa di-update melalui controller.
     */
    protected $fillable = [
        'name',
        'pax',
        'is_online_invited',
        'is_physical_invited', // Pastikan ini ada
        'server_number',       // WAJIB DITAMBAHKAN
        'check_in_at',         // WAJIB DITAMBAHKAN
    ];

    // Opsional: Cast check_in_at sebagai datetime agar format jam aman
    protected $casts = [
        'check_in_at' => 'datetime',
        'is_online_invited' => 'boolean',
        'is_physical_invited' => 'boolean',
    ];
}
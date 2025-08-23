<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // <-- Tambahkan ini

class Guest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * (Pastikan semua kolom Anda ada di sini)
     */
    protected $fillable = [
        'user_id',
        'event_id',
        'uuid',
        'name',
        'affiliation',
        'address',
        'phone_number',
        'check_in_time',
        'souvenir_taken_at',
    ];

    /**
     * The "booted" method of the model.
     * Ini akan otomatis mengisi UUID saat tamu baru dibuat.
     */
    protected static function booted(): void
    {
        static::creating(function ($guest) {
            if (empty($guest->uuid)) {
                $guest->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the route key for the model.
     * Ini memberitahu Laravel untuk menggunakan 'uuid' di URL, bukan 'id'.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}

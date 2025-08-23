<?php
// app/Models/Event.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'uuid',
        'name',
        'date',
        'photo_url',
        // Tambahkan semua kolom baru di sini
        'groom_name',
        'groom_photo',
        'groom_parents',
        'groom_instagram',
        'bride_name',
        'bride_photo',
        'bride_parents',
        'bride_instagram',
        'love_story',
        'description',
        'location',
        'location_url',
        'rekening_bank',
        'rekening_atas_nama',
        'rekening_nomor',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($event) {
            $event->uuid = Str::uuid();
        });
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}

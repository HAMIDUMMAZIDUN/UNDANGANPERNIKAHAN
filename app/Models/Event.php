<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', // Ditambahkan untuk mengizinkan mass assignment
        'name',
        'date',
        'description',
        'location',
        'photo_url',
    ];

    /**
     * Get the user that owns the event.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the guests for the event.
     */
    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
     protected static function booted(): void // <-- 2. Tambahkan method ini
    {
        static::creating(function ($event) {
            if (empty($event->uuid)) {
                $event->uuid = (string) Str::uuid();
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

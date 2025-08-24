<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
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
        'number_of_guests',
        'souvenir_taken_at',
    ];

    /**
     * The "booted" method of the model.
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
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    // --- TAMBAHKAN METHOD INI ---
    /**
     * Mendefinisikan relasi "belongsTo" ke model Event.
     * Setiap Tamu dimiliki oleh satu Event.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}

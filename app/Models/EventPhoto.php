<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'path',
    ];

    /**
     * BARU: Tambahkan relasi ini.
     * * Mendefinisikan bahwa setiap foto "milik" satu event.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
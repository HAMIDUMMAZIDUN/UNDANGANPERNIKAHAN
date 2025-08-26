<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany; 
use App\Models\Rsvp;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'uuid',
        'name',
        'date',
        'photo_url',
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
        'location',
        'akad_location',
        'akad_time',
        'akad_maps_url',
        'resepsi_location',
        'resepsi_time',
        'resepsi_maps_url',
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
    public function photos()
{
    return $this->hasMany(EventPhoto::class);
}
 public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class);
    }
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}

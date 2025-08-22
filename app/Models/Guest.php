<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'event_id',
        'name',
        'affiliation',
        'address',
        'phone_number',
        'status',
    ];

    /**
     * Get the user that owns the guest.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event that the guest belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}

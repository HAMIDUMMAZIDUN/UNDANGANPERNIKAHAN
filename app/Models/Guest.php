<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'name',
        'affiliation',
        'phone',
        'invitation_code',
        'is_attending',
    ];
}

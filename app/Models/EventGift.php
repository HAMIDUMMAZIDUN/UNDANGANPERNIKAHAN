<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class EventGift extends Model
    {
        use HasFactory;

        protected $fillable = [
            'user_id',
            'event_id',
            'sender_name',
            'amount',
            'message',
        ];
    }
    
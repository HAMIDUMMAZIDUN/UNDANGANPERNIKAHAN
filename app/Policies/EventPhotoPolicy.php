<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EventPhoto;
use Illuminate\Auth\Access\Response;

class EventPhotoPolicy
{
    // ... metode lainnya

    public function deletePhoto(User $user, EventPhoto $eventPhoto): Response
    {
    return ($eventPhoto->event && $user->id === $eventPhoto->event->user_id)
        ? Response::allow()
        : Response::deny('Anda tidak memiliki izin untuk menghapus foto ini.');
    }
}
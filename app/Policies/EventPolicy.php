<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        // Izinkan user melihat event HANYA JIKA user->id sama dengan event->user_id
        return $user->id === $event->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Umumnya, semua user yang login bisa membuat event
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        // Izinkan user mengupdate event HANYA JIKA dia pemiliknya
        return $user->id === $event->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        // Izinkan user menghapus event HANYA JIKA dia pemiliknya
        return $user->id === $event->user_id;
    }

    // ... (method lainnya bisa dibiarkan atau diisi dengan logika yang sama)
}
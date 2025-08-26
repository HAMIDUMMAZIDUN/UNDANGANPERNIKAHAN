<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Metode ini akan dijalankan sebelum metode Policy lainnya.
     * Berguna untuk admin atau superuser yang selalu diizinkan.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Contoh: Jika user adalah admin, izinkan semua aksi.
        // if ($user->isAdmin()) { // Asumsi ada method isAdmin() di model User Anda
        //     return true;
        // }
        return null; // Lanjutkan ke metode Policy spesifik
    }

    /**
     * Determine whether the user can view the model.
     * Memeriksa apakah user dapat melihat (edit) event.
     */
    public function view(User $user, Event $event): bool
    {
        return $user->id === $event->user_id
               ? Response::allow()
               : Response::deny('Anda tidak memiliki izin untuk melihat event ini.');
    }

    /**
     * Determine whether the user can update the model.
     * Memeriksa apakah user dapat memperbarui event.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->id === $event->user_id
               ? Response::allow()
               : Response::deny('Anda tidak memiliki izin untuk memperbarui event ini.');
    }

    /**
     * Determine whether the user can manage the gallery for the event.
     * Memeriksa apakah user dapat mengelola galeri foto event.
     */
     public function manageGallery(User $user, Event $event): bool
    {
        return $user->id === $event->user_id; // Cukup kembalikan true atau false
    }

    /**
     * Determine whether the user can upload photos to the event's gallery.
     * Memeriksa apakah user dapat mengunggah foto ke galeri event.
     */
     public function uploadPhoto(User $user, Event $event): Response
    {
        return $user->id === $event->user_id
               ? Response::allow()
               : Response::deny('Anda tidak memiliki izin untuk mengunggah foto ke event ini.');
    }

    // Metode deletePhoto akan ditangani oleh EventPhotoPolicy
    // Atau jika Anda ingin menanganinya di EventPolicy:
    // public function deletePhoto(User $user, Event $event, EventPhoto $photo): bool
    // {
    //     return $user->id === $event->user_id && $event->id === $photo->event_id;
    // }
}
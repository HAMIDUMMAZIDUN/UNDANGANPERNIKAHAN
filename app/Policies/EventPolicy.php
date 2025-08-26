<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;
// Hapus atau jangan gunakan Illuminate\Auth\Access\Response jika Anda hanya mengembalikan bool
// use Illuminate\Auth\Access\Response; 

class EventPolicy
{
    /**
     * Determine whether the user can view the event.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return bool
     */
    public function view(User $user, Event $event): bool
    {
        // Cukup kembalikan true atau false berdasarkan logika otorisasi Anda.
        // Di sini, kita memeriksa apakah user yang login adalah pemilik event.
        return $user->id === $event->user_id;
    }

    /**
     * Determine whether the user can update the event.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return bool
     */
    public function update(User $user, Event $event): bool
    {
        return $user->id === $event->user_id;
    }

    /**
     * Determine whether the user can manage the event gallery.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return bool
     */
    public function manageGallery(User $user, Event $event): bool
    {
        return $user->id === $event->user_id;
    }

    /**
     * Determine whether the user can upload a photo to the event gallery.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return bool
     */
    public function uploadPhoto(User $user, Event $event): bool
    {
        return $user->id === $event->user_id;
    }

    // Pastikan Anda juga memiliki metode deletePhoto di EventPhotoPolicy,
    // karena $photo adalah instance dari EventPhoto, bukan Event.
    // Jika deletePhoto ada di EventPolicy dan menerima EventPhoto, type hint-nya harus sesuai.
    // Jika EventPhotoPolicy adalah policy terpisah, maka logicnya ada di sana.
    // Untuk contoh, saya asumsikan ini di EventPolicy dan menerima EventPhoto.
    /**
     * Determine whether the user can delete a photo from the event gallery.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EventPhoto  $photo
     * @return bool
     */
    public function deletePhoto(User $user, \App\Models\EventPhoto $photo): bool
    {
        // Logika untuk memastikan user yang login adalah pemilik event dari foto tersebut
        return $user->id === optional($photo->event)->user_id;
    }

    // ... metode policy lainnya (create, delete, restore, forceDelete)
}

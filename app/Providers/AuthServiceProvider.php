<?php

namespace App\Providers;

use App\Models\Event;
use App\Policies\EventPolicy;
use App\Models\EventPhoto; // Tambahkan ini
use App\Policies\EventPhotoPolicy; // Tambahkan ini
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Event::class => EventPolicy::class,
        EventPhoto::class => EventPhotoPolicy::class, // Daftarkan EventPhotoPolicy
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
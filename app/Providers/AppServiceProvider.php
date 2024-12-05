<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use App\Models\LetterTemplate;
use App\Policies\IncomingLetterPolicy;
use App\Policies\OutgoingLetterPolicy;
use App\Policies\LetterTemplatePolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // Register policies
        Gate::policy(IncomingLetter::class, IncomingLetterPolicy::class);
        Gate::policy(OutgoingLetter::class, OutgoingLetterPolicy::class);
        Gate::policy(LetterTemplate::class, LetterTemplatePolicy::class);

        // Define gates for roles
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('staff', function ($user) {
            return $user->role === 'admin' || $user->role === 'staff';
        });
    }
}
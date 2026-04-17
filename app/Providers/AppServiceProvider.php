<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ContactMessage;
use App\Policies\ContactMessagePolicy;

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
        // Register ContactMessage policy
        \Illuminate\Support\Facades\Gate::policy(ContactMessage::class, ContactMessagePolicy::class);
    }
}

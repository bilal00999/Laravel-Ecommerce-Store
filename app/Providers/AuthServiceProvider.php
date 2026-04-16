<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Models\User;
use App\Policies\ProductPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define custom gates for authorization
        
        /**
         * 'admin' Gate
         * Check if user is admin
         * Usage: if (Gate::allows('admin')) or @can('admin') in Blade
         */
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin' || $user->is_admin;
        });

        /**
         * 'moderator' Gate
         * Check if user is admin or moderator
         */
        Gate::define('moderator', function (User $user) {
            return in_array($user->role, ['admin', 'moderator']);
        });

        /**
         * 'manage-users' Gate
         * Check if user can manage other users (admin only)
         */
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });

        /**
         * 'manage-settings' Gate
         * Check if user can access app settings (admin only)
         */
        Gate::define('manage-settings', function (User $user) {
            return $user->role === 'admin';
        });

        /**
         * 'view-analytics' Gate
         * Check if user can view analytics (admin and moderator)
         */
        Gate::define('view-analytics', function (User $user) {
            return in_array($user->role, ['admin', 'moderator']);
        });
    }
}

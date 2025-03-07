<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::before(function (User $user) {
            if ($user->role === Role::ROLE_ADMIN) {
                return true;
            }
            return null;
        });

        Gate::define('approve-user', function (User $user) {
            if ($user->role === Role::ROLE_MANAGER) {
                return true;
            }
            return null;
        });
    }
}

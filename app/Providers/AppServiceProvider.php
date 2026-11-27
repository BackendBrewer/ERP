<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

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
        Gate::define(
            'access-super-admin',
            fn (User $user): bool => (bool) $user->is_super_admin
        );
        Gate::define(
            'access-institute-admin',
            fn (User $user): bool => $user->academy_id !== null
                && $user->academy_role === User::ROLE_INSTITUTE_ADMIN
        );
    }
}

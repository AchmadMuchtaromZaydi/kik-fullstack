<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Organisasi;
use App\Models\User;
use App\Policies\OrganisasiPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Organisasi::class => OrganisasiPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Gate untuk admin
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        // Gate untuk user-kik
        Gate::define('user-kik', function ($user) {
            return $user->role === 'user-kik';
        });

        // Gate untuk akses dashboard admin
        Gate::define('access-admin-dashboard', function ($user) {
            return $user->role === 'admin';
        });
    }
}

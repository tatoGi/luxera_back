<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define gate for managing languages
        Gate::define('manage_languages', function ($user) {
            // For now, allow all authenticated users to manage languages
            // You can replace this with your actual role/permission check
            return $user !== null;
            
            // If you have roles, you can use something like:
            // return $user->hasRole('admin');
        });
    }
}

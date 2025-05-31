<?php

namespace App\Providers;

use App\Models\Agreement;
use App\Models\User;
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
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('view-messages', function (User $user, Agreement $agreement) {
            // User must be tenant or landlord, and agreement must be active (or relevant status)
            return ($user->id === $agreement->tenant->id || $user->id === $agreement->landlord->id)
                    && $agreement->status === 'active'; // Adjust 'active' if your status is different
        });

        Gate::define('send-message', function (User $user, Agreement $agreement) {
            // Same conditions as viewing, typically
            return ($user->id === $agreement->tenant->id || $user->id === $agreement->landlord->id)
                    && $agreement->status === 'active'; // Adjust 'active' if your status is different
        });
    }
}

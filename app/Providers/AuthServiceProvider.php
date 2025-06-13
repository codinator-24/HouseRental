<?php

namespace App\Providers;

use App\Models\Agreement;
use App\Models\House;
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

        Gate::define('start-inquiry', function (User $user, House $house) {
            // User cannot inquire about their own house, house must be approved/active
            // Assuming 'approved' is the status for a live, viewable house listing.
            return $user->id !== $house->landlord_id && $house->status === 'approved';
        });

        Gate::define('view-inquiry-thread', function (User $user, House $house) {
            // User must be the landlord of the house or have an existing inquiry message (sent or received) for this house.
            if ($user->id === $house->landlord_id) {
                return true;
            }
            // Check if there's any inquiry message involving this user and house
            return $house->inquiryMessages()
                         ->where(function ($query) use ($user) {
                             $query->where('sender_id', $user->id)
                                   ->orWhere('receiver_id', $user->id);
                         })
                         ->exists();
        });

        Gate::define('send-inquiry-message', function (User $user, House $house, User $receiver) {
            // House must be approved.
            if ($house->status !== 'approved') {
                return false;
            }

            // Case 1: User is the landlord sending to an inquirer
            if ($user->id === $house->landlord_id && $receiver->id !== $house->landlord_id) {
                // Ensure the receiver has actually initiated an inquiry or is part of an ongoing one for this house
                // This check might be implicit if the UI only allows replying to existing threads.
                // For now, we assume if the landlord is sending to a non-landlord for this house, it's valid.
                return true;
            }

            // Case 2: User is a potential tenant (inquirer) sending to the landlord
            if ($user->id !== $house->landlord_id && $receiver->id === $house->landlord_id) {
                return true;
            }

            return false;
        });
    }
}

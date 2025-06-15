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

        // New, more specific gates for inquiry threads:

        Gate::define('initiate-inquiry-with-landlord', function(User $user, House $house) {
            // User can start an inquiry if they are not the landlord and the house is available.
            return $user->id !== $house->landlord_id && $house->status === 'available';
        });

        Gate::define('view-specific-inquiry-thread', function(User $user, House $house, User $otherUserInThread) {
            // $user: Currently authenticated user.
            // $house: The house in question.
            // $otherUserInThread: The other participant named in the route.

            $landlordOfHouse = $house->landlord;

            // Ensure $otherUserInThread is distinct from the landlord for a valid inquiry context.
            // (An inquiry is between landlord and someone else).
            // This check is implicitly handled if the link generation is correct,
            // but as a safeguard, if $otherUserInThread IS the landlord, then $user must NOT be the landlord.
            // If $otherUserInThread is NOT the landlord, then $user can be the landlord.

            // Scenario 1: Current user ($user) is the landlord of the house,
            // AND $otherUserInThread is an inquirer (i.e., not the landlord).
            if ($user->id === $landlordOfHouse->id && $otherUserInThread->id !== $landlordOfHouse->id) {
                return true;
            }

            // Scenario 2: Current user ($user) is an inquirer (i.e., not the landlord),
            // AND $otherUserInThread is the landlord of the house.
            // This means the current user is trying to view their conversation with the landlord.
            if ($user->id !== $landlordOfHouse->id && $otherUserInThread->id === $landlordOfHouse->id) {
                // To be absolutely sure this is the correct inquirer for this thread context,
                // ensure the current user ($user) is the one who is supposed to be
                // the "other party" when the landlord ($otherUserInThread) is specified in the route.
                // This is implicitly true if the route was /messages/inquiry/house/{house}/with/{landlord}
                // and the current user is the inquirer.
                // The key is that $user is one party, and $otherUserInThread is the other.
                // If $user is the inquirer, then $otherUserInThread must be the landlord.
                return true;
            }
            
            return false;
        });

        Gate::define('send-specific-inquiry-message', function(User $user, House $house, User $receiverInThread) {
            // House must be available to send messages.
            if ($house->status !== 'available') {
                return false;
            }

            // The sender ($user) must be authorized to view this thread with $receiverInThread for this $house.
            // This re-uses the logic of being one of the two parties.
            if ($user->id === $house->landlord_id && $receiverInThread->id !== $house->landlord_id) {
                // Landlord sending to the specific inquirer ($receiverInThread)
                return true;
            }
            if ($user->id === $receiverInThread->id) {
                // This case is wrong: user cannot be the receiver they are sending to.
                // This implies an inquirer ($user) is sending to the landlord ($receiverInThread should be landlord)
                // Let's re-evaluate: $user is sender, $receiverInThread is the recipient.
                // If $user is inquirer, $receiverInThread must be $house->landlord_id
                // If $user is landlord, $receiverInThread must be an inquirer (not $house->landlord_id)
                 return false; // Should not happen, sender cannot be receiver.
            }

            // Corrected logic:
            // Case 1: Sender is the landlord, receiver is the otherUser (inquirer)
            if ($user->id === $house->landlord_id && $receiverInThread->id !== $house->landlord_id) {
                return true;
            }
            // Case 2: Sender is an inquirer ($user not landlord), receiver is the landlord
            if ($user->id !== $house->landlord_id && $receiverInThread->id === $house->landlord_id) {
                return true;
            }
            
            return false;
        });


        // Old gates - can be commented out or removed once new ones are confirmed working.
        // Gate::define('start-inquiry', function (User $user, House $house) {
        //     return $user->id !== $house->landlord_id && $house->status === 'available';
        // });

        // Gate::define('view-inquiry-thread', function (User $user, House $house) {
        //     if ($user->id === $house->landlord_id) {
        //         return true;
        //     }
        //     $existingThread = $house->inquiryMessages()
        //                          ->where(function ($query) use ($user) {
        //                              $query->where('sender_id', $user->id)
        //                                    ->orWhere('receiver_id', $user->id);
        //                          })
        //                          ->exists();
        //     if ($existingThread) {
        //         return true;
        //     }
        //     return $user->id !== $house->landlord_id && $house->status === 'available';
        // });

        // Gate::define('send-inquiry-message', function (User $user, House $house, User $receiver) {
        //     if ($house->status !== 'available') {
        //         return false;
        //     }
        //     if ($user->id === $house->landlord_id && $receiver->id !== $house->landlord_id) {
        //         return true;
        //     }
        //     if ($user->id !== $house->landlord_id && $receiver->id === $house->landlord_id) {
        //         return true;
        //     }
        //     return false;
        // });
    }
}

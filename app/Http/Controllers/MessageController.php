<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\House; // Added for inquiries
use App\Models\Message;
use App\Models\User; // Added for inquiries
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB; // Added for DB operations in allMessagesOverview
use App\Notifications\NewInquiryMessage; // Added for notifications

class MessageController extends Controller
{
    /**
     * Display a listing of the messages for a specific agreement.
     *
     * @param  \App\Models\Agreement  $agreement
     * @return \Illuminate\Http\Response
     */
    public function index(Agreement $agreement)
    {
        // Authorization: Ensure the user is part of the agreement and agreement is active
        if (!Gate::allows('view-messages', $agreement)) {
            abort(403, 'Unauthorized action or agreement not active.');
        }

        $messages = $agreement->messages()
                            ->with(['sender', 'receiver'])
                            ->orderBy('created_at', 'asc')
                            ->get();

        // Mark messages as read for the current user
        $agreement->messages()
                  ->where('receiver_id', Auth::id())
                  ->whereNull('read_at')
                  ->update(['read_at' => now()]);

        return view('messages.index', [
            'agreement' => $agreement,
            'messages' => $messages,
            'tenant' => $agreement->tenant, // Eager load if not already
            'landlord' => $agreement->landlord, // Eager load if not already
        ]);
    }

    /**
     * Store a newly created message in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agreement  $agreement
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Agreement $agreement)
    {
        // Authorization: Ensure the user can send messages for this agreement
        if (!Gate::allows('send-message', $agreement)) {
            abort(403, 'Unauthorized action or agreement not active.');
        }

        $request->validate([
            'content' => 'required|string|max:5000', // Max length for a message
        ]);

        $sender_id = Auth::id();
        $receiver_id = null;

        if ($sender_id == $agreement->tenant->id) {
            $receiver_id = $agreement->landlord->id;
        } elseif ($sender_id == $agreement->landlord->id) {
            $receiver_id = $agreement->tenant->id;
        } else {
            // This case should ideally not be reached if Gate authorization is correct
            abort(403, 'Sender is not part of this agreement.');
        }

        Message::create([
            'agreement_id' => $agreement->id,
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'content' => $request->content,
        ]);

        return redirect()->route('agreements.messages.index', $agreement)
                         ->with('success', 'Message sent successfully!');
    }

    /**
     * Display an overview of all message threads for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function allMessagesOverview()
    {
        $user = Auth::user();
        $agreementsAsTenant = Agreement::whereHas('booking', function ($query) use ($user) {
            $query->where('tenant_id', $user->id);
        })->where('status', 'active')->with(['booking.house.landlord', 'booking.tenant', 'messages' => function ($query) {
            $query->orderBy('created_at', 'desc'); // Get latest message for preview if needed
        }, 'messages.sender'])->get();

        $agreementsAsLandlord = Agreement::whereHas('booking.house', function ($query) use ($user) {
            $query->where('landlord_id', $user->id);
        })->where('status', 'active')->with(['booking.house.landlord', 'booking.tenant', 'messages' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'messages.sender'])->get();

        // Combine and ensure uniqueness if a user could somehow be both tenant and landlord on the same agreement (unlikely but good practice)
        $allAgreements = $agreementsAsTenant->merge($agreementsAsLandlord)->unique('id');

        // Fetch distinct inquiry threads
        // An inquiry thread is defined by the current user and a specific house they are inquiring about, or receiving inquiries for.
        $inquiryHouseIds = Message::whereNull('agreement_id')
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->distinct()
            ->pluck('house_id');

        $inquiryThreads = House::whereIn('id', $inquiryHouseIds)
            ->with(['landlord', 'inquiryMessages' => function ($query) use ($user) {
                $query->where(function ($subQuery) use ($user) {
                          $subQuery->where('sender_id', $user->id)
                                   ->orWhere('receiver_id', $user->id);
                      })
                      ->orderBy('created_at', 'desc') // For fetching the latest message easily
                      ->with(['sender', 'receiver']); // Eager load sender/receiver for messages
            }])
            ->get()
            ->map(function ($house) use ($user) {
                // Determine the other party for the thread title/context
                if ($house->landlord_id === $user->id) {
                    // User is the landlord, find the latest inquirer
                    $latestMessageToOrFromLandlord = $house->inquiryMessages
                        ->where('receiver_id', $user->id) // Messages sent to landlord
                        ->first();
                    if ($latestMessageToOrFromLandlord) {
                        $house->otherParty = $latestMessageToOrFromLandlord->sender;
                    } else {
                         // Or messages sent by landlord (less likely to determine "other party" this way for a thread list)
                        $latestMessageByLandlord = $house->inquiryMessages->where('sender_id', $user->id)->first();
                        $house->otherParty = $latestMessageByLandlord ? $latestMessageByLandlord->receiver : null;
                    }
                } else {
                    // User is an inquirer
                    $house->otherParty = $house->landlord;
                }
                $house->latestMessage = $house->inquiryMessages->first(); // Already ordered by desc
                return $house;
            });

        // Combine all threads (agreements and inquiries)
        $allThreads = collect();

        foreach ($allAgreements as $agreement) {
            $allThreads->push((object)[
                'type' => 'agreement',
                'thread_id' => 'agreement_' . $agreement->id,
                'item' => $agreement,
                'otherParty' => ($user->id === $agreement->tenant->id) ? $agreement->landlord : $agreement->tenant,
                'latest_message_at' => $agreement->messages->first()->created_at ?? $agreement->updated_at,
                'title' => 'Conversation for Agreement #' . $agreement->id . ' with ' . (($user->id === $agreement->tenant->id) ? $agreement->landlord->user_name : $agreement->tenant->user_name),
                'link' => route('agreements.messages.index', $agreement),
                'latestMessage' => $agreement->messages->first()
            ]);
        }

        foreach ($inquiryThreads as $inquiry) {
            if (!$inquiry->latestMessage) continue; // Skip if no messages somehow

            $otherPartyName = $inquiry->otherParty ? $inquiry->otherParty->user_name : 'Unknown';
            $allThreads->push((object)[
                'type' => 'inquiry',
                'thread_id' => 'inquiry_house_' . $inquiry->id . '_user_' . ($inquiry->landlord_id === $user->id ? $inquiry->latestMessage->sender_id : $inquiry->landlord_id),
                'item' => $inquiry, // This is the House model
                'otherParty' => $inquiry->otherParty,
                'latest_message_at' => $inquiry->latestMessage->created_at,
                'title' => 'Inquiry about ' . $inquiry->title . ' with ' . $otherPartyName,
                'link' => route('houses.inquiry.show', $inquiry),
                'latestMessage' => $inquiry->latestMessage
            ]);
        }

        $sortedThreads = $allThreads->sortByDesc('latest_message_at');
        
        return view('messages.overview', ['threads' => $sortedThreads]);
    }

    /**
     * Display the inquiry form and message thread for a specific house.
     *
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function showInquiryForm(House $house)
    {
        // Gate::authorize('start-inquiry', $house); // Allows starting a new one
        // More accurately, for viewing an existing/new thread:
        Gate::authorize('view-inquiry-thread', $house);


        $currentUser = Auth::user();
        $landlord = $house->landlord;

        $messages = Message::where('house_id', $house->id)
                           ->whereNull('agreement_id')
                           ->where(function ($query) use ($currentUser, $landlord) {
                               $query->where('sender_id', $currentUser->id)
                                     ->where('receiver_id', $landlord->id);
                           })
                           ->orWhere(function ($query) use ($currentUser, $landlord) {
                               $query->where('sender_id', $landlord->id)
                                     ->where('receiver_id', $currentUser->id);
                           })
                           ->with(['sender', 'receiver'])
                           ->orderBy('created_at', 'asc')
                           ->get();

        // Mark messages as read for the current user in this inquiry thread
        Message::where('house_id', $house->id)
              ->whereNull('agreement_id')
              ->where('receiver_id', $currentUser->id)
              ->whereNull('read_at')
              ->update(['read_at' => now()]);
        
        return view('messages.inquiry_thread', [
            'house' => $house,
            'messages' => $messages,
            'landlord' => $landlord,
            'currentUser' => $currentUser
        ]);
    }

    /**
     * Store a newly created inquiry message in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function storeInquiryMessage(Request $request, House $house)
    {
        $currentUser = Auth::user();
        $receiver = null;

        // Determine receiver: if current user is landlord, they are replying to an inquirer.
        // If current user is not landlord, they are sending to landlord.
        if ($currentUser->id === $house->landlord_id) {
            // This part is tricky without knowing who the landlord is replying to.
            // The form needs to submit the ID of the other participant in the thread.
            // For now, let's assume the request includes 'receiver_id' if landlord is replying.
            // This needs robust implementation in the view.
            // A simpler initial approach: landlord can only reply if there's an existing message from an inquirer.
            // For a new message from tenant to landlord, receiver is always landlord.
            $request->validate(['content' => 'required|string|max:5000']);
            if ($request->has('receiver_id_for_reply') && User::find($request->receiver_id_for_reply)) {
                 $receiver = User::find($request->receiver_id_for_reply);
            } else {
                // Attempt to find the last person who messaged the landlord about this house
                $lastInquirer = Message::where('house_id', $house->id)
                                    ->whereNull('agreement_id')
                                    ->where('receiver_id', $currentUser->id) // Messages sent to landlord
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                if ($lastInquirer) {
                    $receiver = $lastInquirer->sender;
                } else {
                     // This case should ideally not happen if UI guides landlord to reply to specific threads.
                    // Or, if this is the very first message from landlord (unlikely for inquiry flow)
                    abort(403, 'Cannot determine recipient for landlord reply.');
                }
            }
        } else {
            $receiver = $house->landlord;
        }

        Gate::authorize('send-inquiry-message', [$house, $receiver]);

        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $newMessage = Message::create([
            'house_id' => $house->id,
            'sender_id' => $currentUser->id,
            'receiver_id' => $receiver->id,
            'content' => $request->content,
            'agreement_id' => null,
        ]);

        // Notify the receiver
        if ($receiver) {
            $receiver->notify(new NewInquiryMessage($newMessage, $house));
        }

        return redirect()->route('houses.inquiry.show', $house)
                         ->with('success', 'Message sent successfully!');
    }
}

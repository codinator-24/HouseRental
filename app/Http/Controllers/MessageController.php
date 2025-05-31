<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate; // Import Gate

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
        $allAgreements = $agreementsAsTenant->merge($agreementsAsLandlord)->unique('id')->sortByDesc(function ($agreement) {
            return $agreement->messages->first()->created_at ?? $agreement->created_at; // Sort by latest message or agreement creation
        });
        
        return view('messages.overview', ['agreements' => $allAgreements]);
    }
}

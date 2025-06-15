<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Agreement;
use App\Models\House;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminCommunicationController extends Controller
{
    public function index(Request $request)
    {
        // Fetch Agreement-based threads
        $agreementThreads = Message::whereNotNull('agreement_id')
            ->with(['agreement.booking.house', 'agreement.tenant', 'agreement.landlord', 'sender', 'receiver'])
            ->select('agreement_id', DB::raw('MAX(created_at) as last_message_at'))
            ->groupBy('agreement_id')
            ->orderBy('last_message_at', 'desc')
            ->paginate(10, ['*'], 'agreement_page'); // Paginate agreement threads

        $agreementThreadDetails = [];
        foreach ($agreementThreads as $thread) {
            $firstMessage = Message::where('agreement_id', $thread->agreement_id)->with('agreement.booking.house', 'agreement.tenant', 'agreement.landlord', 'sender', 'receiver')->orderBy('created_at', 'asc')->first();
            if ($firstMessage && $firstMessage->agreement) {
                 $agreementThreadDetails[] = (object)[
                    'type' => 'Agreement',
                    'id' => $firstMessage->agreement_id,
                    'house_title' => $firstMessage->agreement->booking->house->title ?? 'N/A',
                    'participant1' => $firstMessage->agreement->tenant->user_name ?? 'N/A',
                    'participant2' => $firstMessage->agreement->landlord->user_name ?? 'N/A',
                    'last_message_at' => $thread->last_message_at,
                    'link' => route('admin.communications.agreement.show', $firstMessage->agreement_id),
                    'raw_agreement' => $firstMessage->agreement // for direct access if needed
                ];
            }
        }
        
        // Fetch Inquiry-based threads
        // For inquiries, a thread is defined by house_id and the pair of users (sender/receiver)
        // We use LEAST and GREATEST to make the user pair order-independent for grouping
        $inquiryThreadQuery = Message::whereNull('agreement_id')
            ->with(['house', 'sender', 'receiver'])
            ->select(
                'house_id',
                DB::raw('LEAST(sender_id, receiver_id) as user_a_id'),
                DB::raw('GREATEST(sender_id, receiver_id) as user_b_id'),
                DB::raw('MAX(created_at) as last_message_at')
            )
            ->groupBy('house_id', 'user_a_id', 'user_b_id')
            ->orderBy('last_message_at', 'desc');

        $inquiryThreads = $inquiryThreadQuery->paginate(10, ['*'], 'inquiry_page'); // Paginate inquiry threads

        $inquiryThreadDetails = [];
        foreach ($inquiryThreads as $thread) {
            $house = House::find($thread->house_id);
            $userA = User::find($thread->user_a_id);
            $userB = User::find($thread->user_b_id);

            if ($house && $userA && $userB) {
                $inquiryThreadDetails[] = (object)[
                    'type' => 'Inquiry',
                    'id' => $house->id . '-' . $userA->id . '-' . $userB->id, // Composite ID for uniqueness
                    'house_title' => $house->title,
                    'participant1' => $userA->user_name,
                    'participant2' => $userB->user_name,
                    'last_message_at' => $thread->last_message_at,
                    'link' => route('admin.communications.inquiry.show', ['house' => $house->id, 'userA' => $userA->id, 'userB' => $userB->id]),
                    'raw_house' => $house, // for direct access
                    'raw_userA' => $userA,
                    'raw_userB' => $userB
                ];
            }
        }

        // For simplicity in the view, we can pass them separately or attempt to merge and sort if needed.
        // Passing separately for now.
        return view('admin.communications.index', compact('agreementThreadDetails', 'inquiryThreadDetails', 'agreementThreads', 'inquiryThreads'));
    }

    public function showAgreementThread(Agreement $agreement)
    {
        $messages = Message::where('agreement_id', $agreement->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.communications.show_agreement_thread', compact('agreement', 'messages'));
    }

    public function showInquiryThread(House $house, User $userA, User $userB)
    {
        // Ensure userA and userB are the correct pair for the inquiry (one is landlord, one is inquirer)
        // This logic might need refinement based on how you want to handle direct access vs. links from overview
        $messages = Message::where('house_id', $house->id)
            ->whereNull('agreement_id')
            ->where(function ($query) use ($userA, $userB) {
                $query->where('sender_id', $userA->id)->where('receiver_id', $userB->id);
            })
            ->orWhere(function ($query) use ($userA, $userB) {
                $query->where('sender_id', $userB->id)->where('receiver_id', $userA->id);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('admin.communications.show_inquiry_thread', compact('house', 'userA', 'userB', 'messages'));
    }
}

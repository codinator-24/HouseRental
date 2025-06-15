<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class UnreadMessagesComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $unreadMessagesCountGlobal = 0;
        if (Auth::check()) {
            $unreadMessagesCountGlobal = Message::where('receiver_id', Auth::id())
                                          ->whereNull('read_at')
                                          ->count();
        }
        $view->with('unreadMessagesCountGlobal', $unreadMessagesCountGlobal);
    }
}

<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Messages;
use App\Models\User;
use App\Models\Notifications;
use App\Models\Alerts;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {

        $unreadPMs = Messages::where('recieverId', Auth::id())->where('read', false)->get();
        $alerts = Alerts::get();

        return view('layouts.app', compact('unreadPMs', 'alerts'));
    }
}

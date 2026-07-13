<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Models\Notifications;

class NotificationController extends Controller
{
    public static function send($to, $message, $icon = "user") {
        Notifications::create([
           'userId' => $to,
           'type' => 'message',
           'content' => $message, 
           'icon' => $icon
        ]);
    }

    public function clearAll() {
    $notifications = Notifications::where('userId', Auth::id())->get();

    foreach ($notifications as $notification) {
        $notification->delete();
    }

    return redirect()->back();
    }

    public function get() {
       $Notifications = Notifications::where('userId', Auth::id())->orderBy('created_at', 'desc')->get();
       
       return response()->json($Notifications); 
    }
}

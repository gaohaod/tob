<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Messages;
use App\Models\User;
use App\Models\Notifications;

use App\Http\Controllers\NotificationController;

class MessagingController extends Controller
{
    public function index() {
        $messages = Messages::where('archived', 0)
        ->where('moderated', 0)
        ->where('senderId', '!=', Auth::id())
        ->where('recieverId', Auth::id())
        ->orderBy('read', 'DESC')
        ->orderBy('created_at', 'DESC')
        ->with('sender')
        ->paginate(10);

        $sent = Messages::where('senderId', Auth::id())
        ->where('moderated', 0)
        ->where('archived', 0)
        ->orderBy('created_at', 'DESC')
        ->with('sender')
        ->paginate(10);

        $archive = Messages::where('archived', 1)
        ->where(function ($query) {
            $query->where('senderId', Auth::id())
              ->orWhere('recieverId', Auth::id());
        })
        ->with('sender')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        return view('messages.index', compact('messages', 'sent', 'archive'));
    }

    public function view($id) {
        $message = Messages::first()->where(function ($query) {
            $query->where('senderId', Auth::id())
              ->orWhere('recieverId', Auth::id());
        })
        ->with('sender')
        ->where('moderated', 0)
        ->where('id', $id)
        ->first();

        if (!$message) {
            return abort(404);
        }

        if (!$message->read && $message->senderId !== Auth::id()) {
            $message->read = true;
            $message->save();
        }

        return view('messages.view', compact('message'));
    }

        /*
            $table->id();
            $table->bigInteger('senderId');
            $table->bigInteger('recieverId');
            $table->text('content');
            $table->string('subject');
            $table->boolean('read');
            $table->boolean('moderated');
            $table->boolean('archived');
            $table->timestamps();
    */


    public function sendMessage(Request $request) {
        $rules = [
            'to' => 'required|string|max:20', //OnlyTwentyCharacters
            'subject' => 'required|string|max:150',
            'content' => 'required|string|max:1500',
        ];

        $validated = $request->validate($rules);

        if ($validated['to'] === Auth::user()->name) {
            return redirect()->back()->with('error', 'You can\'t message yourself!');
        }

        $user = User::where('name', $validated['to'])->where('banned', 0)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'That user doesn\'t exist.');
        }

        Messages::create([
            'senderId' => Auth::id(),
            'recieverId' => $user->id,
            'content' => $validated['content'],
            'subject' => $validated['subject'],
            'read' => false,
            'moderated' => false,
            'archived' => false,
        ]);

        //NotificationController::send($user->id, Auth::user()->name . ' has sent you a message.', 'comment-alt');

        return redirect()->back()->with('success', 'Sent.');
    }

    public function archiveMessage($message) {
        $msg = Messages::where('id', $message)
        ->where(function ($query) {
            $query->where('senderId', Auth::id())
              ->orWhere('recieverId', Auth::id());
        })
        ->where('moderated', 0)
        ->first();

        if (!$msg) {
            return abort(404);
        }

        $msg->archived = $msg->archived ? 0 : 1;
        $msg->save();

        return redirect()->back();
    }

    public function deleteMessage($message) {
        $msg = Messages::where('id', $message)
        ->where(function ($query) {
            $query->where('senderId', Auth::id())
              ->orWhere('recieverId', Auth::id());
        })
        ->where('moderated', 0)
        ->first();

        if (!$msg) {
            return abort(404);
        }

        $msg->delete();

        return redirect(route('app.messages'));
    }
}

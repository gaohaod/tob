<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Friends;

use App\Http\Controllers\NotificationController;

class FriendsController extends Controller
{
    public function getFriends($userId) {

        if (!$userId) {
            $userId = Auth::id();
        }

        $user = User::with(['acceptedFriendsTo', 'acceptedFriendsFrom'])->findOrFail($userId);
        $friends = $user->acceptedFriendsFrom->merge($user->acceptedFriendsTo);

        return view('view.friends', compact('user', 'friends'));
    }

    public function getPending() {
        $user = Auth::user()->load(['pendingFriendsTo', 'pendingFriendsFrom']);
        $friends = $user->pendingFriendsFrom->merge($user->pendingFriendsTo);

        return view('friends.pending', compact('user', 'friends'));
    }

    /*
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('friend_id')->constrained('users');
            $table->integer('accepted')->default(0);
            $table->timestamps();
    */

    public function add($id) {
        if ($id == Auth::id() || !$id) {
            return abort(404);
        }

        $user = User::where('id', $id)
            ->where('banned', 0)
            ->first();

        if (!$user) {
            return abort(404);
        }

        $friendshipExists = Friends::where(function ($query) use ($id) {
            $query->where('user_id', Auth::id())
                  ->where('friend_id', $id);
        })
        ->orWhere(function ($query) use ($id) {
            $query->where('user_id', $id)
                  ->where('friend_id', Auth::id());
        })
        ->exists();

        if ($friendshipExists) {
            return redirect()->back()->with('message', 'You are already friends or a request is pending.');
        }

        Friends::create([
            'user_id' => Auth::id(),
            'friend_id' => $id,
            'accepted' => 0,
        ]);

        NotificationController::send($user->id, 'You have a friend request from ' . Auth::user()->name, 'user-plus');

        return redirect()->back()->with('message', 'Friend request sent!');
    }

    public function accept($id) {
        if ($id == Auth::id() || !$id) {
            return abort(404);
        }

        $user = User::where('id', $id)
            ->where('banned', 0)
            ->first();

        if (!$user) {
            return abort(404);
        }

        $friendship = Friends::where(function ($query) use ($id) {
            $query->where('user_id', $id) 
                  ->where('friend_id', Auth::id())
                  ->where('accepted', 0);
        })->first();

        if (!$friendship) {
            return redirect()->back()->with('message', 'No pending friend request to accept.');
        }

        if ($friendship->user_id == Auth::id()) {
            return redirect()->back()->with('you cant accept a friend request u sent lol');
        }

        $friendship->accepted = 1;
        $friendship->save();

        return redirect()->back()->with('message', 'Friend request accepted!');
    }


    public function removeOrReject($id) {
        if ($id == Auth::id() || !$id) {
            return abort(404);
        }

        $user = User::where('id', $id)
            ->where('banned', 0)
            ->first();

        if (!$user) {
            return abort(404);
        }

        $friendshipExists = Friends::where(function ($query) use ($id) {
            $query->where('user_id', Auth::id())
                  ->where('friend_id', $id);
        })
        ->orWhere(function ($query) use ($id) {
            $query->where('user_id', $id)
                  ->where('friend_id', Auth::id());
        })
        ->first();

        if (!$friendshipExists) {
            return redirect()->back()->with('message', 'You aren\'t friends.');
        }

        $friendshipExists->delete();


        return redirect()->back();
    }

    public static function areFriends($userId, $playerId) {
        $friendship = Friends::where(function ($query) use ($userId, $playerId) {
            $query->where('user_id', $userId)
                  ->where('friend_id', $playerId)
                  ->where('accepted', 1);
        })
        ->orWhere(function ($query) use ($userId, $playerId) {
            $query->where('user_id', $playerId)
                  ->where('friend_id', $userId)
                  ->where('accepted', 1);
        })
        ->exists();

        return $friendship;
    }

    public static function getFriendshipStatus($userId) {
        if ($userId == Auth::id()) {
            return [
                'status' => -1,
                'senderId' => null,
                'receiverId' => null,
            ]; 
        }

        $pending = Friends::where(function ($query) use ($userId) {
                $query->where('user_id', Auth::id())
                      ->where('friend_id', $userId)
                      ->where('accepted', 0);
            })
            ->orWhere(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('friend_id', Auth::id())
                      ->where('accepted', 0);
            })
            ->first(); 

        if ($pending) {
            return [
                'status' => 1,
                'senderId' => $pending->user_id,
                'receiverId' => $pending->friend_id,
            ]; 
        }

        $friends = Friends::where(function ($query) use ($userId) {
                $query->where('user_id', Auth::id())
                      ->where('friend_id', $userId)
                      ->where('accepted', 1);
            })
            ->orWhere(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('friend_id', Auth::id())
                      ->where('accepted', 1);
            })
            ->first(); 

        if ($friends) {
            return [
                'status' => 2,
                'senderId' => $friends->user_id,
                'receiverId' => $friends->friend_id,
            ]; 
        }

        return [
            'status' => 0,
            'senderId' => null,
            'receiverId' => null,
        ]; 
    }
}

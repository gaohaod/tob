<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Asset;
use App\Models\UserBadges;
use App\Models\Owned;

use App\Http\Controllers\FriendsController;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function view($id) {
        $user = User::where('id', $id)
            ->with(['acceptedFriendsTo', 'acceptedFriendsFrom'])
            ->first();

        if (!$user) {
            return abort(404);
        }

        $assets = Asset::where([
                ['creator_id', '=', $user->id],
                ['banned', '=', 0],
                ['under_review', '=', 0],
                ['type', '=', 'place'],
            ])
            ->limit(5)
            ->get();

        $status = FriendsController::getFriendshipStatus($id);

        $owned = Owned::where('userId', $user->id)
            ->where('wearing', 1)
            ->with('asset')
            ->whereHas('asset', function ($query) {
                    $query->whereNotIn('type', ['clothing']);
            })
            ->get();

        $types = ['hat', 'shirt', 'pant', 'tshirt', 'head', 'gear', 'face'];
        $item = [];
        foreach ($types as $type) {
            $item[$type . 's'] = Owned::where('userId', $user->id)
                ->whereHas('asset', function ($query) use ($type) {
                    $query->where('type', $type);
                    $query->whereNotIn('type', ['clothing']);
                })
                ->with('asset')
                ->orderBy('created_at', 'DESC')
                ->paginate(18, ['*'], $type . 's');

            /*if (!$item[$type . 's']) {
                $item[$type . 's'] = collect();
            }*/
        }

        $friends = $user->acceptedFriendsFrom->merge($user->acceptedFriendsTo)->take(3);

        return view('view.user', compact('user', 'assets', 'owned', 'status', 'friends', 'item'));
    }

    public function changePeepType(Request $request) {
        $rules = [
            'peep' => 'required|string|in:alt,def',
        ];

        $validated = $request->validate($rules);
        // thx copilot

        $currentPeeps = Auth::user()->using_alternative_peeps;

        if ($currentPeeps) {
            Auth::user()->using_alternative_peeps = false;
            Auth::user()->save(); 

            return redirect()->back()->with('success2', 'You are now using the default Peep style.');
        } else {
            Auth::user()->using_alternative_peeps = true;
            Auth::user()->save(); 

            return redirect()->back()->with('success2', 'You are now using the alternative Peep style.');
        }
    }


    public function index() {
        $users = User::where('banned', 0)
            ->orderByRaw('
                CASE 
                    WHEN last_seen > ? THEN 1
                    ELSE 0 
                END DESC', [Carbon::now()->subMinutes(10)])
            ->orderBy('last_seen', 'desc') 
            ->orderBy('created_at', 'desc')
            ->paginate(18);
        return view('users', compact('users'));
    }

    public function search(Request $request) {
        $keyword = $request->query('keyword');
        $users = User::where('banned', 0)
            ->where('name', 'like', "%$keyword%")
            ->orderByRaw('
                CASE 
                    WHEN last_seen > ? THEN 1
                    ELSE 0 
                END DESC', [Carbon::now()->subMinutes(10)])
            ->orderBy('last_seen', 'desc') 
            ->orderBy('created_at', 'desc')
            ->paginate(18);
        return view('users', compact('users', 'keyword'));
    }

    public function registerPresence() {
        if (Auth::check()) {
            Auth::user()->update([
                'last_seen' => Carbon::now(),
            ]);

            Auth::user()->save();
        }

        $data = [
            'success' => true,
            'message' => 'OK',
        ];

        return response()->json($data);
    }

    public function changeMship(Request $req) {
        $mship = $req->mship;

        if ($mship) {
            return abort('no');
        }

        if ($mship < 0 || $mship > 3) {
            return die('no');
        }

        Auth::user()->mship = $mship;   
        Auth::user()->save();

        return redirect()->back();
    }

    public function home() {
        $popular = Asset::where('banned', 0)
        ->orderBy('playing', 'DESC')
        ->orderBy('visits', 'DESC')
        ->limit(4)->where('type', 'place')
        ->with('user')
        ->get();

        return view('dashboard', compact('popular'));
    }

    public function change_theme(Request $request) {
        $type = (INT) $request->type;
        $allowed = [
            0,
            1,
            2,
            3,
        ];

        if (!isset($type) || !in_array($type, $allowed)) {
            return abort(403);
        }

        Auth::user()->theme = $type;
        Auth::user()->save();

        return redirect()->back();
    }

public function changeGender(Request $request) {
    $gender = $request->input('gender');

    $validGenders = ['m', 'f'];

    if (!$gender || !in_array($gender, $validGenders)) {
        return back()->withErrors(['gender' => 'There are 2 fucking genders. Choose One.']);
    }

    Auth::user()->gender = $gender;
    Auth::user()->save();

    return back()->with('success', 'Gender updated.');
}

}

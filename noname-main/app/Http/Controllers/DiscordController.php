<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\User;

class DiscordController extends Controller
{
    public function generateToken() {
        if (Auth::check()) {

            if (!Auth::user()->verified_via_discord) {

                $generatedToken = Str::uuid();
                Auth::user()->discord_token = $generatedToken;
                Auth::user()->save();

                return redirect()->back()->with('message', 'Ticket generated! Send this to staff privately. Ticket: <kbd>' . $generatedToken . '</kbd>');

            } else {
                return redirect()->back()->with('message', 'You already have a ticket!');
            }
        } else {
            return abort(401);
        }
    }

    public function verifyToken(Request $req) {

        $token = $req->token;

        if (Auth::check() && Auth::user()->admin) {
            $find = User::where('discord_token', $token)->first();

            if (!$find || $find->verified_via_discord) {
                return redirect()->back()->with('message', 'Token is invalid or has already been used to verify someone.');
            }

            $find->verified_via_discord = true;
            $find->save();

            return redirect()->back()->with('message', 'User verified.');

        } else {
            return abort(401);
        }
    }
}

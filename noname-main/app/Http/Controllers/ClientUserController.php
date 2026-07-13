<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

use App\Models\User;

class ClientUserController extends Controller
{
    public static function get() {
        $authenticationTicket = Cookie::get('ROBLOSECURITY');

        if (empty($authenticationTicket)) {
            return 12;
        }

        $user = User::where('authentication_ticket', $authenticationTicket)->first();

        return $user ?? 11;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\GameserverController;

use App\Models\User;
use App\Models\Asset;
use App\Models\GameTickets;
use App\Models\ServerJobs;

class GameTicketController extends Controller
{
public function requestTicket($placeId) {

    if (Auth::user()->verified_via_discord) {

        $gameJoinDisabled = false;

        $data = [];
        if ($gameJoinDisabled) {
            $data = [
                'success' => false,
                'message' => 'Gameservers are temporarily not available, try again later.',
                'token' => null,
            ];

            return response()->json($data);
        } else {
            if (!Auth::check()) {
                $data = [
                    'success' => false,
                    'message' => 'You need to be logged in to use this API',
                    'token' => null,
                ];

                return response()->json($data);
            }

            $existingTicket = GameTickets::where('userId', Auth::id())->first();

            if ($existingTicket) {
                $data = [
                    'success' => false,
                    'message' => 'Please clear all your tickets and try again',
                    'token' => null,
                ];

                return response()->json($data);
            }

            // no tickets and everything okay create one
            $place = Asset::where('id', $placeId)->where('banned', 0)->where('type', 'place')->first();

            if (!$place) {
                $data = [
                    'success' => false,
                    'message' => 'This place doesn\'t exist or is moderated',
                    'token' => null,
                ];

                return response()->json($data);
            }

            $gameTicket = Str::uuid(); 
            $serverJob = ServerJobs::where('placeId', $placeId);

            /*
            'id',
            'token',
            'placeId',
            'userId',
            'year',
            'port',
            'created_at',
            'updated_at',
            */

            if ($place->year == 2012) {

                $server = GameserverController::startServer($place->id, "HHi21dq6Ba0dscUOlEZeu97OEz1BzH");
                
                $newticket = GameTickets::create([
                    'token' => Str::uuid(),
                    'placeId' =>  $placeId,
                    'userId' => Auth::user()->id,
                    'year' => $place->year,
                    'port' => $server->port,
                ]);
            } else {
                $newticket = GameTickets::create([
                    'token' => Str::uuid(),
                    'placeId' =>  $placeId,
                    'userId' => Auth::user()->id,
                    'year' => $place->year,
                    'port' => 0,
                ]);
            }


            $data = [
                'success' => true,
                'message' => 'Server found, Starting client',
                'token' => $newticket->token,
            ];

            return response()->json($data);
        }
    } else {

        $data = [
            'success' => false,
            'message' => 'verify your account to play games',
            'token' => null,
        ];

        return response()->json($data);
    }

}

    
    public function DeleteAllTickets() {
        if (!Auth::check()) {
            return abort(401);
        }

        GameTickets::where('userId', Auth::user()->id)->delete();

        return redirect()->back();
    }
}

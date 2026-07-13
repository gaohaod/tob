<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

use App\Http\Controllers\SignatureController;
use App\Http\Controllers\ClientUserController;
use App\Http\Controllers\GameserverController;
use App\Http\Controllers\FriendsController;

use App\Models\Asset;
use App\Models\GameTickets;
use App\Models\User;
use App\Models\ServerJobs;

class ClientController extends Controller
{

    function flags2015(Request $req) {
            $apiKey = $req->apiKey;
            if ($apiKey == "D6925E56-BFB9-4908-AAA2-A5B1EC4B2D79 ") {
                            $content = file_get_contents(resource_path('/roblox/2012.json'));

            return response($content)
                ->header('Content-Type', 'application/json')
                ->header('Cache-Control', 'no-cache')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '-1')
                ->header('Last-Modified', gmdate('D, d M Y H:i:s T') . ' GMT');
            }
            $content = file_get_contents(resource_path('/roblox/2015.json'));

            return response($content)
                ->header('Content-Type', 'application/json')
                ->header('Cache-Control', 'no-cache')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '-1')
                ->header('Last-Modified', gmdate('D, d M Y H:i:s T') . ' GMT');
    }

    function rcc2015() {
            $content = file_get_contents(resource_path('/roblox/rcc2015.json'));

            return response($content)
                ->header('Content-Type', 'application/json')
                ->header('Cache-Control', 'no-cache')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '-1')
                ->header('Last-Modified', gmdate('D, d M Y H:i:s T') . ' GMT');
    }

function asset(Request $request) {
    ob_start();

    $id = $request->query('id');
    $version = $request->query('version');
    $rcc = true;

    if (!$id || !is_numeric($id)) {
        abort(404, 'Invalid asset ID');
    }

    $sanitizedId = basename($id);

    $asset = Asset::find($sanitizedId);
    $file = storage_path("/app/public/asset/" . $sanitizedId);

    if (!$asset || $asset->banned) {
        if (!file_exists($file)) {
            $url = "https://assetdelivery.roblox.com/v1/asset/?id=" . $sanitizedId;
            $url .= isset($version) ? "&version=" . $version : '';
            return redirect($url);
        }
        $content = file_get_contents($file);
        $finalContent = ($rcc === "true") ? str_replace('Hat', 'Accessory', $content) : $content;

        $corescriptAssetIds = [
            140,
            141,
            142,
            143,
            144,
            145,
            146,
            147,
            148,
            149,
            150,
            151,
            152,
            153,
            154,
            155,
            156,
            274,
            275,
            276,
            277,
            278,
            279,
            280,
            281,
            282,
            283,
            284,
            285,
            286,
            287,
            288,
            289,
            290,
            291,
            292,
            293,
            294,
            295,
            296,
            297,
            298,
        ];

        if (in_array($sanitizedId, $corescriptAssetIds)) {
            $test = "%$sanitizedId%\r\n" . $finalContent;
            $sig = SignatureController::sign("\r\n" . $finalContent);
            $final = "--rbxsig%" . $sig . "%\n" . $test;
            return response($final)
                ->header('Content-Type', 'text/plain')
                ->header('Cache-Control', 'no-cache')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '-1')
                ->header('Last-Modified', gmdate('D, d M Y H:i:s T') . ' GMT');
        } else {
            $p = "not signed \r\n" . $finalContent;
            return response($p)
                ->header('Content-Type', 'text/plain')
                ->header('Cache-Control', 'no-cache')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '-1')
                ->header('Last-Modified', gmdate('D, d M Y H:i:s T') . ' GMT')
                ->header('Content-Type', 'text/plain');
        }
    }

    if ($asset->type === "place") {
        $accessKey = $request->AccessKey;

        if ($accessKey !== "u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn") {
            abort(403, "Access key is required to access place assets.");
        }

        $placePath = storage_path("/app/public/places/" . $sanitizedId);
        if (file_exists($placePath)) {
            return response(file_get_contents($placePath))
                ->header('Content-Type', 'text/plain')
                ->header('Cache-Control', 'no-cache')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '-1')
                ->header('Last-Modified', gmdate('D, d M Y H:i:s T') . ' GMT');
        }

        return response('Place does not exist', 404)
            ->header('Content-Type', 'text/plain');
    }

    if (file_exists($file)) {
        $content = file_get_contents($file);
        $finalContent = ($rcc === "true") ? str_replace('Hat', 'Accessory', $content) : $content;
        $finalFinalcontent = str_replace('roblox.com/Asset/?id=', 'noname.xyz/asset/?id=', $finalContent);
        $finalFinalFinalcontent = str_replace('roblox.com/asset/?id=', 'noname.xyz/asset/?id=', $finalFinalcontent);

        $corescriptAssetIds = [
            140,
            141,
            142,
            143,
            144,
            145,
            146,
            147,
            148,
            149,
            150,
            151,
            152,
            153,
            154,
            155,
            156,
            274,
            275,
            276,
            277,
            278,
            279,
            280,
            281,
            282,
            283,
            284,
            285,
            286,
            287,
            288,
            289,
            290,
            291,
            292,
            293,
            294,
            295,
            296,
            297,
            298,
        ];

        if (in_array($sanitizedId, $corescriptAssetIds)) {
            $data = "%" . $sanitizedId . "%\n" . File::get($file);
            $key = file_get_contents(resource_path('roblox/key.pem'));
            openssl_sign($data, $signature, $key, OPENSSL_ALGO_SHA1);
            $sig = base64_encode($signature);
            $final = "" . sprintf("%%%s%%%s", $sig, $data);
            return response($final)
                ->header('Content-Type', 'text/plain')
                ->header('Cache-Control', 'no-cache')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '-1')
                ->header('Last-Modified', gmdate('D, d M Y H:i:s T') . ' GMT');
        } else {
            return response($finalFinalFinalcontent)
                ->header('Content-Type', 'text/plain')
                ->header('Cache-Control', 'no-cache')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '-1')
                ->header('Last-Modified', gmdate('D, d M Y H:i:s T') . ' GMT')
                ->header('Content-Type', 'text/plain');
        }
    }

    $url = "https://assetdelivery.roblox.com/v1/asset/?id=" . $sanitizedId;
    $url .= isset($version) ? "&version=" . $version : '';
    return redirect($url);

    ob_end_flush();
}


    function visit_2016() {

        $VisitScript = file_get_contents(resource_path('/roblox/visit.lua'));

        if (Auth::check()) {
            $uid = Auth::user()->id;
        } else {
            $uid = "-1";
        }

        $NewVisit = str_replace('{{time}}', time(), $VisitScript);
        $NewVisit2 = str_replace('{{id}}', $uid, $VisitScript);

        $sig = SignatureController::sign("\r\n" . $NewVisit2);
        $final = "--rbxsig%" . $sig . "%\r\n" . $NewVisit2;
        return response($final)->header('Content-Type', 'text/plain');        
    }

    function getCurrentUser(Request $request) {
        $cookie = $request->cookie('ROBLOSECURITY');

        if (!$cookie) {
            return '-1';
        }

        $usr = ClientUserController::getUserFromSecurity($cookie);

        return $usr->id;

    }

    function LuaWebServiceHandleSocial(Request $request) {

        $Method = $request->query('method');
        $PlayerId = $request->query('playerid');
        $UserId = $request->query('userid');
        $GroupId = $request->query('groupid');

        $adminUserIds = User::where('admin', true)->pluck('id')->toArray();

        if (isset($Method) and isset($PlayerId)) {

            if ($Method == "GetGroupRank") {
                if (in_array($PlayerId, $adminUserIds) and $GroupId == 2 or $GroupId == 1200769) {
                    $response = '<Value Type="integer">255</Value>';
                } else {
                    $response =  '<Value Type="integer">0</Value>';
                }
            }

            if ($Method == "IsInGroup") {
                if (in_array($PlayerId, $adminUserIds) and $GroupId == 1200769) {
                    $response = '<Value Type="boolean">true</Value>';
                } else {
                    $response = '<Value Type="boolean">false</Value>';
                }
            }

            if ($Method == "IsFriendsWith") {

                $areFriends = FriendsController::areFriends($UserId, $PlayerId);

                if ($areFriends) {
                    $response = '<Value Type="boolean">true</Value>';
                } else {
                    $response = '<Value Type="boolean">false</Value>';
                }
            }

            if ($Method == "IsBestFriendsWith") {

                $areFriends = FriendsController::areFriends($UserId, $PlayerId);

                if ($areFriends) {
                    $response = '<Value Type="boolean">true</Value>';
                } else {
                    $response = '<Value Type="boolean">false</Value>';
                }
            }

        } else {
            $response = 'Invalid request.';
        }

        return response($response)->header('Content-Type', 'application/xml'); //

    }

    function join_16(Request $request) {
        $token = $request->token;

        $ticket = GameTickets::where('token', $token)->first();

        if (!$ticket) {
            dd($ticket);
        }

        $user = User::where('id', $ticket->userId)->where('banned', 0)->first();

        if (!$user) {
            return abort(404);
        }

        $game = Asset::where('id', $ticket->placeId)->first();
        $asset = Asset::where('id', $ticket->placeId)->with('user')->first();

        if (!$game || $asset) {
            
        }

        if ($request->twelve == 'false') {



        // start serverrrr
        $server = Gameservercontroller::startGameserver($ticket->placeId); // hope to God this starts in time

        //$server = ServerJobs::where('placeId', $ticket->placeId)->first();

        $ticket->port = $server['port'];
        $ticket->save();

        $CharacterAppearance = "";

                
        if ($user->verified_via_discord) {
            $mship = "BuildersClub";
        } else {
            $mship = "None";
        }

 
        $jobidFUCKTHIS = $request->query("GodHelpMe");
/*
 "MachineAddress" => "26.84.148.206",
        "ServerPort" => $server['port'],
*/
        /*$joinscript = [
        "ClientPort" => 0,
        "MachineAddress" => "26.88.250.242",
        "ServerPort" => $server['port'],
        "PingUrl" => "",
        "PingInterval" => 20,
        "UserName" => $user->name,
        "SeleniumTestMode" => false,
        "UserId" => $user->id,
        "SuperSafeChat" => false,
        "CharacterAppearance" => "http://noname.xyz/char/$user->id",
        "ClientTicket" => SignatureController::ClientTicket($user->id, $jobidFUCKTHIS, $user->name, "http://noname.xyz/char/$user->id"), //($uid, $jid, $usr, $charap) please man just work
        "GameId" => $ticket->placeId,
        "PlaceId" => $ticket->placeId,
        "MeasurementUrl" => "",
        "WaitingForCharacterGuid" => "26eb3e21-aa80-475b-a777-b43c3ea5f7d2",
        "BaseUrl" => "http://www.noname.xyz",
        "ChatStyle" => "ClassicAndBubble",
        "VendorId" => "0",
        "ScreenShotInfo" => "",
        "VideoInfo" => "",
        "CreatorId" => $asset->creator_id,
        "CreatorTypeEnum" => "User",
        "MembershipType" => $mship,
        "AccountAge" => "100",
        "JobId" => $server['jobid'],
        "CookieStoreFirstTimePlayKey" => "rbx_evt_ftp",
        "CookieStoreFiveMinutePlayKey" => "rbx_evt_fmp",
        "CookieStoreEnabled" => true,
        "IsRobloxPlace" => true,
        "GenerateTeleportJoin" => false,
        "IsUnknownOrUnder13" => false,
        "SessionId" => "39412c34-2f9b-436f-b19d-b8db90c2e186|00000000-0000-0000-0000-000000000000|0|" . request()->ip() . "|8|" . Carbon::now('Europe/Belgrade')->toIso8601String() . "|0|null|null",
        "DataCenterId" => 0,
        "UniverseId" => 3,
        "BrowserTrackerId" => 0,
        "UsePortraitMode" => false,
        "FollowUserId" => 0,
        "characterAppearanceId" => $user->id
    ];*/

        $joinscript = [
            "ClientPort" => 0,
            "MachineAddress" => "26.88.250.242",
            "ServerPort" => $server['port'],
            "PingUrl" => "",
            "PingInterval" => 20,
            "UserName" => "ihatethis",
            "SeleniumTestMode" => false,
            "UserId" => 69,
            "SuperSafeChat" => false,
            "CharacterAppearance" => "http://noname.xyz/char/3",
            "ClientTicket" => SignatureController::ClientTicket(69, $jobidFUCKTHIS, "ihatethis", "http://noname.xyz/char/3"), //($uid, $jid, $usr, $charap) please man just work
            "GameId" => $ticket->placeId,
            "PlaceId" => $ticket->placeId,
            "MeasurementUrl" => "",
            "WaitingForCharacterGuid" => "26eb3e21-aa80-475b-a777-b43c3ea5f7d2",
            "BaseUrl" => "http://www.noname.xyz",
            "ChatStyle" => "ClassicAndBubble",
            "VendorId" => "0",
            "ScreenShotInfo" => "",
            "VideoInfo" => "",
            "CreatorId" => $asset->creator_id,
            "CreatorTypeEnum" => "User",
            "MembershipType" => $mship,
            "AccountAge" => "100",
            "JobId" => $server['jobid'],
            "CookieStoreFirstTimePlayKey" => "rbx_evt_ftp",
            "CookieStoreFiveMinutePlayKey" => "rbx_evt_fmp",
            "CookieStoreEnabled" => true,
            "IsRobloxPlace" => true,
            "GenerateTeleportJoin" => false,
            "IsUnknownOrUnder13" => false,
            "SessionId" => "39412c34-2f9b-436f-b19d-b8db90c2e186|00000000-0000-0000-0000-000000000000|0|" . request()->ip() . "|8|" . Carbon::now('Europe/Belgrade')->toIso8601String() . "|0|null|null",
            "DataCenterId" => 0,
            "UniverseId" => 3,
            "BrowserTrackerId" => 0,
            "UsePortraitMode" => false,
            "FollowUserId" => 0,
            "characterAppearanceId" => 3
        ];

        $data = json_encode($joinscript, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        $signature = SignatureController::Sign("\r\n" . $data);
        $final = "--rbxsig%". $signature . "%\r\n" . $data;

        return response($final)->header('Content-Type', 'text/plain');
    } else {
        $jobby = Gameservercontroller::startServer($asset->id, "HHi21dq6Ba0dscUOlEZeu97OEz1BzH");

        if (!$jobby || $jobby == null) {
            $response = 'Game:SetMessage("Couldn\'t start game, we\'re really sorry! Technical details: job is null")';

            return response($response)->header('Content-Type', 'text/plain'); 
        }

        $VisitScript = file_get_contents(resource_path('/roblox/join.lua'));

        /*
{playerName}
{membership}
{gamePort}
{playerId}
{placeId}
{creatorId}
        */
        
        if ($user->verified_via_discord) {
            $mship = "BuildersClub";
        } else {
            $mship = "None";
        }

        $VS2 = preg_replace('{playerName}', $user->name, $VisitScript);
        $VS3 = preg_replace('{membership}', $mship, $VS2);
        $VS4 = preg_replace('{gamePort}', $jobby->port, $VS3); //$jobby->port
        $VS5 = preg_replace('{playerId}', $user->id, $VS4);
        $VS6 = preg_replace('{placeId}', $asset->id, $VS5);
        $VS7 = preg_replace('{creatorId}', $asset->user->id, $VS6);
        $signature = SignatureController::Sign("\r\n" . $VS7);
        $final = "%". $signature . "%\r\n" . $VS7;

        /*$sig = SignatureController::sign("\r\n" . $VS7);
        $final = "%" . $sig . "%\r\n" . $VS7;*/
        return response($final)->header('Content-Type', 'text/plain');  
        
    }



    }

    function placelauncher(Request $request) {
        $token = $request->token;

        $ticket = GameTickets::where('token', $token)->first();

        if (!$ticket) {
            return abort(403);
        }

        $user = User::where('id', $ticket->userId)->where('banned', 0)->first();

        if (!$user) {
            //return abort(404);
        }

        $game = Asset::where('id', $ticket->placeId)->first();
        $asset = Asset::where('id', $ticket->placeId)->with('user')->first();

        if (!$game || $asset) {
            
        }

        if ($asset->playing >= $asset->max_players) {
            $status = 6;
        } else {

        }
        // start serverrrr
        $server = Gameservercontroller::startGameserver($ticket->placeId);

        $status = $server["status"];

        $jobid = $server["jobid"];

        $args = "&jobid=" . $jobid;
        
        $placelauncherRaw = [
            "jobId"=> $jobid,
            "status"=> $status,
            "joinScriptUrl"=> "http://noname.xyz/game/join.ashx?twelve=false&GodHelpMe=$jobid&token=$token",
            "authenticationUrl"=> "http://noname.xyz/Login/Negotiate.ashx",
            'authenticationTicket' => $token,
            'message' => null,
        ];
        
        $data = json_encode($placelauncherRaw, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

        //echo($data);
        return response($data)->header('Content-Type', 'text/plain');
    }

    function placeinfo(Request $request) {
        $assetId = (INT)$request->id;

        $asset = Asset::where('id', $assetId)->with('user')->first();

        if (!$asset) {
            return abort(404);
        }

        $created_at = $asset->created_at->format('Y-m-d\TH:i:s.v\Z');
        $updated_at = $asset->updated_at->format('Y-m-d\TH:i:s.v\Z');

        $data = '
        {
    "TargetId": ' . $assetId . ',
    "ProductType": "User Product",
    "AssetId": ' . $assetId . ',
    "ProductId": ' . $assetId . ',
    "Name": "' . $asset->name . '",
    "Description": "' . $asset->description . '",
    "AssetTypeId": 8,
    "Creator": {
        "Id": ' . $asset->user->id . ',
        "Name": "' . $asset->user->name . '"
    },
    "IconImageAssetId": 0,
    "Created": "' . $created_at . '",
    "Updated": "' . $updated_at . '",
    "PriceInRobux": ' . $asset->peeps . ',
    "PriceInTickets": ' . $asset->peeps . ',
    "Sales": 0,
    "IsNew": true,
    "IsForSale": true,
    "IsPublicDomain": false,
    "IsLimited": false,
    "IsLimitedUnique": false,
    "Remaining": null,
    "MinimumMembershipLevel": 0,
    "ContentRatingTypeId": 0
}
        ';


        return response($data)->header('Content-Type', 'text/plain');
    }

    function loadPlaceInfo(Request $request) {

        $placeId = $request->PlaceId;

        if (!$placeId) {
            return abort(404);
        }

        $asset = Asset::where('id', $placeId)->first();

        if (!$asset) {
            return abort(404);
        }

        $Script = file_get_contents(resource_path('/roblox/LoadPlaceInfo.lua'));

        $Script2 = str_replace('{{ gameCreatorId }}', $asset->creator_id, $Script);

        $sig = SignatureController::sign("\r\n" . $Script2);
        $final = "--rbxsig%" . $sig . "%\r\n" . $Script2;
        return response($final)->header('Content-Type', 'text/plain');        
    }

    function getAuth(Request $request) {
        $suggest = $request->suggest;
        $check = $request->cookie('ROBLOSECURITY');
    
        $cookieName = "ROBLOSECURITY";
        $cookieValue = (string) Str::uuid(); 
        $minutes = 460800 * 30 / 60; 
    
        $user = User::where('authentication_ticket', $suggest)->first();
    
        if (!$user) {
            return abort(401); 
        }
    
        if ($check) {
    
            Cookie::queue(Cookie::forget($cookieName, '/', '.noname.xyz'));
            Cookie::queue(Cookie::make(
                'ROBLOSECURITY',
                $user->authentication_ticket,
                $minutes,
                '/',
                '.noname.xyz',
                false,
                false,
                false,
                'None'
            ));
    
            /*$user->authentication_ticket = $cookieValue;
            $user->save();*/
    
            Auth::login($user);
    
            return response($cookieValue)->header('Content-Type', 'text/plain');
        } else {
            Cookie::queue(Cookie::make(
                'ROBLOSECURITY',
                $user->authentication_ticket,
                $minutes,
                '/',
                '.noname.xyz',
                false,
                false,
                false,
                'None'
            ));
            /*$user->authentication_ticket = $cookieValue;
            $user->save();*/
    
            Auth::login($user);
    
            return response($cookieValue)->header('Content-Type', 'text/plain');
        }
    }

    public function Toolbox(Request $request) {
        $type = $request->type;
        if (!$type) {
            $items = Asset::where('banned', 0)
            ->where('under_review', 0)
            ->whereNotIn('type', ['clothing', 'shirt', 'pants', 'hat', 'face', 'place', 'head', 'tshirt', 'gear'])
            ->get();
            return view('studio.toolbox', compact('items'));
        } else {
            $allowed = ['audio', 'decal', 'model'];
            if (!in_array($type, $allowed)) {
                return abort(404);
            }
            $items = Asset::where('banned', 0)
            ->where('type', $type)
            ->where('under_review', 0)
            ->whereNotIn('type', ['clothing', 'shirt', 'pants', 'hat', 'face', 'place', 'head', 'tshirt', 'gear'])
            ->get();
    
            return view('studio.toolbox-results', compact('items', 'type'));
        }
    }


    public function studioAshx() {
        $VisitScript = file_get_contents(resource_path('/roblox/studio.lua'));

        $sig = SignatureController::sign("\r\n" . $VisitScript);
        $final = "%" . $sig . "%\r\n" . $VisitScript;
        return response($final)->header('Content-Type', 'text/plain');  
    }   

    public function fflags2012() {
        $VisitScript = file_get_contents(resource_path('/roblox/2012fflags_2022_02'));

        return response($VisitScript)->header('Content-Type', 'text/plain');  
    }

    public function join12($token) {

    }   

}

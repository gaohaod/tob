<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Roblox\Grid\Rcc\RCCServiceSoap;
use App\Roblox\Grid\Rcc\Job;
use App\Roblox\Grid\Rcc\ScriptExecution;
use App\Roblox\Grid\Rcc\Status;
use App\Roblox\Grid\Rcc\LuaType;
use App\Roblox\Grid\Rcc\LuaValue;

use App\Models\ServerJobs;
use App\Models\GameTickets;
use App\Models\RccInstances;
use App\Models\ServerPlayers;
use App\Models\User;
use App\Models\Asset;

class GameserverController extends Controller
{
    public static function startGameserver($placeId) {
        $generatedPort = rand(53640, 6968);
        $genport = mt_rand(1,65535);
        $jobId = "NONAME-Gameserver-" . Str::uuid();
        $existingJob = ServerJobs::where('placeId', $placeId)->first();

        $assetTiedtojob = Asset::where('id', $placeId)->first();

        // for rcc
        $gameserverLua = file_get_contents(resource_path('/roblox/gameserver.lua'));
        $startscript = 'start(' . $placeId . ', ' . $genport . ', "http://www.noname.xyz")';

        $newLua = str_replace('{{startFunc}}', "start(" . $placeId . ", 'http://www.noname.xyz', " . $generatedPort . ", " . $placeId . ")", $gameserverLua);
        //$newLua = $gameserverLua . $startscript;
        if ($existingJob) {
            return ['status' => $existingJob->status, 'jobid' => $existingJob->jobId, 'port' => $existingJob->port];
        } else {
            $job = [
                'jobId' => $jobId,
                'status' => '1',
                'placeId' => $placeId,
                'port' => $generatedPort,
            ];

            $createJob = ServerJobs::create($job);

            $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);
            
            $gameJob = new Job($jobId,70);

            $script = new ScriptExecution($jobId ."-Script", $newLua);
            $jobload = $RCCServiceSoap->OpenJobEx($gameJob, $script);

            return ['status' => '1', 'jobid' => $jobId, 'port' => $generatedPort];
        }
    }


    public function renewGameserver($jobId) {
        $RCCServiceSoap = new RCCServiceSoap("26.88.250.242", 6969);
        $RCCServiceSoap->RenewLease($jobId, 120);
    }

    public function completeGameserver($jobId) {
        ServerJobs::where('jobId', $jobId)->update(['status' => 2]);
    }

    public function deleteJobGameserver($jobId) {
        $RCCServiceSoap = new RCCServiceSoap("26.88.250.242", 6969);
        $RCCServiceSoap->RenewLease($jobId, 1);
        ServerJobs::where('jobId', $jobId)->delete();
    }

    // Below are user-agent protected endpoints.

    public function registerRcc(Request $request) {
        
        if ($request->header('User-Agent') !== 'Roblox/WinInet') {
        RccInstances::create([
            'id' => Str::uuid(),
        ]);

        $data = [
            'success' => true,
            'message' => 'RCC registered successfully',
        ];
        return response()->json($data);
        } else {
            $data = [
                'success' => false,
                'message' => 'No access for you :)',
            ];
            return response()->json($data);
        }
    }

    public function removeRcc($uuid, Request $request) {
        
        if ($request->header('User-Agent') !== 'Roblox/WinInet') {

            if (!isset($uuid)) {
                $data = [
                    'success' => false,
                    'message' => 'Nope.',
                ];
                return response()->json($data);
            }

            $instance = RccInstances::where('id', $uuid)->first();

            if (!$instance) {
                $data = [
                    'success' => false,
                    'message' => 'Instance doesn\'t exist',
                ];
    
                return response()->json($data);
            }

            $instance->delete();

            $data = [
                'success' => true,
                'message' => 'RCC unregistered successfully',
            ];

            return response()->json($data);
        } else {
            $data = [
                'success' => false,
                'message' => 'No access for you :)',
            ];

            return response()->json($data);
        }
    }


    public function AddToServer(Request $request) {

        $placeId = (INT)$request->placeId;
        $accessKey = $request->accessKey;
        $userId = (INT)$request->userId;

        if (!isset($placeId) || !isset($accessKey) || !isset($userId)) {
            return abort(404);
        }

        $ak = "u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn";
    
        if (!$accessKey == $ak) {
            return abort(401);
        }

        \Log::info('Place ID: ' . $placeId);
        \Log::info('Access Key: ' . $accessKey);
        \Log::info('User ID: ' . $userId);
    
        $game = ServerJobs::where('placeId', $placeId)->first();
        $asset = Asset::where('id', $placeId)->first();
        
        $user = User::where('id', $userId)->first(); 

        $user->in_game = true;
        $user->save(); 
    
        $data = [
            'userId' => $user->id,  
            'placeId' => $placeId,
            'jobId' => $game->jobId,
        ];
    
        ServerPlayers::create($data);

        $asset->playing++;
        $asset->save();
    
        return true; 
    }

    public function RemoveFromServer(Request $request) {

        $placeId = (INT)$request->placeId;
        $accessKey = $request->accessKey;
        $userId = (INT)$request->userId;

        if (!isset($placeId) || !isset($accessKey) || !isset($userId)) {
            return abort(404);
        }

        $ak = "u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn";
    
        if (!$accessKey == $ak) {
            return abort(401);
        }

        \Log::info('Place ID: ' . $placeId);
        \Log::info('Access Key: ' . $accessKey);
        \Log::info('User ID: ' . $userId);
    
        $game = ServerJobs::where('placeId', $placeId)->first();
        $asset = Asset::where('id', $placeId)->first();

        $user = User::where('id', $userId)->first();  

        $user->in_game = false;
        $user->save(); 

        $serverPlayersEntry = ServerPlayers::where('userId', $userId)->where('placeId', $placeId)->first();

        if (!$game || !$user || !$serverPlayersEntry) {
            return abort(404);
        }
        
        $asset->visits++;
        $asset->playing--;
        $asset->save();

        $serverPlayersEntry->delete();

        return true; // the rcc can read this

    }

    public function addWipeout($userId, $accessKey) {
        
        $ak = "u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn";

        if (!$userId) {
            return abort(400);
        }

        $user = User::where('id', $userId)->first();

        if (!$user) {
            return abort(404);
        }

        if (!$accessKey == $ak) {
            return abort(403);
        }

        $user->wipeouts++;
        $user->save();

        return response('OK')->header('Content-Type', 'text/plain');

    }

    public function addKnockout($userId, $accessKey) {
        
        $ak = "u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn";

        if (!$userId) {
            return abort(400);
        }

        $user = User::where('id', $userId)->first();

        if (!$user) {
            return abort(404);
        }

        if (!$accessKey == $ak) {
            return abort(403);
        }

        $user->knockouts++;
        $user->save();

        return response('OK')->header('Content-Type', 'text/plain');

    }

    // 2012

    public function removeServer($jobId, $accessKey) {
        $ak = "u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn";

        if (!$jobId || !$accessKey) {
            return abort(404);
        }  

        $server = ServerJobs::where('jobId', $jobId)->first();

        if (!$server) {
            return abort(404);
        }

        $server->delete();

        return response('statuscode_complete')->header('Content-Type', 'text/plain');
    }

    public function UpdatePort($placeId, $port, $accessKey) {
        $ak = "u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn";

        if (!$placeId || !$accessKey || $accessKey !== $ak) {
            return abort(400);
        }

        $tickets = GameTickets::where('placeId', $placeId)->get();

        $tickets->port = $port;

        $tickets->save();
        // the gameserver calls this when a job is made
    }

    public function gameserver2012(Request $req) {
            $jobId = $req->jobId;
            $url = "http://noname.xyz";

            if (!$jobId) {
                return die('no');
            }

            $jobb = ServerJobs::where('jobId', $jobId)->first();

            if (!$jobb) {
                return die('oops');
            }

            $gameserver = file_get_contents(resource_path("/roblox/host.lua"));

            $asset = Asset::where('id', $jobb->placeId)->first();

            if (!$asset) {
                return die('oops');
            }

            // serverstart($jobb->port, , $url, )
            $gameserver1 = str_replace("{port}", $jobb->port, $gameserver);
            $gameserver2 = str_replace("{placeId}", $jobb->placeId, $gameserver1);
            $gameserver3 = str_replace("{url}", $url, $gameserver2);
            $gameserver4 = str_replace("{jobId}", $jobb->jobId, $gameserver3);
            $gameserver4 = str_replace("{GE}", $asset->gearsEnabled ? "true" : "false", $gameserver3);

            $sig = SignatureController::sign("\r\n" . $gameserver4);
            $final = "%" . $sig . "%\r\n" . $gameserver4;
            return response($final)
            ->header('Content-Type', 'text/plain')
            ->header('Cache-Control', 'no-cache')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '-1')
            ->header('Last-Modified', gmdate('D, d M Y H:i:s T') . ' GMT')
            ->header('Content-Type', 'text/plain');    }

public static function startServer($placeId, $accessKey) {

    $findJob = ServerJobs::where('placeId', $placeId)->first();

    if ($findJob) {
        return $findJob;
    }

    $url = "http://26.88.250.242:7854/";

    $jobId = Str::uuid();
    $generatedPort = rand(6970, 66666);

    $data = array(
        'acckey' => $accessKey,
        'type' => $jobId,
        'placeid' => $placeId,
        'action' => 0, 
        'port' => $generatedPort,
    );

    $job = [
        'jobId' => $jobId,
        'status' => '2', // 2017 shouldn't join on this, but it will return an error anyway so it's no problem ^_^
        'placeId' => $placeId,
        'port' => $generatedPort,
    ];


    // create the server
    $newJob = ServerJobs::create($job);

    $jsonData = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json', 
        'Content-Length: ' . strlen($jsonData)
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    $response = curl_exec($ch);

    if ($response === false) {
        return null;
    }

    curl_close($ch);
    return $newJob;
}

public function stopServer($jobId, $accessKey) {
    $url = "http://26.88.250.242:7854/";

    $job = ServerJobs::where($jobId);

    if (!$job) {
        return response('err_jobnull')->header('Content-Type', 'text/plain'); 
    }

    $data = array(
        'acckey' => $accessKey,
        'type' => $jobId, 
        'placeid' => 0,
        'action' => 1,
        'port' => 0,
    );

    $jsonData = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json', 
        'Content-Length: ' . strlen($jsonData)
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    $response = curl_exec($ch);

    if ($response === false) {
        return false;
    }

    $job->delete();

    curl_close($ch);

    return true;
}
}
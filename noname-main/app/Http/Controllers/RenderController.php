<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Roblox\Grid\Rcc\RCCServiceSoap;
use App\Roblox\Grid\Rcc\Job;
use App\Roblox\Grid\Rcc\ScriptExecution;
use App\Roblox\Grid\Rcc\Status;
use App\Roblox\Grid\Rcc\LuaType;
use App\Roblox\Grid\Rcc\LuaValue;

use App\Http\Controllers\ThumbnailController;

class RenderController extends Controller
{
    /**
     * Renders a full avatar.
     * @param {number} 1 User ID
     */

    public static function full($id) {
        if (!isset($id)) {
            header("Location: /images/p.png");
            exit();
        }

        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);


        $jobid = "NONAME-ThumbnailJob-" . substr(str_shuffle("01234567890abcdefghijklmnopqrstuvwxyz"), 0, 20);

        $job = new Job($jobid);
        $scriptText = file_get_contents(resource_path('roblox/renderFull.lua'));

        $newScript = str_replace('{{userid}}', $id, $scriptText);
        $okFuckCache = str_replace('{{time}}', time(), $newScript);
    
        $script = new ScriptExecution($jobid ."-Script", $okFuckCache);
        $value = $RCCServiceSoap->BatchJob($job, $script);
        //echo("StringTest1: " . (!is_soap_fault($value[0]) ? ($value[0] !== null ? $value[0] : "null") : "Failed!") . "\n");
        $jobLoad = $RCCServiceSoap->OpenJobEx($job ,$script);

        if (!$jobLoad) {
            return redirect("/images/fuck.png");
        }

        $decoded = base64_decode($jobLoad[0]);
        $pathToSave = public_path('cdn/users/' . $id);
        file_put_contents($pathToSave, $decoded);

        return redirect("/cdn/users/" . $id);
    }

    public function getRender(Request $request) {
        $id = (int) $request->id;

        if (!$id) {
            return redirect('/images/p.png');
        }

        return redirect('/cdn/users/' . $id);
    }

    function pp() {
        $t = ThumbnailController::renderThumbnail(1, false, 720, 500);

        if ($t) {
            return 'ok';
        }

    }

}

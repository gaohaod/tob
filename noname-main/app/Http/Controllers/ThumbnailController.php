<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Roblox\Grid\Rcc\RCCServiceSoap;
use App\Roblox\Grid\Rcc\Job;
use App\Roblox\Grid\Rcc\ScriptExecution;
use App\Roblox\Grid\Rcc\Status;
use App\Roblox\Grid\Rcc\LuaType;
use App\Roblox\Grid\Rcc\LuaValue;

use App\Models\Asset;

class ThumbnailController extends Controller
{
    public static function renderThumbnail($id, $hideSky = false, $width = 720, $height = 500) {
        if (!isset($id) || !isset($hideSky)) {
            header("Location: /images/p.png");
            exit();
        }

        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);

        $jobid = "NONAME-ThumbnailJob-" . substr(str_shuffle("01234567890abcdefghijklmnopqrstuvwxyz"), 0, 20);

        $job = new Job($jobid);

        $scriptText = file_get_contents(resource_path('roblox/renderAsset.lua'));

        // change the settings
        $scriptText2 = str_replace('{{ AssetId }}', $id, $scriptText);
        $finalScript = str_replace('{{ HideSky }}', $hideSky, $scriptText2);
    
        $script = new ScriptExecution($jobid ."-Script", $finalScript);

        $value = $RCCServiceSoap->BatchJob($job, $script);
        $jobLoad = $RCCServiceSoap->OpenJobEx($job ,$script);

        if (!$jobLoad) {
            return redirect("/images/fuck.png");
        }

        $decoded = base64_decode($jobLoad[0]);
        $pathToSave = public_path('cdn/' . $id);
        file_put_contents($pathToSave, $decoded);

        return true;
    }

        public static function renderMesh($id) {
        if (!isset($id)) {
            header("Location: /images/p.png");
            exit();
        }

        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);

        $jobid = "NONAME-ThumbnailJob-" . substr(str_shuffle("01234567890abcdefghijklmnopqrstuvwxyz"), 0, 20);

        $job = new Job($jobid);

        $scriptText = file_get_contents(resource_path('roblox/asset_renders/renderMesh.lua'));

        // change the settings
        $finalScript = str_replace('{Id}', $id, $scriptText);
    
        $script = new ScriptExecution($jobid ."-Script", $finalScript);

        $value = $RCCServiceSoap->BatchJob($job, $script);
        $jobLoad = $RCCServiceSoap->OpenJobEx($job ,$script);

        if (!$jobLoad) {
            return redirect("/images/fuck.png");
        }

        $decoded = base64_decode($jobLoad[0]);
        $pathToSave = public_path('cdn/' . $id);
        file_put_contents($pathToSave, $decoded);

        return true;
    }

    public static function placeThumbnail($id) {
        if (!isset($id)) {
            header("Location: /images/p.png");
            exit();
        }

        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);


        $jobid = "NONAME-ThumbnailJob-" . substr(str_shuffle("01234567890abcdefghijklmnopqrstuvwxyz"), 0, 20);

        $job = new Job($jobid);
        $scriptText = file_get_contents(resource_path('roblox/renderAsset.lua'));

        $final = str_replace('{{assetid}}', $id, $scriptText);
    
        $script = new ScriptExecution($jobid ."-Script", $final);
        $value = $RCCServiceSoap->BatchJob($job, $script);
        //echo("StringTest1: " . (!is_soap_fault($value[0]) ? ($value[0] !== null ? $value[0] : "null") : "Failed!") . "\n");
        $jobLoad = $RCCServiceSoap->OpenJobEx($job ,$script);

        if (!$jobLoad) {
            return redirect("/images/fuck.png");
        }

        $decoded = base64_decode($jobLoad[0]);
        $pathToSave = public_path('cdn/' . $id);
        file_put_contents($pathToSave, $decoded);

        return redirect("/cdn/" . $id);
    }

    public static function renderModel($id) {
        if (!isset($id)) {
            header("Location: /images/p.png");
            exit();
        }

        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);


        $jobid = "NONAME-ThumbnailJob-" . substr(str_shuffle("01234567890abcdefghijklmnopqrstuvwxyz"), 0, 20);

        $job = new Job($jobid);
        $scriptText = file_get_contents(resource_path('roblox/renderModel.lua'));

        $final = str_replace('{{assetid}}', $id, $scriptText);
    
        $script = new ScriptExecution($jobid ."-Script", $final);
        $value = $RCCServiceSoap->BatchJob($job, $script);
        //echo("StringTest1: " . (!is_soap_fault($value[0]) ? ($value[0] !== null ? $value[0] : "null") : "Failed!") . "\n");
        $jobLoad = $RCCServiceSoap->OpenJobEx($job ,$script);

        if (!$jobLoad) {
            return redirect("/images/fuck.png");
        }

        $decoded = base64_decode($jobLoad[0]);
        $pathToSave = public_path('cdn/' . $id);
        file_put_contents($pathToSave, $decoded);

        return redirect("/cdn/" . $id);
    }

    public static function renderPlace($id) {
        if (!isset($id)) {
            header("Location: /images/p.png");
            exit();
        }

        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);

        $jobid = "NONAME-ThumbnailJob-" . substr(str_shuffle("01234567890abcdefghijklmnopqrstuvwxyz"), 0, 20);

        $job = new Job($jobid);

        $scriptText = file_get_contents(resource_path('roblox/renderAsset.lua'));

        // change the settings
        $scriptText2 = str_replace('{{ AssetId }}', $id, $scriptText);
        $finalScript = str_replace('{{ HideSky }}', "false", $scriptText2);
    
        $script = new ScriptExecution($jobid ."-Script", $finalScript);

        $value = $RCCServiceSoap->BatchJob($job, $script);
        $jobLoad = $RCCServiceSoap->OpenJobEx($job ,$script);

        if (!$jobLoad) {
            return redirect("/images/fuck.png");
        }

        $decoded = base64_decode($jobLoad[0]);
        $pathToSave = public_path('cdn/' . $id);
        file_put_contents($pathToSave, $decoded);

        $data = [
            'success' => true,
            'message' => 'item rendered successfully',
        ];

        return response()->json($data);
    }

    public static function renderShirt($id) {
        if (!isset($id)) {
            header("Location: /images/p.png");
            exit();
        }

        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);

        $jobid = "NONAME-ThumbnailJob-" . substr(str_shuffle("01234567890abcdefghijklmnopqrstuvwxyz"), 0, 20);

        $job = new Job($jobid);

        $scriptText = file_get_contents(resource_path('roblox/asset_renders/renderShirt.lua'));

        // change the settings
        $finalScript = str_replace('{{ id }}', $id, $scriptText);
    
        $script = new ScriptExecution($jobid ."-Script", $finalScript);

        $value = $RCCServiceSoap->BatchJob($job, $script);
        $jobLoad = $RCCServiceSoap->OpenJobEx($job ,$script);

        if (!$jobLoad) {
            return redirect("/images/fuck.png");
        }

        $decoded = base64_decode($jobLoad[0]);
        $pathToSave = public_path('cdn/' . $id);
        file_put_contents($pathToSave, $decoded);

        $data = [
            'success' => true,
            'message' => 'item rendered successfully',
        ];

        return response()->json($data);
    }

    public static function renderPants($id) {
        if (!isset($id)) {
            header("Location: /images/p.png");
            exit();
        }

        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);

        $jobid = "NONAME-ThumbnailJob-" . substr(str_shuffle("01234567890abcdefghijklmnopqrstuvwxyz"), 0, 20);

        $job = new Job($jobid);

        $scriptText = file_get_contents(resource_path('roblox/asset_renders/renderPants.lua'));

        // change the settings
        $finalScript = str_replace('{{ id }}', $id, $scriptText);
    
        $script = new ScriptExecution($jobid ."-Script", $finalScript);

        $value = $RCCServiceSoap->BatchJob($job, $script);
        $jobLoad = $RCCServiceSoap->OpenJobEx($job ,$script);

        if (!$jobLoad) {
            return redirect("/images/fuck.png");
        }

        $decoded = base64_decode($jobLoad[0]);
        $pathToSave = public_path('cdn/' . $id);
        file_put_contents($pathToSave, $decoded);

        $data = [
            'success' => true,
            'message' => 'item rendered successfully',
        ];

        return response()->json($data);
    }

    public static function renderTShirt($id) {
        if (!isset($id)) {
            header("Location: /images/p.png");
            exit();
        }

        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);

        $jobid = "NONAME-ThumbnailJob-" . substr(str_shuffle("01234567890abcdefghijklmnopqrstuvwxyz"), 0, 20);

        $job = new Job($jobid);

        $scriptText = file_get_contents(resource_path('roblox/renderTShirt.lua'));

        // change the settings
        $finalScript = str_replace('{{assetid}}', $id++, $scriptText);
    
        $script = new ScriptExecution($jobid ."-Script", $finalScript);

        $value = $RCCServiceSoap->BatchJob($job, $script);
        $jobLoad = $RCCServiceSoap->OpenJobEx($job ,$script);

        if (!$jobLoad) {
            return redirect("/images/fuck.png");
        }

        $decoded = base64_decode($jobLoad[0]);
        $pathToSave = public_path('cdn/' . $id);
        file_put_contents($pathToSave, $decoded);

        $data = [
            'success' => true,
            'message' => 'item rendered successfully',
        ];

        return response()->json($data);
    }

    public static function renderFace($id) {
        if (!isset($id)) {
            header("Location: /images/p.png");
            exit();
        }

        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);

        $jobid = "NONAME-ThumbnailJob-" . substr(str_shuffle("01234567890abcdefghijklmnopqrstuvwxyz"), 0, 20);

        $job = new Job($jobid);

        $scriptText = file_get_contents(resource_path('roblox/asset_renders/renderFace.lua'));

        // change the settings
        $finalScript = str_replace('{{assetid}}', $id++, $scriptText);
    
        $script = new ScriptExecution($jobid ."-Script", $finalScript);

        $value = $RCCServiceSoap->BatchJob($job, $script);
        $jobLoad = $RCCServiceSoap->OpenJobEx($job ,$script);

        if (!$jobLoad) {
            return redirect("/images/fuck.png");
        }

        $decoded = base64_decode($jobLoad[0]);
        $pathToSave = public_path('cdn/' . $id);
        file_put_contents($pathToSave, $decoded);

        $data = [
            'success' => true,
            'message' => 'item rendered successfully',
        ];

        return response()->json($data);
    }

    public static function renderHead($id) {
        if (!isset($id)) {
            header("Location: /images/p.png");
            exit();
        }

        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);

        $jobid = "NONAME-ThumbnailJob-" . substr(str_shuffle("01234567890abcdefghijklmnopqrstuvwxyz"), 0, 20);

        $job = new Job($jobid);

        $scriptText = file_get_contents(resource_path('roblox/asset_renders/renderHead.lua'));

        // change the settings
        $finalScript = str_replace('{{headid}}', $id, $scriptText);
    
        $script = new ScriptExecution($jobid ."-Script", $finalScript);

        $value = $RCCServiceSoap->BatchJob($job, $script);
        $jobLoad = $RCCServiceSoap->OpenJobEx($job ,$script);

        if (!$jobLoad) {
            return redirect("/images/fuck.png");
        }

        $decoded = base64_decode($jobLoad[0]);
        $pathToSave = public_path('cdn/' . $id);
        file_put_contents($pathToSave, $decoded);

        $data = [
            'success' => true,
            'message' => 'item rendered successfully',
        ];

        return response()->json($data);
    }

    public static function renderHat($id) {
        if (!isset($id)) {
            header("Location: /images/p.png");
            exit();
        }

        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);

        $jobid = "NONAME-ThumbnailJob-" . substr(str_shuffle("01234567890abcdefghijklmnopqrstuvwxyz"), 0, 20);

        $job = new Job($jobid);

        $scriptText = file_get_contents(resource_path('roblox/asset_renders/renderHat.lua'));

        // change the settings
        $finalScript = str_replace('{{ hatId }}', $id, $scriptText);
    
        $script = new ScriptExecution($jobid ."-Script", $finalScript);

        $value = $RCCServiceSoap->BatchJob($job, $script);
        $jobLoad = $RCCServiceSoap->OpenJobEx($job ,$script);

        if (!$jobLoad) {
            return redirect("/images/fuck.png");
        }

        $decoded = base64_decode($jobLoad[0]);
        $pathToSave = public_path('cdn/' . $id);
        file_put_contents($pathToSave, $decoded);

        $data = [
            'success' => true,
            'message' => 'item rendered successfully',
        ];

        return response()->json($data);
    }

    public static function renderGear($id) {
        if (!isset($id)) {
            header("Location: /images/p.png");
            exit();
        }

        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);

        $jobid = "NONAME-ThumbnailJob-" . substr(str_shuffle("01234567890abcdefghijklmnopqrstuvwxyz"), 0, 20);

        $job = new Job($jobid);

        $scriptText = file_get_contents(resource_path('roblox/asset_renders/renderGear.lua'));

        // change the settings
        $finalScript = str_replace('{{ hatId }}', $id, $scriptText);
    
        $script = new ScriptExecution($jobid ."-Script", $finalScript);

        $value = $RCCServiceSoap->BatchJob($job, $script);
        $jobLoad = $RCCServiceSoap->OpenJobEx($job ,$script);

        if (!$jobLoad) {
            return redirect("/images/fuck.png");
        }

        $decoded = base64_decode($jobLoad[0]);
        $pathToSave = public_path('cdn/' . $id);
        file_put_contents($pathToSave, $decoded);

        $data = [
            'success' => true,
            'message' => 'item rendered successfully',
        ];

        return response()->json($data);
    }

    function test() {
        $result = self::renderThumbnail(1, false, 720, 500);

        if ($result) {
            return 'ok';
        }
        
    }


        public static function renderUser($id) {
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

}

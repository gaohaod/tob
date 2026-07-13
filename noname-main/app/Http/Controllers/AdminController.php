<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use App\Roblox\Grid\Rcc\RCCServiceSoap;
use App\Roblox\Grid\Rcc\Job;
use App\Roblox\Grid\Rcc\ScriptExecution;
use App\Roblox\Grid\Rcc\Status;
use App\Roblox\Grid\Rcc\LuaType;
use App\Roblox\Grid\Rcc\LuaValue;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\Asset;
use App\Models\Owned;
use App\Models\Invites;
use App\Models\Messages;
use App\Models\Alerts;

use App\Http\Controllers\ThumbnailController;

use App\Jobs\ThumbnailJob;

class AdminController extends Controller
{
    public function getJobs() {
        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);
    
        $jobs = $RCCServiceSoap->GetAllJobs();
    
        $jobsArray = [$jobs];
    
        $jobsArray = array_map(function($job) {
            return (array) $job;
        }, $jobsArray);

        return view('admin.instances.jobs', ['jobs' => $jobsArray]);
    }

    public function getPendingAssets() {
        $underReview = Asset::where('under_review', 1)
        ->whereNotIn('type', ['clothing'])
        ->paginate(12);

        return view('admin.pending.assets', compact('underReview'));
    }
    

    public function UploadHat(Request $request) {

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'fileupload' => 'required|file',
            'for2012' => 'nullable|string|in:on,off',
        ];
    
        $validated = $request->validate($rules);
    
        $shouldbetwelve = ($validated['for2012'] ?? null) === "on";


        $asset = Asset::create([
            'name' => $validated['name'],
            'type' => 'hat',
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => $validated['price'],
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'This item has no description. Wow.',
            'year' => 2016,
            'for2012' => $shouldbetwelve,
        ]);
    
        $extension = $validated['fileupload']->getClientOriginalExtension();
        if (!in_array($extension, ['rbxm', 'rbxmx'])) {
            return redirect()->back()->with('message', 'Only valid R* hats are allowed.');
        }
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $fileContents = file_get_contents($validated['fileupload']->getPathname());
    
        $finalContents = str_replace('Accessory', 'Hat', $fileContents);
    
        file_put_contents($uploadPath . '/' . $id, $finalContents);
    
        ThumbnailController::renderHat($id); 
    
        $webhookData = [
            "content" => null,
            "embeds" => [
            [
                "title" => $asset->name,
                "description" => $asset->description,
                "url" => "http://noname.xyz/app/item/$asset->id",
                "color" => 10348842,
                "author" => [
                "name" => "A new item has released to the catalog!",
                "url" => "http://noname.xyz/app/item/$asset->id"
                ],
                "footer" => [
                "text" => "This is automated and occurs when a hat is uploaded to the catalog."
                ]
            ]
            ],
            "attachments" => []
        ];

        $url = "https://discord.com/api/webhooks/1335371846425509929/ankR1hriuXaUak92woT9gputodJASlFPXktTG-f8gveucFTCMtasASVI2Lk_wHkwZkod";
        $headers = [ 'Content-Type: application/json; charset=utf-8' ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhookData));
        $response   = curl_exec($ch);

        return redirect()->back()->with('message', 'Uploaded.');
    }

    public function UploadGear(Request $request) {

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'fileupload' => 'required|file',
            'for2012' => 'nullable|string|in:on,off',
        ];
    
        $validated = $request->validate($rules);
    
        $shouldbetwelve = ($validated['for2012'] ?? null) === "on";

        $asset = Asset::create([
            'name' => $validated['name'],
            'type' => 'gear',
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => $validated['price'],
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'This item has no description. Wow.',
            'year' => 2016,
            'for2012' => $shouldbetwelve,
        ]);
    
        $extension = $validated['fileupload']->getClientOriginalExtension();
        if (!in_array($extension, ['rbxm', 'rbxmx'])) {
            return redirect()->back()->with('message', 'Only valid R* gears are allowed.');
        }
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $validated['fileupload']->move($uploadPath, $id);
    
        ThumbnailController::renderGear($id); 

        $webhookData = [
            "content" => null,
            "embeds" => [
            [
                "title" => $asset->name,
                "description" => $asset->description,
                "url" => "http://noname.xyz/app/item/$asset->id",
                "color" => 10348842,
                "author" => [
                "name" => "A new item has released to the catalog!",
                "url" => "http://noname.xyz/app/item/$asset->id"
                ],
                "footer" => [
                "text" => "This is automated and occurs when a hat is uploaded to the catalog."
                ]
            ]
            ],
            "attachments" => []
        ];

        $url = "https://discord.com/api/webhooks/1335371846425509929/ankR1hriuXaUak92woT9gputodJASlFPXktTG-f8gveucFTCMtasASVI2Lk_wHkwZkod";
        $headers = [ 'Content-Type: application/json; charset=utf-8' ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhookData));
        $response   = curl_exec($ch);
    
        return redirect()->back()->with('message', 'Uploaded.');
    }

    public function UploadHead(Request $request) {

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'fileupload' => 'required|file',
        ];
    
        $validated = $request->validate($rules);
    
        $asset = Asset::create([
            'name' => $validated['name'],
            'type' => 'head',
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => $validated['price'],
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'This item has no description. Wow.',
            'year' => 2016,
            'for2012' => true,
        ]);
    
        $extension = $validated['fileupload']->getClientOriginalExtension();
        if (!in_array($extension, ['rbxm', 'rbxmx'])) {
            return redirect()->back()->with('message', 'Only valid R* heads are allowed.');
        }
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $validated['fileupload']->move($uploadPath, $id);
    
        ThumbnailController::renderHead($id); 

        $webhookData = [
            "content" => null,
            "embeds" => [
            [
                "title" => $asset->name,
                "description" => $asset->description,
                "url" => "http://noname.xyz/app/item/$asset->id",
                "color" => 10348842,
                "author" => [
                "name" => "A new item has released to the catalog!",
                "url" => "http://noname.xyz/app/item/$asset->id"
                ],
                "footer" => [
                "text" => "This is automated and occurs when a hat is uploaded to the catalog."
                ]
            ]
            ],
            "attachments" => []
        ];

        $url = "https://discord.com/api/webhooks/1335371846425509929/ankR1hriuXaUak92woT9gputodJASlFPXktTG-f8gveucFTCMtasASVI2Lk_wHkwZkod";
        $headers = [ 'Content-Type: application/json; charset=utf-8' ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhookData));
        $response   = curl_exec($ch);
    
        return redirect()->back()->with('message', 'Uploaded.');
    }

    public function approveAsset($id) {
        $asset = Asset::where('id', $id)->where('under_review', 1)->first();

        $asset->under_review = false;

        $asset->save();

switch ($asset->type):
    case "model":
        ThumbnailJob::dispatch("model", $id);
        break;
    case "shirt":
        ThumbnailJob::dispatch("shirt", $id);
        break;
    case "pants":
        ThumbnailJob::dispatch("pants", $id);
        break;
    case "tshirt":
        ThumbnailJob::dispatch("tshirt", $id);
        break;
    case "place":
        ThumbnailJob::dispatch("place", $id);
        break;
    default:
        break;
endswitch;


        return redirect()->back(); 
    }

    public function declineAsset($id) {
        $asset = Asset::where('id', $id)->with('user')->first();

        $uploadPath = storage_path('app/public/asset');
        $cdnPath = public_path('cdn');

        if (!$asset->type === "model") {
            // nuke it off the cdn!
            $asset_file_1 = $id - 1; 
            $asset_file_2 = $id;

            $file_path_1 = $uploadPath . DIRECTORY_SEPARATOR . $asset_file_1;
            $file_path_2 = $uploadPath . DIRECTORY_SEPARATOR . $asset_file_2;

            File::Delete($file_path_1);
            File::Delete($file_path_2);
        } else {
            $asset_file_other = $id;
            $file_path_other = $uploadPath . DIRECTORY_SEPARATOR . $asset_file_other;

            if ($asset->type == "shirt") {
                $cdn_path = $cdnPath . DIRECTORY_SEPARATOR . $id;
            } else {
                $cdn_path = $cdnPath . DIRECTORY_SEPARATOR . $id - 1;
            }
            
            File::Delete($cdn_path);
            File::Delete($file_path_other);
        }

        $asset->banned = true;
        $asset->under_review = false;

        $wearing = Owned::where('itemId', $id)->get();

        foreach ($wearing as $wore) {
            $wore->user->peeps = $wore->user->peeps + $wore->peeps;
            $wore->user->save();

            Messages::create([
            'senderId' => 14,
            'recieverId' => $asset->user->id,
            'content' => "ATTENTION CITIZEN! 市民请注意!
This is the Central Intelligentsia of the Chinese Communist Party. 您的 Internet 浏览器历史记录和活动引起了我们的注意 YOUR INTERNET ACTIVITY HAS ATTRACTED OUR ATTENTION. 因此，您的个人资料中的 11115 ( -11115 Social Credits) 个社会积分将打折 DO NOT DO THIS AGAIN! 不要再这样做! If you do not hesitate, more Social Credits ( -11115 Social Credits )will be subtracted from your profile, resulting in the subtraction of ration supplies. (由人民供应部重新分配 CCP) You'll also be sent into a re-education camp in the Xinjiang Uyghur Autonomous Zone. 如果您毫不犹豫，更多的社会信用将从您的个人资料中打折，从而导致口粮供应减少 您还将被送到新疆维吾尔自治区的再教育营
为党争光! Glory to the CCP!我不瘋！我知道他交換了這些號碼！我知道那是 1216 年。大憲章之後的一年。好像我可能會犯這樣的錯誤。絕不。絕不！我只是——我只是無法證明這一點。他──他掩蓋了自己的蹤跡，他讓影印店裡的那個白痴替他撒了謊。你覺得這是什麼東西？你認為這很糟糕嗎？這？這詭計？他做得更糟。那個廣告看板！你是在告訴我，一個人只是碰巧就這樣跌倒了嗎？不！是他精心策劃的！吉米！他透過天窗排便！而我救了他！我不應該這樣做。我把他帶進我自己的公司了！我在想什麼？他永遠不會改變。他永遠不會改變！從9歲開始，一直都是這樣！雙手無法離開錢箱！但不是我們的吉米！不可能是珍貴的吉米！偷他們瞎子！他還成為律師！？真是個病態的玩笑！我應該一有機會就阻止他！而你──你必須阻止他！你-",
            'subject' => "A reminder",
            'read' => false,
            'moderated' => false,
            'archived' => false,
            ]);

            NotificationController::send($asset->user->id, 'You have a system notification', 'circle-exclamation');

            $wore->delete();
        }

        $asset->save();

        return redirect()->back(); 
    }

        public function declineAssetAndBanCreator($id) {
        $asset = Asset::where('id', $id)->with('user')->first();

        $uploadPath = storage_path('app/public/asset');

        if (!$asset->type === "model") {
            // nuke it off the cdn!
            $asset_file_1 = $id - 1; 
            $asset_file_2 = $id;

            $file_path_1 = $uploadPath . DIRECTORY_SEPARATOR . $asset_file_1;
            $file_path_2 = $uploadPath . DIRECTORY_SEPARATOR . $asset_file_2;

            File::Delete($file_path_1);
            File::Delete($file_path_2);
        } else {
            $asset_file_other = $id;
            $file_path_other = $uploadPath . DIRECTORY_SEPARATOR . $asset_file_other;

            File::Delete($file_path_other);
        }

        $asset->banned = true;
        $asset->under_review = false;

        $asset->save();

        $asset->user->banned = true;
        $asset->user->ban_reason = "An asset you made violated our rules.";

        $wearing = Owned::where('itemId', $id)->get();

        foreach ($wearing as $wore) {
            $wore->user->peeps = $wore->user->peeps + $wore->peeps;
            $wore->user->save();
            $wore->delete();
        }

        $asset->user->save();

        return redirect()->back(); 
    }

    public function createKey() {
        $key = config("app.name", "Laravel") . "-" . Str::uuid();

        $invite_tobemade = [
            "key" => $key,
            "used" => 0,
        ];

        Invites::create($invite_tobemade);

        $data = [
            "key" => $key,
            "success" => true,
        ];

        return response()->json($data);
    }

    public function viewInvites() {
        $invites = Invites::get();

        return view('admin.invites', compact('invites'));
    }

    public function revokeInvite($inv) {
        $invite = Invites::where('key', $inv);

        if (!$invite && !$inv) {
            return abort(404);
        }

        $invite->delete();

        return redirect()->back();
    }

    public function renewInvite($inv) {
        $invite = Invites::where('key', $inv)->first();

        if (!$invite && !$inv) {
            return abort(404);
        }

        $invite->used = false;
        $invite->save();

        return redirect()->back();
    }

    public function banUser($username) {

        dd($username);

        if (!$username) {
            return abort(403);
        }

        $user = User::where('name', $username)->first();

        if (!$user) {
            return abort(404);
        }

        $user->banned = true;
        $user->ban_reason = "Moderated.";

        $user->save();

        return redirect()->back();
    }

    public function unbanUser($username) {
        $user = User::where('name', $username)->first();

        if (!$user) {
            return abort(404);
        }

        $user->banned = false;
        $user->ban_reason = null;

        $user->save();

        return redirect()->back();
    }

public function banAsset($id) {
    if (!$id) {
        return abort(403);
    }

    \Log::info('Attempting to ban asset with ID: ' . $id);

    $asset = Asset::where('id', $id)->first();

    if (!$asset) {
        \Log::error('Asset not found for ID: ' . $id);
        return abort(404);
    }

    $asset->banned = true;

    $asset->save();

    \Log::info('Asset banned successfully with ID: ' . $id);

    return redirect()->back();
}

    public function createAlert(Request $request) {
        $rules = [
            'color' => 'required|string|in:success,danger,warning,info',
            'content' => 'required|string|max:1000',
        ];

        $valiated = $request->validate($rules);

        Alerts::create([
            'color' => $valiated['color'],
            'content' => $valiated['content'],
        ]);

        return redirect()->back()->with('success', 'Alert created');
    }

    public function alerts() {
        $alerts = Alerts::get();

        return view('admin.alerts', compact('alerts'));
    }

    public function deleteAlert($id) {
        $alert = Alerts::where('id', $id)->first();

        if (!$alert) {
            return response('alert doesnt exist');
        }

        $alert->delete();

        return redirect()->back();
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\UploadRequest;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\ThumbnailController;

use Illuminate\Support\Facades\Auth;

use App\Models\Asset;
use App\Models\Videos;

use App\Jobs\ThumbnailJob;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:a,m,d,ts,s,p,l,sm',
            'fileupload' => 'required|file',
        ];
    
        switch ($request->type) {
            case 'a':
                $rules['fileupload'] = 'required|file|mimes:mp3,wav,ogg|max:9500'; 
                break;
            case 'm':
                $rules['fileupload'] = 'required|file|max:15360'; 
                break;
            case 'd':
                $rules['fileupload'] = 'required|image|max:5120'; 
                break;
            case 'ts':
                $rules['fileupload'] = 'required|file|mimes:png,jpg,jpeg|max:5120'; 
                $rules['price'] = 'required|numeric|min:0|max:50';
                break;
            case 's':
                $rules['fileupload'] = 'required|file|mimes:png,jpg,jpeg|max:5120'; 
                $rules['price'] = 'required|numeric|min:0|max:50';
                break;
            case 'p':
                $rules['fileupload'] = 'required|image|max:30000'; 
                $rules['price'] = 'required|numeric|min:0|max:50';
                $rules['max_players'] = 'numeric|min:1|max:500';
                break;
            case 'l':
                $rules['fileupload'] = 'required|file|mimes:lua|max:5120'; 
                break;
        }
    
        $validated = $request->validate($rules);
    
        switch ($validated['type']) {
            case 'a':
                return $this->handleAudioUpload($validated);
            case 'm':
                return $this->handleModelUpload($validated);
            case 'ts':
                return $this->handleTShirtUpload($validated);
            case 's':
                return $this->handleShirtUpload($validated);
            case 'p':
                return $this->handlePantsUpload($validated);
            case 'd':
                return $this->handleDecalUpload($validated);
            case 'l':
                return $this->handleScriptUpload($valdiated);
            case 'sm';
                return $this->handleMeshUpload($request);
        }
    }
       
    private function handleAudioUpload($validated)
    {
        try {
            $asset = Asset::create([
                'name' => Str::uuid()->toString() . '_audio',
                'type' => "clothing",
                'off_sale' => 1,
                'peeps' => 0,
                'creator_id' => Auth::id(),
                'under_review' => 0,
                'year' => 2016,
                'thumbnailUrl' => 'pp',
                'description' => $validated['description'] ?? 'Audio Asset',
                'off_sale' => true,
                'for2012' => true,
            ]);
    
            $id = $asset->id;
            $uploadPath = storage_path('app/public/asset');
    
            if ($validated['fileupload']->isValid()) {
                $validated['fileupload']->move($uploadPath, $id);
            } else {
    
                \Log::error('File upload failed', [
                    'file' => $validated['fileupload'],
                    'error' => $validated['fileupload']->getError(),
                ]);
                return response()->json(['message' => 'File upload failed.'], 422);
            }
    
            $this->generateXml($id, 'audio', $validated['name'], $validated['description']);
    
            return redirect()->back()->with('message', 'Uploaded.');
        } catch (\Exception $e) {
            return redirect()->back()->with('message', 'Sorry, an error occured.');
        }
    }
    
    private function handleModelUpload($validated)
    {
        $asset = Asset::create([
            'name' => $validated['name'],
    'type' => $validated['type'] === 'a' ? 'audio' : ($validated['type'] === 'm' ? 'model' : 'clothing'),
            'creator_id' => Auth::id(),
            'under_review' => 1,
            'peeps' => 0,
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'Model Asset',
            'year' => 2016,
            'for2012' => true,
        ]);
    
        $extension = $validated['fileupload']->getClientOriginalExtension(); // workaround because for some reason rbxmx mimes are ignored
        if (!in_array($extension, ['rbxm', 'rbxmx'])) {
            return response()->json(['message' => 'Only valid R* models are allowed.'], 422);
        }

        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $validated['fileupload']->move($uploadPath, $id);

        Auth::user()->peeps = Auth::user()->peeps - 1;
        Auth::user()->save();

        // render it!!!!
        ThumbnailJob::dispatch('model', $id); // ++ the id because i think it'd give the xml - Due to moderation, i feel like it would be better NOT to render the model
    
        return redirect()->back()->with('message', 'Uploaded.');
    }
    
    private function handleTShirtUpload($validated)
    {
        $asset = Asset::create([
            'name' => Str::uuid()->toString() . '_asset',
    'type' => $validated['type'] === 'a' ? 'audio' : ($validated['type'] === 'm' ? 'model' : 'clothing'),
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => 0,
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'T-Shirt Asset',
            'year' => 2016,
            'for2012' => true,
        ]);
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $validated['fileupload']->move($uploadPath, $id);
        ThumbnailController::renderTShirt($id--);
    
        $this->generateXml($id, 'tshirt', $validated['name'], $validated['description'], $validated['price']);

        Auth::user()->peeps = Auth::user()->peeps - 5;
        Auth::user()->save();

        return redirect()->back()->with('message', 'Uploaded. Your T-Shirt is currently waiting for approval.');
    }

    private function handleShirtUpload($validated)
    {
        $asset = Asset::create([
            'name' => Str::uuid()->toString() . '_asset',
    'type' => $validated['type'] === 'a' ? 'audio' : ($validated['type'] === 'm' ? 'model' : 'clothing'),
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => 0,
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'Shirt Asset',
            'year' => 2016,
            'for2012' => true,
        ]);
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $validated['fileupload']->move($uploadPath, $id);
    
        $this->generateXml($id, 'shirt', $validated['name'], $validated['description'], $validated['price']);
        ThumbnailJob::dispatch("shirt", $id);

        Auth::user()->peeps = Auth::user()->peeps - 5;
        Auth::user()->save();

        return redirect()->back()->with('message', 'Uploaded. Your shirt is currently waiting for approval.');
    }

    private function handlePantsUpload($validated)
    {
        $asset = Asset::create([
            'name' => Str::uuid()->toString() . '_asset',
            'type' => 'clothing',
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => 0,
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'Pants Asset',
            'year' => 2016,
            'for2012' => true,
        ]);
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $validated['fileupload']->move($uploadPath, $id);
    

        $this->generateXml($id, 'pants', $validated['name'], $validated['description'], $validated['price']);
        ThumbnailJob::dispatch("pants", $id);

        Auth::user()->peeps = Auth::user()->peeps - 5;
        Auth::user()->save();

        return redirect()->back()->with('message', 'Uploaded. Your pants is currently waiting for approval.');
    }

    public function uploadFace(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:40',
            'description' => 'nullable|string|max:300',
            'price' => 'required|numeric',
            'fileupload' => 'required|image|max:5120|mimes:jpg,jpeg,png',
        ];
    
        $lol = $request->validate($rules);

        $asset = Asset::create([
            'name' => Str::uuid()->toString() . '_here_asset',
            'type' => 'clothing',
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => $lol['price'],
            'thumbnailUrl' => 'pp',
            'description' => $lol['description'] ?? 'This item has no description.',
            'year' => 2016,
            'for2012' => true,
        ]);
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');

        $lol['fileupload']->move($uploadPath, $id);
    
        $this->generateXml($id, 'face', $lol['name'], $lol['description'], $lol['price'], 0);

        $id2 = $id - 1;

        $webhookData = [
            "content" => null,
            "embeds" => [
            [
                "title" => $lol['name'],
                "description" => $lol['description'], 
                "url" => "http://noname.xyz/app/item/$id2",
                "color" => 10348842,
                "author" => [
                "name" => "A new item has released to the catalog!",
                "url" => "http://noname.xyz/app/item/$id2"
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

private function handleMeshUpload(Request $request) {

    $rules = [
        'name' => 'required|string|max:150',
        'description' => 'nullable|string|max:255',
        'type' => 'required|in:a,m,d,ts,s,p,l,sm',
        'fileupload' => 'required|file|max:5120',
    ];

    $validated = $request->validate($rules);

    $extension = $validated['fileupload']->getClientOriginalExtension(); 

    if (!in_array($extension, ['obj', 'mesh'])) {
        return redirect()->back()->with('message', 'Only valid R* places are allowed.');
    }

    $asset = Asset::create([
        'name' => $validated['name'],
        'description' => $validated['description'] ?? "Mesh Asset",
        'creator_id' => Auth::id(),
        'type' => 'mesh',
        'under_review' => 1,
        'peeps' => 0,
        'thumbnailUrl' => 'pp',
        'year' => 2016,
        'for2012' => true,
    ]);

    $id = $asset->id;

    $uploadPath = storage_path('app/public/asset');

    $filename = $id;
    $validated['fileupload']->move($uploadPath, $filename);

    Auth::user()->peeps = Auth::user()->peeps - 5;
    Auth::user()->save();

    ThumbnailJob::dispatch('mesh', $id);

    return redirect()->back()->with('message', 'Uploaded.');
}

    
    private function handleDecalUpload($validated)
    {
        $asset = Asset::create([
            'name' => Str::uuid()->toString() . '_here_asset',
            'type' => 'clothing',
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => 0,
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'Image Asset',
            'year' => 2016,
            'for2012' => true,
        ]);
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $validated['fileupload']->move($uploadPath, $id);
    
        $this->generateXml($id, 'decal', $validated['name'], $validated['description']);

        Auth::user()->peeps = Auth::user()->peeps - 1;
        Auth::user()->save();
    
        return redirect()->back()->with('message', 'Uploaded.');
    }
    
    private function handleScriptUpload($validated) {
        $asset = Asset::create([
            'name' => $validated->name,
            'type' => 'script',
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => 0,
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'deprecated',
            'year' => 2016,
            'for2012' => true,
        ]);
    


    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $validated['fileupload']->move($uploadPath, $id);
    
        return redirect()->back()->with('message', 'Uploaded.');
    }

    private function generateXml($id, $templateFile, $name, $description, $price = 0, $underreview = 1)
    {
        $fuckshitpenis = Asset::create([
            'name' => (string) $name, 
            'type' => $templateFile,
            'peeps' => $price,
            'creator_id' => Auth::id(),
            'under_review' => $underreview,
            'year' => 2016,
            'thumbnailUrl' => 'pp',
            'description' => $description ?? 'XML Asset',
            'for2012' => true,
        ]);

        $PleaseGodKillMeForFucksSakeAlreadyIAmInSoMuchPainRightNowSoStopItPleaseThankYou = $fuckshitpenis->id - 1;

        $xmlTemplate = file_get_contents(resource_path('xml/' . $templateFile . '.xml'));
        $xmlContent = str_replace('{{ id }}', $PleaseGodKillMeForFucksSakeAlreadyIAmInSoMuchPainRightNowSoStopItPleaseThankYou, $xmlTemplate);
    
        Storage::put('public/asset/' . $fuckshitpenis->id, $xmlContent);
    }

    public function uploadPlace(Request $request)
    {

    if (Auth::user()->place_slots_left == 0) {
        return redirect()->back()->with('message', 'Only valid R* places are allowed.');
    }
    $rules = [
        'name' => 'required|string|max:40',
        'description' => 'nullable|string|max:300',
        'fileupload' => 'required|file|max:30000',
        'thumbnail' => 'nullable|image|max:5120',
        'max_players' => 'required|integer|max:50',
        'yearxd' => 'required|integer|in:2017,2012',
        'gearsEnabled' => 'required|string|in:on,off',
    ];

    $validated = $request->validate($rules);

    $extension = $validated['fileupload']->getClientOriginalExtension(); // workaround because for some reason rbxmx mimes are ignored
    if (!in_array($extension, ['rbxl', 'rbxlx'])) {
        return redirect()->back()->with('message', 'Only valid R* places are allowed.');
    }

    if ($validated['gearsEnabled']) {
        $gearsquestionmark = true;
    } else {
        $gearsquestionmark = false;
    }

    $asset = Asset::create([
        'name' => $validated['name'],
        'description' => $validated['description'],
        'type' => 'place',
        'peeps' => 0,
        'thumbnailUrl' => 'pp',
        'creator_id' => Auth::id(),
        'under_review' => 1,
        'year' => $validated['yearxd'],
        'max_players' => $validated['max_players'],
        'description' => $validated['description'] ?? 'This place has no description.',
        'gearsEnabled' => $gearsquestionmark,
    ]);

    $asset->peeps = 0;

    $id = $asset->id;
    $uploadPath = storage_path('app/public/places');

    $validated['fileupload']->move($uploadPath, $id);

    if ($request->hasFile('thumbnail')) {
        $thumbnailPath = public_path('cdn');
        $request->file('thumbnail')->move($thumbnailPath, $id);
    } else {
        ThumbnailJob::dispatch("place", $id); // render the place thumbnail
    }

    Auth::user()->place_slots_left--;
    Auth::user()->save();

    return redirect('/app/place/' . $id);
}

public function uploadVideo(Request $request) {
    if (!Auth::user()->admin) {
        return abort(401);
    }

    $rules = [
        'title' => 'required|string|max:155',
        'description' => 'required|string|max:255',
        'video' => 'required|mimes:mp4,mov,avi,wmv|max:100000', // max size in KB
    ];

    $validated = $request->validate($rules);

    $uploadPath = public_path('cdn/videos');

    if ($request->hasFile('video')) {

        $lastVideo = Videos::latest('id')->first();
        $nextId = $lastVideo ? $lastVideo->id + 1 : 1;

        $video = $request->file('video');

        $mimeType = $video->getMimeType();
        if (!in_array($mimeType, ['video/mp4', 'video/avi', 'video/quicktime', 'video/wmv'])) {
            return back()->withErrors(['video' => 'Invalid video format.']);
        }

        $video->move($uploadPath, $nextId);


        $databasevideo = Videos::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'creatorId' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'Video uploaded successfully!');
    }

    return back()->withErrors(['video' => 'No video file uploaded.']);
}


}

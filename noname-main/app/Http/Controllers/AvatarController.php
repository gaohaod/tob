<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Owned;
use App\Models\Bodycolors;
use App\Models\Asset;
use App\Models\User;

class AvatarController extends Controller
{
    function charapp($id, Request $request) {
        if (!isset($id)) {
            return abort(400);
        }

        $rcc = $request->query('rcc') ?? false;
    
        $twelve = $request->query('twelve') ?? 0;

        $urls = [];
        $urls[] = 'http://noname.xyz/Asset/BodyColors.ashx?t=' . time() . '&id=' . $id;
    
        if ($twelve == 1) {
                $ownedItems = Owned::where('userId', $id)
                ->where('wearing', 1)
                ->where('for2012', 1)
                ->get(['itemId']);
                
        } else {
        $ownedItems = Owned::where('userId', $id)
            ->where('wearing', 1)
            ->get(['itemId']); 
        }

        foreach ($ownedItems as $item) {
            if ($rcc) {
                $url = 'http://noname.xyz/asset/?id=' . $item->itemId . '&rcc=true';
                $urls[] = $url; 
            } else {
                $url = 'http://noname.xyz/asset/?id=' . $item->itemId;
                $urls[] = $url; 
            }
        }
    
        $result = implode(';', $urls);

        $new = $result . ";";
    
        return response($new)->header('Content-Type', 'text/plain');
    }


    function bodycolors(Request $req) {
        $id = $req->id;

        if (!isset($id)) {
            return abort(400);
        }

        $bc = Bodycolors::where('userId', $id)->first();
        if (!$bc) {
            return abort(404);
        }

        $data =  '
<roblox
	xmlns:xmime="http://www.w3.org/2005/05/xmlmime"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://www.noname.xyz/roblox.xsd" version="4">
	<External>null</External>
	<External>nil</External>
	<Item class="BodyColors">
		<Properties>
			<int name="HeadColor">' . $bc->head . '</int>
			<int name="LeftArmColor">' . $bc->larm . '</int>
			<int name="LeftLegColor">' . $bc->lleg . '</int>
			<string name="Name">Body Colors</string>
			<int name="RightArmColor">' . $bc->rarm . '</int>
			<int name="RightLegColor">' . $bc->rleg . '</int>
			<int name="TorsoColor">' . $bc->torso . '</int>
			<bool name="archivable">true</bool>
		</Properties>
	</Item>
</roblox>
';

    return response($data)
    ->header('Content-Type', 'text/xml')
    ->header('Cache-Control', 'no-cache')
    ->header('Pragma', 'no-cache')
    ->header('Expires', '-1')
    ->header('Last-Modified', gmdate('D, d M Y H:i:s T') . ' GMT');

    }

    function show() {
        $bodyColors = Bodycolors::where('userId', Auth::id())->first();

        $valid_types = ['hat', 'shirt', 'pants', 'tshirt', 'head', 'gear', 'face'];


        $hats = Owned::where('userId', Auth::id())
            ->whereHas('asset', function ($query) {
                $query->where('type', 'hat');
                $query->whereNotIn('type', ['clothing']);
            })
            ->with('asset')
            ->orderBy('created_at', 'DESC')
            ->paginate(24, ['*'], 'hats');

        $shirts = Owned::where('userId', Auth::id())
            ->whereHas('asset', function ($query) {
                $query->where('type', 'shirt');
                $query->whereNotIn('type', ['clothing']);
            })
            ->with('asset')
            ->orderBy('created_at', 'DESC')
            ->paginate(24, ['*'], 'shirts');

        $pants = Owned::where('userId', Auth::id())
            ->whereHas('asset', function ($query) {
                $query->where('type', 'pants');
                $query->whereNotIn('type', ['clothing']);
            })
            ->with('asset')
            ->orderBy('created_at', 'DESC')
            ->paginate(24, ['*'], 'pants');

        $tshirts = Owned::where('userId', Auth::id())
            ->whereHas('asset', function ($query) {
                $query->where('type', 'tshirt');
                $query->whereNotIn('type', ['clothing']);
            })
            ->with('asset')
            ->orderBy('created_at', 'DESC')
            ->paginate(24, ['*'], 'tshirts');

        $heads = Owned::where('userId', Auth::id())
            ->whereHas('asset', function ($query) {
                $query->where('type', 'head');
                $query->whereNotIn('type', ['clothing']);
            })
            ->with('asset')
            ->orderBy('created_at', 'DESC')
            ->paginate(24, ['*'], 'heads');

        $gears = Owned::where('userId', Auth::id())
            ->whereHas('asset', function ($query) {
                $query->where('type', 'gear');
                $query->whereNotIn('type', ['clothing']);
            })
            ->with('asset')
            ->orderBy('created_at', 'DESC')
            ->paginate(24, ['*'], 'gears');

        $faces = Owned::where('userId', Auth::id())
            ->whereHas('asset', function ($query) {
                $query->where('type', 'face');
                $query->whereNotIn('type', ['clothing']);
            })
            ->with('asset')
            ->orderBy('created_at', 'DESC')
            ->paginate(24, ['*'], 'faces');


        return view('avatar', compact('bodyColors', 'hats', 'shirts', 'pants', 'tshirts', 'heads', 'gears', 'faces'));
    }

    function changeBodyColor($color, $part)
    {
        // valid colors and parts
        $validColors = [
            1, 2, 3, 5, 6, 8, 11, 12, 21, 22, 23, 24, 25, 26, 27, 28, 36, 37, 38, 39, 40,
            41, 42, 43, 45, 47, 48, 49, 50, 100, 101, 102, 104, 105, 106, 107, 108, 110,
            111, 112, 115, 116, 118, 119, 120, 121, 123, 124, 126, 127, 128, 131, 133, 134,
            135, 136
        ];
    
        $validParts = ['head', 'torso', 'larm', 'rarm', 'lleg', 'rleg'];
    
        if (!$color || !in_array($color, $validColors)) {
            return response('Invalid color', 400);
        }
    
        if (!$part || !in_array($part, $validParts)) {
            return response('Invalid part', 400);
        }
    
        if (!Auth::check()) {
            return response('Unauthorized', 403);
        }

        $bodycolors = Bodycolors::where('userId', Auth::id())->first();
    
        if (!$bodycolors) {
            $bodycolors = new Bodycolors();
            $bodycolors->userId = Auth::id();
        }

        $bodycolors->$part = $color;
        $bodycolors->save();
    
        return response('Body color updated successfully');
    }

    function wearItem($id) {
        if (!Auth::check()) {
            return response('error', 401);
        }

        if (empty($id)) {
            return response('error', 400);
        }

        $owned = Owned::where('userId', Auth::id())
                      ->where('itemId', $id)
                      ->with('asset') 
                      ->first();

        if (!$owned || !$owned->asset) {
            return response('error', 404); 
        }

        $limits = [
            'hat' => 3,
            'shirt' => 1,
            'pants' => 1,
            'tshirt' => 1,
            'gear' => 3,
            'head' => 1,
            'face' => 1,
        ];

        $type = $owned->asset->type;

        if (!isset($limits[$type])) {
            return response('error', 400);
        }

        $wornItemsCount = Owned::where('userId', Auth::id())
            ->where('wearing', 1)
            ->whereHas('asset', function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->count();

        if ($owned->wearing == 0 && $wornItemsCount >= $limits[$type]) {
            return response('error', 400);
        }

        $owned->wearing = $owned->wearing ? 0 : 1;

        try {
            $owned->save();
        } catch (\Exception $e) {
            return response('error', 500);
        }

        return response('ok', 200);
    }

    function getItems($type) {
        if (!isset($type)) {
            return abort(404);
        }

        $valid_types = ['hat', 'shirt', 'pants', 'tshirt', 'head', 'gear', 'face'];

        if (!in_array($type, $valid_types)) {
            return abort(400); //lol
        }

        $items = Owned::where('userId', Auth::id())
        ->whereHas('asset', function ($query) use ($type) {
            $query->where('type', $type); 
        })
        ->with('asset') 
        ->paginate(12);

        if (!$items) {
            return abort(404);
        }

        return response()->json($items);
    }
    
}

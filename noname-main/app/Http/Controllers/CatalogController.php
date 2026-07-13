<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

use App\Models\Asset;
use App\Models\Owned;
use App\Models\User;

class CatalogController extends Controller
{

    public function getTransactions() {
        $transactions = Owned::where('userId', Auth::id())
        ->orderBy('created_at', 'DESC')
        ->with('asset.user')
        ->paginate(10);

        return view('transactions.view', compact('transactions'));
    }

    function buy($id) {

        $data = [];
        $dontincludepls = ['place', 'clothing', 'model', 'audio', 'decal', 'mesh'];
        
        if (Auth::user()->verified_via_discord) {

        // check if user is authed
        if (!Auth::check()) {
            $data = [
                'success' => false,
                'message' => 'Authentication error.',
            ];
            return response()->json($data);
        }    

        $item = Asset::where('id', $id)
        ->where('banned', 0)
        ->whereNotIn('type', $dontincludepls)
        ->first();

        if (!$item) {
            $data = [
                'success' => false,
                'message' => 'An unknown error occured. (ext)',
            ];
            return response()->json($data);
        }

        $itemPrice = $item->peeps;
        $userBalance = Auth::user()->peeps;

        $ownedItem = Owned::where('userId', Auth::id())
        ->where('itemId', $item->id)
        ->first();

        if ($ownedItem) {
            $data = [
                'success' => false,
                'message' => 'You already own this item.',
            ];
            return response()->json($data);
        }

        if ($item->off_sale == 1) {
            $data = [
                'success' => false,
                'message' => 'This item is off-sale.',
            ];
            return response()->json($data);
        }

        if ($userBalance < $itemPrice) {
            $data = [
                'success' => false,
                'message' => 'You can\'t afford this item.',
            ];
            return response()->json($data);
        }
        
        Owned::create([
            'userId' => Auth::id(),
            'itemId' => $item->id,
            'wearing' => 0,
            'for2012' => $item->for2012,
        ]);

        $itemCreator = User::find($item->creator_id);

        if ($itemCreator) {
            $itemCreator->peeps = (int) $itemCreator->peeps + $itemPrice;
            $itemCreator->save();
        }
    
        $authUser = Auth::user();
        $authUser->peeps = $userBalance - $itemPrice;
        $authUser->save();

        $data = [
            'success' => true,
            'message' => '成功向中国捐款。',
        ];

        return response()->json($data);

    } else {
        $data = [
            'success' => false,
            'message' => 'you must be verified to buy stuff lol',
        ];

        return response()->json($data);
    }

    }

    public function index() {

        $dontincludepls = ['place', 'clothing', 'model', 'audio', 'decal', 'mesh'];

        $items = Asset::whereNotIn('type', $dontincludepls)
        ->where('under_review', 0)
        ->where('banned', 0)
        ->orderBy('created_at', 'DESC')
        ->paginate(18);

        // view time
        return view('catalog', compact('items'));
    }

    public function sort($by) {
            switch ($by) {
            case 'gears':
                $items = Asset::where('type', 'gear')
                ->with('user')
                ->orderBy('created_at', 'DESC')
                ->paginate(18);
            break;
            case 'faces':
                $items = Asset::where('type', 'face')
                ->with('user')
                ->orderBy('created_at', 'DESC')
                ->paginate(18);
            break;
            case 'tshirts':
                $items = Asset::where('type', 'tshirt')
                ->where('under_review', 0)
                ->where('banned', 0)
                ->orderBy('created_at', 'DESC')
                ->with('user')
                ->paginate(18);
            break;
            case 'pants':
                $items = Asset::where('type', 'pants')
                ->where('under_review', 0)
                ->where('banned', 0)
                ->orderBy('created_at', 'DESC')
                ->with('user')
                ->paginate(18);
            break;
            case 'shirts':
                $items = Asset::where('type', 'shirt')
                ->where('under_review', 0)
                ->where('banned', 0)
                ->with('user')
                ->orderBy('created_at', 'DESC')
                ->paginate(18);
            break;
            case 'heads':
                $items = Asset::where('type', 'head')
                ->with('user')
                ->orderBy('created_at', 'DESC')
                ->paginate(18);
            break;
            case 'hats':
                $items = Asset::where('type', 'hat')
                ->with('user')
                ->orderBy('created_at', 'DESC')
                ->paginate(18);
            break;
            case 'models':
                $items = Asset::where('type', 'model')
                ->where('under_review', 0)
                ->where('banned', 0)
                ->where('off_sale', 0)
                ->orderBy('created_at', 'DESC')
                ->with('user')
                ->paginate(18);
                break;
            case 'decals':
                $items = Asset::where('type', 'decal')
                ->where('under_review', 0)
                ->where('off_sale', 0)
                ->where('banned', 0)
                ->orderBy('created_at', 'DESC')
                ->with('user')
                ->paginate(18);
                break;
            case 'audios':
                $items = Asset::where('type', 'audio')
                ->where('under_review', 0)
                ->where('off_sale', 0)
                ->where('banned', 0)
                ->orderBy('created_at', 'DESC')
                ->with('user')
                ->paginate(18);
                break;
            case 'mesh':
                $items = Asset::where('type', 'mesh')
                ->where('under_review', 0)
                ->where('off_sale', 0)
                ->where('banned', 0)
                ->orderBy('created_at', 'DESC')
                ->with('user')
                ->paginate(18);
                break;
            default:
                $dontincludepls = ['place', 'clothing', 'model', 'audio', 'decal', 'mesh'];

                $items = Asset::whereNotIn('type', $dontincludepls)
                ->with('user')
                ->orderBy('created_at', 'DESC')
                ->paginate(18);
                // show all items
            break;
        }

    return view('catalog', compact('items'));

    }

    public function show($id) {

        $dontincludepls = ['place', 'clothing', 'model', 'audio', 'decal'];

        $item = Asset::whereNotIn('type', $dontincludepls)
        ->where('banned', 0)
        ->where('under_review', 0)
        ->where('id', $id)
        ->with('user')
        ->first();

        $owned = Owned::where('userId', Auth::id())
        ->where('itemId', $id)
        ->first();

        $sales = Owned::where('itemId', $id)->count();

        if (!$item) {
            return abort(404);
        }

        return view('view.item', compact('item', 'owned', 'sales'));
    }

    public function model($id) {

        $dontincludepls = ['place', 'clothing', 'gear', 'face', 'tshirt', 'pants', 'shirt', 'head', 'hat'];

        $item = Asset::whereNotIn('type', $dontincludepls)
        ->where('banned', 0)
        ->where('under_review', 0)
        ->where('id', $id)
        ->with('user')
        ->first();

        if (!$item) {
            return abort(404);
        }

        return view('view.model', compact('item'));
    }

    public function search(Request $request)
    {
    $search = $request->input('search');
    $items = Asset::where('type', 'hat')
    ->where('name', 'like', "%$search%")
    ->where('banned', 0)
    ->with('user')
    ->paginate(18);

    return view('catalog', compact('items', 'search'));

    }

    public function buyPlaceSlot() {
        $data = [];
        $placeSlotPrice = 250; // 250 peeps for one is reasonable... right..?
        $userPeeps = Auth::user()->peeps;
        $maxSlots = 3;

        if ($userPeeps < $placeSlotPrice) {
            $data = [
                'success' => false,
                'message' => 'You don\'t have enough Peeps to buy a place slot.',
            ];

            return response()->json($data);
        }

        if (Auth::user()->place_slots_left >= $maxSlots) {
            $data = [
                'success' => false,
                'message' => 'You can\'t buy any more slots. Use up your current slots and try again later.',
            ];

            return response()->json($data);
        }   

        Auth::user()->peeps = Auth::user()->peeps - $placeSlotPrice;
        Auth::user()->place_slots_left++;
        Auth::user()->save();

        $data = [
            'success' => true,
            'message' => 'You have ' . Auth::user()->place_slots_left . ' place slots now.',
        ];

        return response()->json($data);
    }

}

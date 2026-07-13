<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Videos;

class VideoController extends Controller
{
    public function index() {
        $videos = Videos::with('user')->orderBy('created_at', 'DESC')->paginate(16);

        return view('videos', compact('videos'));
    }

    public function view($id) {
        $video = Videos::where('id', $id)->with('user')->first();

        if (!$video) {
            return abort(404);
        }

        return view('view.video', compact('video'));
    }
}

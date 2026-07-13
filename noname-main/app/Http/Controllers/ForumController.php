<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Forum;
use App\Models\Replies;
use App\Models\UserVote;

use App\Http\Controllers\NotificationController;

class ForumController extends Controller
{

    public function vote(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:forum,id',
            'type' => 'required|in:up,down,none',
        ]);

        $userId = auth()->id();
        $forumId = $request->query('id');
        $voteType = $request->query('type');

        $forum = Forum::findOrFail($forumId);

        $existingVote = UserVote::where('user_id', $userId)
            ->where('forum_id', $forumId)
            ->first();

        if ($existingVote) {
            if ($existingVote->vote_type === 'up') {
                $forum->decrement('votes');
            } elseif ($existingVote->vote_type === 'down') {
                $forum->increment('votes');
            }

            if ($existingVote->vote_type === $voteType) {
                $existingVote->delete();
                return redirect()->back();
            }
        }

        if ($voteType === 'up') {
            $forum->increment('votes');
        } elseif ($voteType === 'down') {
            $forum->decrement('votes');
        }

        UserVote::updateOrCreate(
            ['user_id' => $userId, 'forum_id' => $forumId],
            ['vote_type' => $voteType]
        );

        return redirect()->back();
    }

    public function showForum() {
           $forumSections = [
    [
        'id' => 0,
        'name' => 'Announcements',
        'description' => 'Announcements for ' . config('app.name', 'Laravel') . '.',
        'post_count' => $announcements_post_count,
        'last_post' => $announcements
    ],
    [
        'id' => 1,
        'name' => 'General',
        'description' => 'For generic topics related to ' . config('app.name', 'Laravel') . '.',
        'post_count' => $general_post_count,
        'last_post' => $general
    ],
    [
        'id' => 2,
        'name' => 'Off-Topic',
        'description' => 'For off-topic purposes. Self-explanatory.',
        'post_count' => $off_topic_post_count,
        'last_post' => $off_topic
    ],
    [
        'id' => 3,
        'name' => 'Development/Scripting',
        'description' => 'Have an issue with a script? Come here and (hopefully) someone will help you!',
        'post_count' => $dev_post_count,
        'last_post' => $dev
    ],
    [
        'id' => 4,
        'name' => 'Help',
        'description' => 'Report bugs, or issues with ' . config('app.name', 'Laravel') . "'s client/launcher? Ask here and a developer will help you!",
        'post_count' => $help_post_count,
        'last_post' => $help
    ],
    [
        'id' => 5,
        'name' => 'Politics',
        'description' => 'Joe birden came to my house and bit me in the ass!!!',
        'post_count' => $politics_post_count,
        'last_post' => $politics
    ],
];

    return view('forum.index', compact('forumSections'));
    }

    public function viewCategory($category_id)
    {
        $this->validateCategoryId($category_id);

        $posts = Forum::where('category', $category_id)
        ->orderBy('pinned', 'DESC')
        ->orderBy('votes', 'DESC')
        ->orderBy('created_at', 'DESC')
        ->orderBy('banned', 'ASC')
        ->with('user')
        ->withCount('replies') 
        ->paginate(18);

        $categoryNames = $this->getCategoryNames();
        $category_name = $categoryNames[$category_id] ?? '406';

        return view('forum.view-category', compact('posts', 'category_name', 'category_id'));
    }

    public function viewPost($post_id)
    {
        $post = Forum::with('user', 'replies.user')->findOrFail($post_id);
    
        $user = $post->user;
    
        $postCount = $user->postCount();
    
        $categoryNames = $this->getCategoryNames();
        $category_name = $categoryNames[$post->category] ?? '406';
    
        return view('forum.view-post', [
            'post' => $post,
            'replies' => $post->replies()->with('user')->paginate(5),
            'category_name' => $category_name,
            'category_id' => $post->category,
            'postCount' => $postCount,
            'repliesWithPostCount' => $post->replies->map(function ($reply) {
                return [
                    'id' => $reply->id,
                    'userPostCount' => $reply->user->postCount(),
                    'reply' => $reply->reply,
                    'banned' => $reply->banned,
                    'posterId' => $reply->posterId,
                    'created_at' => $reply->created_at,
                    'posterUsername' => $reply->user->name,
                    'verified_via_discord' => $reply->user->verified_via_discord,
                ];
            }),
        ]);
    }
    
    public function index() {

        $announcements = Forum::orderBy('created_at', 'DESC')->with('user')->where('category', 0)->where('pinned', 0)->first();
        $announcements_post_count = Forum::where('category', 0)->count();

        $general = Forum::orderBy('created_at', 'DESC')->with('user')->where('category', 1)->where('pinned', 0)->first();
        $general_post_count = Forum::where('category', 1)->count();

        $off_topic = Forum::orderBy('created_at', 'DESC')->with('user')->where('category', 2)->where('pinned', 0)->first();
        $off_topic_post_count = Forum::where('category', 2)->count();

        $dev = Forum::orderBy('created_at', 'DESC')->with('user')->where('category', 3)->where('pinned', 0)->first();
        $dev_post_count = Forum::where('category', 3)->count();

        $help = Forum::orderBy('created_at', 'DESC')->with('user')->where('category', 4)->where('pinned', 0)->first();
        $help_post_count = Forum::where('category', 4)->count();

        $politics = Forum::orderBy('created_at', 'DESC')->with('user')->where('category', 5)->where('pinned', 0)->first();
        $politics_post_count = Forum::where('category', 5)->count();

        return view('forum', compact('announcements', 'announcements_post_count', 'general', 'general_post_count', 'off_topic', 'off_topic_post_count', 'dev', 'dev_post_count', 'help', 'help_post_count', 'politics', 'politics_post_count'));

    }

    public function replyToPost($post_id)
    {
        $post = Forum::findOrFail($post_id);
        return view('forum.reply', compact('post'));
    }

    public function newPost($category_id)
    {
        if ($category_id == 0 && Auth::user()->admin == false) {
            return abort(403);
        }
        return view('forum.new-post', compact('category_id'));
    }

    public function createPost($category_id, Request $request)
    {

        if ($category_id == 0 && Auth::user()->admin == false) {
            return abort(403);
        }

        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:50'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        Forum::create([
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'posterId' => Auth::id(),
            'category' => $category_id,
        ]);

        return redirect('/app/forum/' . $category_id);
    }

    public function createReply($post_id, Request $request)
    {
        $pp = Forum::where('locked', false)->where('id', $post_id)->first();

        if (!$pp) {
            return abort(401);
        }

        $validated = $request->validate([
            'reply' => ['required', 'string', 'max:500'],
        ]);

        Replies::create([
            'reply' => $validated['reply'],
            'posterId' => Auth::id(),
            'replyPostId' => $post_id,
        ]);

        if ($pp->posterId !== Auth::user()->name) {
            NotificationController::send($pp->posterId, Auth::user()->name . " replied to your post", "comments-alt");
        }

        return redirect('/app/forum/view/' . $post_id);
    }

    private function validateCategoryId($category_id)
    {
        if ($category_id < 0 || $category_id > 5) {
            abort(404);
        }
    }

    private function getCategoryNames()
    {
        return [
            0 => 'Announcements',
            1 => 'General',
            2 => 'Off-Topic',
            3 => 'Development/Scripting',
            4 => 'Help',
            5 => 'Politics',
        ];
    }

    public function toggleLock($postId) {
        if (!$postId) {
            return abort(400);
        }

        $post = Forum::where('id', $postId)->with('user')->first();

        if (!$post) {
            return abort(404);
        }

        if (!Auth::user()->id == $post->user->id) {
            return abort(401);
        }


        if ($post->locked == true) {
            $post->locked = false;
            $post->save();
            return redirect()->back();
        } else {
            $post->locked = true;
            $post->save();
            return redirect()->back();
        }
    }

    public function togglePin($postId) {
        if (!$postId) {
            return abort(400);
        }

        $post = Forum::where('id', $postId)->with('user')->first();

        if (!$post) {
            return abort(404);
        }

        if (!Auth::user()->admin) {
            return abort(401);
        }


        if ($post->pinned == true) {
            $post->pinned = false;
            $post->save();
            return redirect()->back();
        } else {
            $post->pinned = true;
            $post->save();
            if ($post->posterId !== Auth::id()) {
                NotificationController::send($post->posterId, "Your post was pinned!", "thumbtack");
            }
            return redirect()->back();
        }
    }

    public function deletePost($postId) {
        if (!$postId) {
            return abort(400);
        }

        $post = Forum::where('id', $postId)->with('user')->first();

        if (!$post) {
            return abort(404);
        }

        if (Auth::user()->id != $post->user->id && !Auth::user()->admin) {
            NotificationController::send($post->posterId, "Your post was moderated", "ban");
            return abort(401);
        }



        if (Auth::user()->id == $post->user->id) {
            $post->delete();
            return redirect(route('app.forum'));
        } else {
            if ($post->banned == 1) {
                $post->banned = 0;
            } else {
                $post->banned = 1;
            }

            $post->save();

            return redirect()->back();
        }

    }

    public function moderateReply($replyId) {
        if (!$replyId) {
            return abort(400);
        }

        $post = Replies::where('id', $replyId)->first();

        if (!$post || !Auth::user()->admin) {
            return abort(401);
        }

        if ($post->banned == 1) {
            $post->banned = 0;
        } else {
            if ($post->posterId !== Auth::id()) {
                NotificationController::send($post->posterId, "Your reply was moderated", "ban");
            }
            $post->banned = 1;
        }

        $post->save();
        return redirect()->back();
    }

}
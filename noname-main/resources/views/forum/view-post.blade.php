@section('title', $post->subject)
<x-app-layout>
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">{{ config('app.name', 'Laravel') }}</a></li>
                <li class="breadcrumb-item"><a href="/app/forum">Forum</a></li>
                <li class="breadcrumb-item"><a href="/app/forum/{{ $category_id }}">{{ $category_name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $post->subject }}</li>
            </ol>
        </nav>
        <x-card :title="'original post - ' . $post->subject">
            <div class="row">
                <div class="col-auto text-center">
                    <img src="/cdn/users/{{ $post->posterId }}?t={{ time() }}" alt="{{ $post->user->name }}" width="130" class="mb-1">
                    <p class="fw-medium mb-0">{{ $post->user->name }} <x-online-status :online="$post->user->isActive()" /> <x-gender-icon :gender="$post->user->gender" />  </p>
                    <p class="text-muted fw-regular mb-2">Joined {{ $post->user->created_at->diffForHumans() }}</p>

{!! $post->locked ? "<p class='mb-1 text-muted'>Post locked.</p>" : "<button class='btn btn-success w-100 mb-1' onclick=\"document.location = '/app/forum/reply/" . $post->id . "'\"><i class='far fa-reply mr-2'></i>reply</button>" !!}


                    
                    <div class="text-center">

                        @if ($post->banned)
                            <p class="text-muted my-3">You can't vote.</p>
                        @else
                        <div id="voting_panel" class="my-3">
                            <span class="p-1 bg-light rounded border px-2 text-muted">{{ $post->votes }}</span>
                            <span class="mx-2 text-muted">|</span>
                            <button class="btn btn-sm btn-success p-1 px-2 mr-1 d-inline-block" onclick="document.location = '/app/forum/vote?id={{ $post->id }}&type=up'">
                                <i class="far fa-arrow-up align-middle"></i>
                            </button>
                            <button class="btn btn-sm btn-danger p-1 px-2 d-inline-block" onclick="document.location = '/app/forum/vote?id={{ $post->id }}&type=down'">
                                <i class="far fa-arrow-down align-middle"></i>
                            </button>
                        </div>

                        @endif

                        @if (Auth::user()->admin)
                        <button class="btn" onclick="document.location = '/app/forum/pin/{{ $post->id }}'">{!! $post->pinned ? "<i class='text-danger far fa-thumbtack'></i>" : "<i class='far fa-thumbtack'></i>" !!}</button>
                        @endif
                        @if ($post->user->id == Auth::user()->id || Auth::user()->admin)
                        <button class="btn text-danger" onclick="document.location = '/app/forum/delete/{{ $post->id }}'"><i class="far {{ $post->banned ? 'fa-recycle' : 'fa-trash' }}"></i></button>
                        @endif
                        @if ($post->user->id == Auth::user()->id)
                        <button class="btn" onclick="document.location = '/app/forum/lock/{{ $post->id }}'">{!! $post->locked ? "<i class='far fa-unlock'></i>" : "<i class='far fa-lock'></i>" !!}</button>
                        @endif
                    </div>
                </div>
                <div class="col">
                    @if ($post->banned == 1) 
                    <p class="text-danger fw-regular mb-0" id="forum-parse">
                            <i>[ Post removed by moderation ]</i>
                    </p>
                        @else
                    <p class="text-muted fw-regular mb-0" data-forum-content id="forum-parse">
                        {!! nl2br(htmlspecialchars($post->body)) !!}
                    </p>
                        @endif
                    </p>

                </div>                    
            </div>
        </x-card>   

        @if ($replies->isEmpty()) 
            <div class="mt-3">
                <p class="text-center mb-0 text-muted">This post has no replies.</p>
            </div>
        @else

            <!--<div class="mt-3 d-flex justify-content-center w-100">
                {{ $replies->links() }}
            </div> FIXME-->

            @foreach ($repliesWithPostCount as $data)
            <div class="mt-3">
            <x-card :title="'Reply - ' .  $data['created_at'] " :bg="'header-purple'" data-forum-content>
                <div class="row">
                    <div class="col-auto text-center">
                        <img src="/cdn/users/{{ $data['posterId'] }}?t={{ time() }}" alt="{{ $data['posterUsername'] }}" width="95" class="mb-1">
                        <p class="fw-medium mb-2">{{ $data['posterUsername'] }} {!! $data['verified_via_discord'] ? "<span class='ml-1 text-success'><i class='far fa-check-circle'></i></span>" : "" !!}</p>

                        <div class="text-center">
                        @if ($post->user->id == Auth::user()->id || Auth::user()->admin)
                        <button class="btn text-danger" onclick="document.location = '/app/reply/delete/{{ $data['id'] }}'"><i class="far fa-recycle"></i></button>
                        @endif
                        </div>
                    </div>
                    <div class="col">
                    @if ($data['banned'] == 1) 
                    <p class="text-danger fw-regular mb-0">
                            <i>[ Post removed by moderation ]</i>
                    </p>
                        @else
                    <p class="text-muted fw-regular mb-0 reply" id="forum-reply-{{ $data['id'] }}">
                        {!! nl2br(htmlspecialchars($data['reply'])) !!}
                    </p>
                        @endif
                    </div>
                </div>
            </x-card>
        </div>
            @endforeach
        @endif

    </div>

    <script src="/functions.js"></script>
    <script>

        function convertToImages(content) {
    return content.replace(customImageRegex, (match) => {
        return `<img src="${match}" style="width: auto; height: 150px;" />`;
    });
}

    const emojis = {
        "cowboy": "/images/emojis/cowboy.png",
        "frown": "/images/emojis/sad.png",
        "grimace": "/images/emojis/grimace.png",
        "liar": "/images/emojis/liar.png",
        "money_mouth": "/images/emojis/money.png",
        "neutral_face": "/images/emojis/neutral.png",
        "rolling_eyes": "/images/emojis/rollingeyes.png",
        "scream": "/images/emojis/scream.png",
        "exhausted": "/images/emojis/smh.png",
        "smile": "/images/emojis/smile.png",
        "wide_smile": "/images/emojis/smile_alt.png",
        "smiling": "/images/emojis/smiling.png",
        "sunglasses": "/images/emojis/sunglasses.png",
        "thinking_face": "/images/emojis/thinking.png",
        "upside_down": "/images/emojis/upsidedown.png",
        "yum": "/images/emojis/yum.png",
        "duhh": "/images/emojis/duh.png",
    };

function replaceEmojis(text) {
    const emojiRegex = /:([a-zA-Z0-9_]+):/g;

    return text.replace(emojiRegex, (match, emojiname) => {
        if (emojis[emojiname]) {
            return `<img src="${emojis[emojiname]}" class="align-middle" width="16" alt="${emojiname}">`;
        }
        return match;
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const forumPosts = document.querySelectorAll('[data-forum-content], .forum-post, .reply');

    forumPosts.forEach((forumPost) => {
        forumPost.innerHTML = replaceEmojis(forumPost.innerHTML);
    });
});



        parseForum();
    </script>
</x-app-layout>
@section('title', 'Message - ' . $message->subject)
<x-app-layout>
	<div class="container mt-5">

		@if ($message->archived)
			<div class="alert alert-warning mb-3">Heads up! This message is archived.</div>
		@endif

		<button class="btn btn-success mb-2" onclick="document.location = '/app/messages/archive/{{ $message->id }}'"><i class="far fa-archive"></i></button>
		<x-card title="view messsage - {{ $message->subject }}" nocaps="yep">

			<div class="row">
				<div class="col-auto text-center">
					<img src="/cdn/users/{{ $message->sender->id }}?t={{ time() }}" width="100" class="mb-3">

					<p class="mb-0">{{ $message->sender->name }} <x-gender-icon :gender="$message->sender->gender" /> <x-online-status :online="$message->sender->isActive()" /></p>

					@if ($message->senderId === Auth::id())
						<button class="btn" onclick="document.location = '/app/messages/delete/{{ $message->id }}'"><i class="far fa-trash"></i></button>
					@endif
				</div>

				<div class="col">
					<p class="message">
						
						{!! nl2br(htmlspecialchars($message->content)) !!}

					</p>
				</div>

			</div>
		</x-card>
	<div>

		<script>
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
    const forumPosts = document.querySelectorAll('.message');

    forumPosts.forEach((forumPost) => {
        forumPost.innerHTML = replaceEmojis(forumPost.innerHTML);
    });
});

		</script>
</x-app-layout>
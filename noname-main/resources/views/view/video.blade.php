@section('title', $video->title)

@auth
<x-app-layout>
	<div class="container mt-5">
		<x-card title="video - {{ $video->title }}">
			<video class="rounded" id="player" width="100%" style="max-height: 750px;" playsinline controls>
				<source src="/cdn/videos/{{ $video->id }}" />
			</video>

			<h3 class="font-weight-light mt-3">{{ $video->title }}</h3>
			<h5 class="text-muted">by <a href="/app/user/{{ $video->creatorId }}">{{ $video->user->name }}</a></h5>

			<p class="text-muted mb-0">
				{!! nl2br(htmlspecialchars($video->description)) !!}
			</p>
		</x-card>

@auth
		<div class="mt-3">
			<div id="disqus_thread"></div>
<script>
   var disqus_developer = 1;
    /**
    *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
    *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables    */
    
    var disqus_config = function () {
    this.page.url = 'n0name.xyz';  // Replace PAGE_URL with your page's canonical URL variable
    this.page.identifier = '{{ $video->title . '-' . $video->id }}'; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
    };
    
    (function() { // DON'T EDIT BELOW THIS LINE
    var d = document, s = d.createElement('script');
    s.src = 'https://n0name-xyz.disqus.com/embed.js';
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
</div>
@endauth
		</div>
	</div>
<script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
	<script>
		const player = new Plyr('#player');
	</script>

</x-app-layout>
@endauth
@guest
<x-guest-layout>
	<link rel="stylesheet" href="/plyr.css" />
	<div class="container mt-5">
		<x-card title="video - {{ $video->title }}">
			<video class="rounded" id="player" width="100%" style="max-height: 750px;" playsinline controls>
				<source src="/cdn/videos/{{ $video->id }}" />
			</video>

			<h3 class="font-weight-light mt-3">{{ $video->title }}</h3>
			<h5 class="text-muted">by <a href="/app/user/{{ $video->creatorId }}">{{ $video->user->name }}</a></h5>

			<p class="text-muted mb-0">
				{!! nl2br(htmlspecialchars($video->description)) !!}
			</p>
		</x-card>

	</div>
<script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
	<script>
		const player = new Plyr('#player');
	</script>

</x-guest-layout>
@endguest
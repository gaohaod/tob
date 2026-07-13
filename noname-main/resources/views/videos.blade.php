@section('title', 'videos')
@auth
<x-app-layout>
	<div class="container mt-5">

		<div class="text-center">
			<img src="/images/nonamevideo.png" width="100" class="mb-5">
		</div>

		<div class="alert alert-info">Videos are still in beta, if you have any bugs to report, post it on the Forums</div>

		<h3 class="font-weight-light mb-2">Videos</h3>

		@if ($videos->isEmpty())
		<p class="text-center text-muted">No videos here.</p>
		@else
		<div class="row">
			@foreach ($videos as $video)
			<div class="col-md-3 mb-3">
				<x-video-card :title="$video->title" :creator="$video->user->name" :id="$video->id" /> 
			</div>
			@endforeach
		</div>

		            <div class="mt-3 d-flex justify-content-center w-100">
                {{ $videos->links() }}
            </div>
		@endif

	</div>
</x-app-layout>
@endauth

@guest
<x-guest-layout>
	<link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro@f96a46a/css/all.css" rel="stylesheet" type="text/css" />
<nav class="navbar navbar-expand-lg navbar-dark bg-success border-bottom shadow-sm">
    <div class="container">
  <a class="navbar-brand fst-italic fw-medium" href="/">{{ config('app.name', 'Laravel') }}</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link {{ request()->is('/') ? 'active' : ''}}" href="/">Home <span class="sr-only">(current)</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ request()->is('app/videos', 'app/videos/*') ? 'active' : ''}}" href="/app/videos">Videos</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('app/register') ? 'active' : ''}}" href="/app/register">Register</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('app/login') ? 'active' : ''}}" href="/app/login">Log in</a>
        </li>
    </ul>
  </div>
  </div>
</nav>
	<div class="container mt-5">

		<div class="alert alert-info">Videos are still in beta, if you have any bugs to report, post it on the Forums</div>

		<h3 class="font-weight-light mb-2">Videos</h3>

		@if ($videos->isEmpty())
		<p class="text-center text-muted">No videos here.</p>
		@else
		<div class="row">
			@foreach ($videos as $video)
			<div class="col-md-3 mb-3">
				<x-video-card :title="$video->title" :creator="$video->user->name" :id="$video->id" /> 
			</div>
			@endforeach
		</div>

		            <div class="mt-3 d-flex justify-content-center w-100">
                {{ $videos->links() }}
            </div>
		@endif

	</div>
</x-guest-layout>
@endguest
@section('title', 'Users')
@auth
	<x-app-layout>
		<div class="container mt-5">

		 <form action="/app/users/search" method="GET">
		 	<div class="input-group mb-4">
  					<input type="text" class="form-control" name="keyword" value="{{ $keyword ?? '' }}" placeholder="Search for users...">
  					<span class="input-group-btn">
    					<button class="btn btn-success funnybutton" type="button"><i class="far fa-search mr-3"></i>search</button>
  					</span>
  				
			</div>
			</form>

		@if ($users->isEmpty())
			<p class="text-center text-muted">There's no one around.</p>
		@else
			<div class="row">
				@foreach ($users as $user)
				<div class="col-2 mb-4">
					<div class="card p-2 card-body text-center clickable" onclick="document.location = '/app/user/{{ $user->id }}'">
						<img src="/cdn/users/{{ $user->id }}" width="auto" class="mb-3">

						<h5 class="text-muted font-weight-regular mb-1 text-truncate">{{ $user->name }}</h5>

						<p class="{{ $user->isActive() ? 'text-success' : 'text-muted' }} mb-3">@if (!$user->in_game) {{ $user->isActive() ? '[ Online ] ' : '[ ' . $user->last_seen->diffForHumans() . ' ]' }} @else <span class="text-info">[ In-Game ]</span> @endif</p>

					</div>

				</div>
				@endforeach
			</div>
		@endif

		</div>
	</x-app-layout>
@endauth

@guest
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

		@if ($users->isEmpty())
			<p class="text-center text-muted">There's no one around.</p>
		@else
			<div class="row">
				@foreach ($users as $user)
				<div class="col-2 mb-4">
					<div class="card p-2 card-body text-center">
						<img src="/cdn/users/3" width="auto" class="mb-3">

						<h5 class="text-muted font-weight-regular mb-1">User</h5>

						<p class="text-success mb-3">[ In Game ]</p>

					</div>

				</div>
				@endforeach
			</div>
		@endif

		</div>
@endguest
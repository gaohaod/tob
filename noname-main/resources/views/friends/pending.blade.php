@section('title', 'Pending Friends')

<x-app-layout>
	<div class="container mt-5">
		@if ($friends->isEmpty())
			<div class="my-4 text-center justify-content-center">
				<img src="/images/sweat.png" width="100" class="mb-3">
				<p clas="mb-0 text-muted">You haven't added anyone yet.</p>
			</div>
		@else
			<div class="row">
				@foreach ($friends as $user)
					<div class="col-2 mb-4">
						<div class="card p-2 card-body text-center clickable" onclick="document.location = '/app/user/{{ $user->id }}'">
							<img src="/cdn/users/{{ $user->id }}" width="auto" class="mb-3">

							<h5 class="text-muted font-weight-regular mb-1 text-truncate">{{ $user->name }}</h5>

							<p class="{{ $user->isActive() ? 'text-success' : 'text-muted' }} mb-3">@if (!$user->in_game) {{ $user->isActive() ? '[ Online ] ' : '[ ' . $user->last_seen->diffForHumans() . ' ]' }} @else <span class="text-info">[ In-Game ]</span> @endif</p>

							<button class="mb-0 btn btn-success" onclick="document.location = '/app/friend/decline/{{ $user->id }}'"><i class="far fa-times mr-2"></i>cancel</button>

						</div>

					</div>
				@endforeach
			</div>
		@endif
	</div>
</x-app-layout>
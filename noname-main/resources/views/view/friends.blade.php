@section('title', $user->name . '\'s Friends')
<x-app-layout>
	<div class="container mt-5">
		<x-card title="{{ $user->name }}'s Friends ({{ $friends->count() }})">
			@if ($friends->isEmpty())
				<div class="my-4 text-center justify-content-center">
					<img src="/images/sweat.png" width="80" class="mb-3">
					<p clas="mb-0 text-muted">This user doesn't have any friends. What a party-pooper.</p>
				</div>
			@else
				<div class="row">
					@foreach ($friends as $user)
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
		</x-card>
	</div>
</x-app-layout>
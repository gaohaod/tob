@section('title', 'New message')
<x-app-layout>
	<div class="container mt-5">

		@if (\Session::has('success'))
        <div class="alert alert-success mb-2 alert-dismissible fade show" role="alert">
            {!! \Session::get('success') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

		@if (\Session::has('error'))
        <div class="alert alert-danger mb-2 alert-dismissible fade show" role="alert">
            {!! \Session::get('error') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

		<x-card title="new message">
			<p class="text-muted">Compose a new message to somebody.</p>

			<form action="/app/messages/new" method="POST">
				@csrf
				<div class="form-group mb-3">
					<input class="form-control {{ $errors->has('to') ? 'is-invalid' : '' }}" placeholder="Username of who you're going to send it to" name="to">
					@error('to')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
				</div>

				<div class="form-group mb-3">
					<input class="form-control {{ $errors->has('subject') ? 'is-invalid' : '' }}" placeholder="Subject..." name="subject">
					@error('subject')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
				</div>

				<div class="form-group mb-3">
					<textarea class="form-control {{ $errors->has('content') ? 'is-invalid' : '' }}" placeholder="Today, I thought about waffles." name="content"></textarea>
					@error('content')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
				</div>

				<button class="btn btn-success btn-lg w-100" type="submit">send</button>
			</form>
		</x-card>
	</div>
</x-app-layout>
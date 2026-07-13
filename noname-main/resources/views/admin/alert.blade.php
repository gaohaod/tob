@section('title', 'Create an Alert')
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


		<x-card title="create an alert">
			<p class="text-muted"> Create a site-wide alert. </p>

			<form action="/app/admin/alert" method="POST">
				@csrf
				<div class="form-group mb-3">
				    <label for="color">Color</label>
				    <select class="form-control {{ $errors->has('color') ? 'is-invalid' : '' }}" name="color" id="color">
				      <option value="success">Green</option>
				      <option value="danger">Red</option>
				      <option value="warning">Yellow</option>
				      <option value="info">Blue</option>
				    </select>
				    @error('color')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
				</div>

				<div class="form-group mb-3">
				    <label for="content">Content</label>
				    <input type="text" class="form-control {{ $errors->has('content') ? 'is-invalid' : '' }}" id="content" name="content" aria-describedby="contentHelp">
				    <small id="contentHelp" name="content" class="form-text text-muted">If you're going to make a joke announcement, go ahead. You can also put HTML content in.</small>
					@error('content')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
				</div>

				<div class="form-group mb-0">
					<button class="btn btn-success w-100">create</button>
				</div>

			</form>
		</x-card>
	</div>
</x-app-layout>
<x-app-layout>

	<div class="container mt-5">

		        @if (\Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! \Session::get('message') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

		<x-card title="validate token">
			<form action="/app/admin/verify-token" method="GET">
				<input class="form-control" type="text" placeholder="Ticket here.." name="token">

				<button class="btn btn-success" type="submit">verify</button>
			</form>
		</x-card>
	</div>

</x-app-layout>
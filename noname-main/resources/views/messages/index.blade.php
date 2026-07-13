@section('title', 'Messages')
<x-app-layout>
	<div class="container mt-5">
		<div class="row">
			<div class="col-3">

				<button class="btn btn-success w-100 mb-2 text-left" onclick="document.location = '/app/messages/new'"><i class="far fa-plus mr-3"></i>new message</button>

				<div class="nav flex-column nav-pills text-left" id="v-pills-tab" role="tablist" id="myTab" aria-orientation="vertical">
                	<button class="nav-link active text-left" id="inbox-tab" data-toggle="tab" data-target="#inbox" type="button" role="tab" aria-controls="inbox" aria-selected="true"><i class="far fa-inbox mr-2"></i>Inbox</button>
                	<button class="nav-link text-left" id="sent-tab" data-toggle="tab" data-target="#sent" type="button" role="tab" aria-controls="sent"><i class="far fa-inbox-out mr-2"></i>Sent</button>
                	<button class="nav-link text-left" id="archive-tab" data-toggle="tab" data-target="#archive" type="button" role="tab" aria-controls="archive" aria-selected="false"><i class="far fa-archive mr-2"></i> Archive</button>
				</div>
			</div>

			<div class="col">
               <div class="tab-content" id="myTabContent">
                	<div class="tab-pane fade show active" id="inbox" role="tabpanel" aria-labelledby="inbox-tab">
                    	 @if ($messages->isEmpty())
                    	 	<div class="text-center">
                    	 		<img src="/images/confused.png" width="50" class="mb-3">

                    	 		<h5 class="text-muted font-weight-light">No messages here.</h5>
                    	 	</div>
                    	 @else

                    	 	@foreach ($messages as $message)
                    	 		<div class="card card-body p-2 mb-2 clickable" onclick="document.location = '/app/message/{{ $message->id }}'">
                    	 			<div class="row mt-1">
                    	 				<div class="col-auto text-center">
                    	 					<img src="/cdn/users/{{ $message->senderId }}?t={{ time() }}" width="50" class="pl-1">
                    	 				</div>

                    	 				<div class="col pl-2">
                    	 					<h5 class="font-weight-light mb-1">{{ Str::limit($message->subject, 90) }} 
                    	 					</h5>

                    	 					<h6 class="font-weight-light text-muted">sent by {{ $message->sender->name }}</h6>
                    	 				</div>
                    	 			</div>
                    	 		</div>
                    	 	@endforeach

                    	 	{{ $messages->links() }}

                    	 @endif
                	</div>

                	<div class="tab-pane fade" id="sent" role="tabpanel" aria-labelledby="sent-tab">
                    	 @if ($sent->isEmpty())
                    	 	<div class="text-center">
                    	 		<img src="/images/confused.png" width="50" class="mb-3">

                    	 		<h5 class="text-muted font-weight-light">No messages here.</h5>
                    	 	</div>
                    	 @else

                    	 	@foreach ($sent as $sent_message)
                    	 		<div class="card card-body p-2 mb-2 clickable" onclick="document.location = '/app/message/{{ $sent_message->id }}'">
                    	 			<div class="row mt-1">
                    	 				<div class="col-auto text-center">
                    	 					<img src="/cdn/users/{{ $sent_message->senderId }}?t={{ time() }}" width="50" class="pl-1">
                    	 				</div>

                    	 				<div class="col pl-2">
                    	 					<h5 class="font-weight-light mb-1">{{ Str::limit($sent_message->subject, 90) }} 
                    	 					</h5>

                    	 					<h6 class="font-weight-light text-muted">sent by {{ $sent_message->sender->name }}</h6>
                    	 				</div>
                    	 			</div>
                    	 		</div>
                    	 	@endforeach

                    	 	{{ $sent->links() }}

                    	 @endif
                	</div>

                	<div class="tab-pane fade" id="archive" role="tabpanel" aria-labelledby="archive-tab">
                    	 @if ($archive->isEmpty())
                    	 	<div class="text-center">
                    	 		<img src="/images/confused.png" width="50" class="mb-3">

                    	 		<h5 class="text-muted font-weight-light">No messages here.</h5>
                    	 	</div>
                    	 @else

                    	 	@foreach ($archive as $archived)
                    	 		<div class="card card-body p-2 mb-2 clickable" onclick="document.location = '/app/message/{{ $archived->id }}'">
                    	 			<div class="row mt-1">
                    	 				<div class="col-auto text-center">
                    	 					<img src="/cdn/users/{{ $archived->senderId }}?t={{ time() }}" width="50" class="pl-1">
                    	 				</div>

                    	 				<div class="col pl-2">
                    	 					<h5 class="font-weight-light mb-1">{{ Str::limit($archived->subject, 90) }} 
                    	 					</h5>

                    	 					<h6 class="font-weight-light text-muted">sent by {{ $archived->sender->name }}</h6>
                    	 				</div>
                    	 			</div>
                    	 		</div>
                    	 	@endforeach

                    	 	{{ $archive->links() }}

                    	 @endif
                	</div>

				</div>
			</div>
		</div>
	</div>
</x-app-layout>
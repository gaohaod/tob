@section('title', $game->name)
<div class="modal fade" id="startGameModal" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body text-center">
            <p class="text-muted font-weight-regular" id="modal-status-text">
               Requesting a server...
            </p>
            <div class="my-4">
               <x-loader />
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-lg btn-secondary w-100" data-dismiss="modal"><i class="far fa-times mr-2"></i>The client started, close this modal.</button>
         </div>
      </div>
   </div>
</div>
<x-app-layout>
   <div class="container mt-5">
      <x-card :title="'View game'">
         <div class="row mb-4">
            <div class="col pr-2">
               <div class="position-relative">
                  <span class="badge badge-danger position-absolute {{ $game->id == 86 ? 'mt-3' : 'mt-2' }} ml-2" style="z-index: 3;">{{ $game->year }}</span>
                  <div id="carouselGameThumbnails" class="carousel slide" data-ride="carousel">
                     <div class="carousel-inner">
                            @if ($game->under_review == 1)
                                <img src="/images/place_pending_lg.png" alt="Game Thumbnail" width="100%" height="400" class="place-thumbnail lazy-load border rounded-sm">
                            @else
                            <img src="/images/placeload.png" data-src="/cdn/{{ $game->id }}?t={{ time() }}" alt="Game Thumbnail" width="100%" height="400" class="place-thumbnail lazy-load border rounded-sm">
                            @endif
                     </div>
                     <button class="carousel-control-prev" type="button" data-target="#carouselGameThumbnails" data-slide="prev">
                     <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                     <span class="sr-only">Previous</span>
                     </button>
                     <button class="carousel-control-next" type="button" data-target="#carouselGameThumbnails" data-slide="next">
                     <span class="carousel-control-next-icon" aria-hidden="true"></span>
                     <span class="sr-only">Next</span>
                     </button>
                  </div>
               </div>
            </div>
            <div class="col-4">
               <h4 class="font-weight-bolder">{{ $game->name }}</h4>
               <h6 class="font-weight-regular text-muted mb-3">by <a href="/app/user/{{ $game->user->id }}">{{ $game->user->name }} {!! $game->user->verified_via_discord ? "<span class='ml-1 text-success'><i class='far fa-check-circle'></i></span>" : "" !!}</a></h6>
               <div class="position-relative">
                  <button class="btn btn-success py-2 w-100" data-toggle="modal" data-target="#startGameModal" onclick="startGame({{ $game->id }})"><i class="far fa-play mr-2"></i>play</button>
               </div>
            </div>
         </div>
         <ul class="nav nav-tabs" id="gameView" role="tablist">
            <li class="nav-item" role="presentation">
               <button class="nav-link active" id="description-tab" data-toggle="tab" data-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
            </li>
            <li class="nav-item" role="presentation">
               <button class="nav-link" id="stats-tab" data-toggle="tab" data-target="#stats" type="button" role="tab" aria-controls="stats" aria-selected="false">Statistics</button>
            </li>
            <li class="nav-item" role="presentation">
               <button class="nav-link" id="badges-tab" data-toggle="tab" data-target="#badges" type="button" role="tab" aria-controls="badges" aria-selected="false">Badges</button>
            </li>
         </ul>
         <div class="tab-content" id="gameViewTab">
            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
               <p class="font-weight-regular text-muted mb-0 mt-3" id="description-parse">
                  {!! nl2br(e($game->description)) !!}
               </p>
            </div>
            <div class="tab-pane fade" id="stats" role="tabpanel" aria-labelledby="stats-tab">
               <div class="row my-4 text-center">
                  <div class="col">
                     <h5 class="font-weight-medium">Visits</h5>
                     <h6 class="mb-0 font-weight-regular text-muted">{{ $game->visits }}</h6>
                  </div>
                  <div class="col">
                     <h5 class="font-weight-medium">Max Players</h5>
                     <h6 class="mb-0 font-weight-regular text-muted">{{ $game->max_players }}</h6>
                  </div>
                  <div class="col">
                     <h5 class="font-weight-medium">Created</h5>
                     <h6 class="mb-0 font-weight-regular text-muted">{{ $game->created_at }}</h6>
                  </div>
                  <div class="col">
                     <h5 class="font-weight-medium">Updated</h5>
                     <h6 class="mb-0 font-weight-regular text-muted">{{ $game->updated_at }}</h6>
                  </div>
               </div>
            </div>

            <div class="tab-pane fade" id="badges" role="tabpanel" aria-labelledby="badges-tab">
               <p class="font-weight-regular text-center text-muted mb-0 mt-3" id="description-parse">
                  This place has no badges.
               </p>
            </div>

         </div>
      </x-card>
      <div class="mt-3">
    <h4 class="font-weight-light mb-3">Servers</h4>
    @if (!empty($serversWithPlayers))
        @foreach ($serversWithPlayers as $data)
            <div class="card card-body mb-2" data-server-id="{{ $data['server']->id }}">
                <h5 class="font-weight-regular mb-1">Server <small class="ml-2"><code>{{ $data['server']->jobId }}</code></small></h5>
                <h6 class="font-weight-light {{ $data['serverPlayers']->isEmpty() ? 'mb-0' : 'mb-4' }}">
                    {{ $data['serverPlayers']->count() }} online out of {{ $game->max_players }} players
                    @if ($data['serverPlayers']->isEmpty())
                        (No players currently online)
                    @endif
                </h6>
                <div class="row">
                    @if (!$data['serverPlayers']->isEmpty())
                        @foreach ($data['serverPlayers'] as $serverPlayer)
                            <x-server-list-user :id="$serverPlayer->userId" />
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <p class="text-center mb-0 fw-light">This game has no running servers. Click <i>Play</i> to start a new one.</p>
    @endif
</div>


<div class="mt-4">

<h4 class="font-weight-light mb-3">Comments</h4>

<div id="disqus_thread"></div>
<script>
   var disqus_developer = 1;
    /**
    *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
    *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables    */
    
    var disqus_config = function () {
    this.page.url = 'n0name.xyz';  // Replace PAGE_URL with your page's canonical URL variable
    this.page.identifier = '{{ $game->name . '-' . $game->id }}'; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
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


   </div>
   <script src="/functions.js"></script>
   <script id="dsq-count-scr" src="//n0name-xyz.disqus.com/count.js" async></script>
   <script>
      let statusText = document.getElementById('modal-status-text');
      
      async function startGame(id) {
          try {
            // remove tokens to save a trip to settings :P
            await fetch('/app/tickets/remove-game-tickets')  

            const response = await fetch(`/app/tickets/generate-game-ticket/${id}`);
            const result = await response.json();
      
              if (!response.ok) {
                  statusText.innerHTML = "Couldn't start client, please try again later.";
                  return;
              }
              
              statusText.innerHTML = result.message;
      
              if (result.success === true) {
                  document.location = "noname://" + result.token;
                  await sleep(3000);
                  location.reload();
              }
          } catch (error) {
              statusText.innerHTML = "Couldn't start client, please try again later.";
          }
      }
      
      
              parseDescription(); // markdown sux 
          
   </script>

   <script src="/functions.js"></script>
</x-app-layout>

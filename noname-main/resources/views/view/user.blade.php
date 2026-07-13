@section('title', $user->name)



<div class="modal fade" id="wearingModal" tabindex="-1" aria-labelledby="wearingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @if ($owned->isEmpty())
                        <p class="mb-0 text-muted text-center">this user is naked</p>
                     @else
                     <div class="row">
                        @foreach ($owned as $wear)
                        <div class="col-3 mb-2 px-2">
                           <div class="card card-body p-3 text-center">
                              @if ($wear->asset->type == "face" || $wear->asset->type == "tshirt")
                                 <img src="/asset/?id={{ $wear->itemId - 1 }}" width="auto" height="auto" class="mb-3">
                              @else
                                 <img src="/cdn/{{ $wear->itemId }}" width="auto" height="auto" class="mb-3">
                              @endif
                              <p class="mb-0 text-truncate">{{ $wear->asset->name }}</p>
                              <p class="text-muted mb-0">{{ $wear->asset->peeps }} Peeps</p>
                           </div>
                        </div>
                        @endforeach
                     </div>
         @endif
      </div>
    </div>
  </div>
</div>


<x-app-layout>
   <div class="container mt-5">
      @if ($user->banned == 0)
      <x-card :title="'profile'" class="mb-3">
         <div class="container">
            <div class="row">
               <div class="col-auto">
                  <img src="/cdn/users/{{ $user->id }}?t={{ time() }}" alt="{{ $user->name }}" width="200px" height="200px">
               </div>
               <div class="col">
                  <h4 class="fw-bold">{{ $user->name }}  {!! $user->verified_via_discord ? "<span class='ml-2 text-success'><i class='far fa-check-circle'></i></span>" : "" !!} </h4>
                  <h6 class="fw-medium mb-3">

                     <div class="{{ $user->isActive() ? 'text-success' : 'text-muted' }} mb-2">@if (!$user->in_game) {{ $user->isActive() ? '[ Online ] ' : '[ ' . $user->last_seen->diffForHumans() . ' ]' }} @else <span class="text-info">[ In-Game ]</span> @endif</div>
                  </h6>
                  <small class="fw-light mb-1">Bio:</small>
                  <p class="text-muted fw-regular mb-2" id="user-description">
                     {!! nl2br(e($user->description)) !!}
                  </p>
                  <p class="fw-regular mb-2">
                     <span class="text-muted">Wipeouts:</span> {{ $user->wipeouts }}<br>
                     <span class="text-muted">Knockouts:</span> {{ $user->knockouts }}<br>
                     Joined {{ config('app.name', 'Laravel') }} {{ $user->created_at->diffForHumans() }}.
                     @if ($user->banned == 1)
                     <br><span class="text-danger">This user has been banned. Reason: {{ $user->ban_reason }}</span>
                     @endif
                     @if ($user->admin ==1) 
                     <br><span class="text-danger"><i class="far fa-gavel mr-2"></i> This user is an administrator.</span>
                     @endif
                  </p>
               </div>

               <div class="col text-right pr-0">
                  @if ($status['status'] == 0)
                      <button class="btn btn-success mb-1" onclick="document.location = '/app/user/add/{{ $user->id }}'">
                          <i class="far fa-user-plus mr-2"></i>Add Friend
                      </button><br>
                  @elseif ($status['status'] == 1)
                      @if ($status['receiverId'] == Auth::id())
                          <button class="mb-1 mr-1 btn btn-success" onclick="document.location = '/app/friend/accept/{{ $user->id }}'">
                              <i class="far fa-user-plus mr-2"></i>Accept
                          </button>
                          <button class="mb-1 btn btn-success" onclick="document.location = '/app/friend/decline/{{ $user->id }}'">
                              <i class="far fa-times mr-2"></i>Decline
                          </button><br>
                      @else
                          <button class="mb-1 btn btn-success" onclick="document.location = '/app/friend/decline/{{ $user->id }}'">
                              <i class="far fa-times mr-2"></i>Cancel
                          </button><br>
                      @endif
                  @elseif ($status['status'] == 2)
                      <button class="mb-1 btn btn-success" onclick="document.location = '/app/friend/decline/{{ $user->id }}'">
                          <i class="far fa-user-minus mr-2"></i>Unfriend
                      </button><br>
                  @endif
                  <button class="btn btn-success" data-toggle="modal" data-target="#wearingModal"><i class="far fa-tshirt mr-2"></i>View wearing</button>
               </div>
            </div>
         </div>
      </x-card>
      <div class="mt-4">
         <div class="row">
            <div class="col-md-6">
               <x-card :hasall="'yep'" :alltext="'see all &nbsp; 🢒'" :alllink="'/app/user/' . $user->id . '/friends'" :title="'friends'">
                  @if ($friends->isEmpty())
                  <p class="text-muted text-center mb-0">This user doesn't have any friends. What a party-pooper.</p>
                  @else
                  <div class="row">
                     @foreach ($friends as $friend)
                     <div class="col-4">
                        <div class="card p-2 card-body text-center clickable" onclick="document.location = '/app/user/{{ $friend->id }}'">
                           <img src="/cdn/users/{{ $friend->id }}" width="auto" class="mb-2 mt-2">
                           <p class="text-muted font-weight-regular mb-2 text-truncate">{{ $friend->name }}</p>
                        </div>
                     </div>
                     @endforeach
                  </div>
                  @endif
               </x-card>
            </div>
            <div class="col">
               <x-card :title="'places'">
                  @if ($assets->isEmpty())
                  <p class="text-muted text-center mb-0">This user has no places.</p>
                  @else
                  <div class="accordion" id="userplaces">
                  @foreach ($assets as $asset)

                     <div class="card">
                        <div class="card-header" id="place{{ $asset->id }}">
                           <h2 class="mb-0">
                              <button class="btn btn-link text-success btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{ $asset->id }}" aria-expanded="true" aria-controls="collapse{{ $asset->id }}">
                              {{ $asset->name }}
                              </button>
                           </h2>
                        </div>
                        <div id="collapse{{ $asset->id }}" class="collapse" aria-labelledby="place{{ $asset->id }}" data-parent="#userplaces">
                           <div class="card-body">
                              <img src="/cdn/{{ $asset->id }}?t={{ time() }}" alt="Place Thumbnail" width="100%" height="auto" class="rounded border mb-2">

                              <button class="btn btn-success w-100" onclick="document.location = '/app/place/{{ $asset->id }}'">view place</button>
                           </div>
                        </div>
                     </div>

                  @endforeach
                  </div>
                  @endif
               </x-card>

            </div>
         </div>


               <div class="my-2">&nbsp;</div>



               <x-card title="inventory">
                   <div class="row">
                       <div class="col-3">
                           <div class="nav flex-column nav-pills text-left" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                               @foreach (['hats', 'shirts', 'pants', 'tshirts', 'heads', 'gears', 'faces'] as $category)
                                   <button class="nav-link @if ($loop->first) active @endif text-left" 
                                           id="{{ $category }}-tab" 
                                           data-toggle="tab" 
                                           data-target="#{{ $category }}" 
                                           type="button" 
                                           role="tab" 
                                           aria-controls="{{ $category }}" 
                                           aria-selected="true">
                                       {{ ucfirst($category) }}
                                   </button>
                               @endforeach
                           </div>
                       </div>

                       <div class="col">
                           <div class="tab-content" id="myTabContent">
                               @foreach (['hats', 'shirts', 'pants', 'tshirts', 'heads', 'gears', 'faces'] as $category)
                                   <div class="tab-pane fade @if ($loop->first) show active @endif" 
                                        id="{{ $category }}" 
                                        role="tabpanel" 
                                        aria-labelledby="{{ $category }}-tab">
                                       @if ($item[$category]->isEmpty())
                                           <p class="mb-0 text-muted text-center">This user doesn't own anything of this category.</p>
                                       @else
                                           <div class="row">
                                               @foreach ($item[$category] as $asset)
                                                   <div class="col-2 mb-2 px-2">
                                                       <div class="card card-body p-3 text-center">
                                                           @if ($asset->asset->type == "face" || $asset->asset->type == "tshirt")
                                                               <img src="/asset/?id={{ $asset->itemId - 1 }}" 
                                                                    width="100%" 
                                                                    height="auto" 
                                                                    class="mb-3">
                                                           @else
                                                               <img src="/cdn/{{ $asset->itemId }}" 
                                                                    width="100%" 
                                                                    height="100%" 
                                                                    class="mb-3">
                                                           @endif
                                                           <p class="mb-0 text-truncate">{{ $asset->asset->name }}</p>
                                                           <p class="text-muted mb-0">{{ $asset->asset->peeps }} Peeps</p>
                                                       </div>
                                                   </div>
                                               @endforeach
                                           </div>
                                      <div class="d-flex justify-content-center mt-3">
                                          {{ $item[$category]->links() }}
                                      </div>
                                       @endif
                                   </div>
                               @endforeach
                           </div>
                       </div>
                   </div>
               </x-card>


      </div>
      @else

      <div class="bg-danger p-2 font-weight-bold mb-5 text-white">This user is no longer available. Reason: <i class="font-weight-regular">{{ $user->ban_reason }}</i></div>
      <div class="d-flex justify-content-center">
  <div class="spinner-border" role="status">
    <span class="sr-only">Loading...</span>
  </div>
</div>

@endif
   </div>

   <script src="/functions.js"></script>
   <script>
      function replacePeeps() {
          let userHasAlternativePeeps = {{ $user->using_alternative_peeps ? 'true' : 'false' }};
          let userPeeps = {{ $user->peeps }};
          let peepsImg = userHasAlternativePeeps 
              ? '<img src="/images/peeps_alternative.png" alt="Peep icon" width="16" class="mx-1">[' + userPeeps + ']' 
              : '<img src="/images/peeps.png" alt="Peep icon" width="16" class="mx-1">[' + userPeeps + ']';
      
          let userDescriptionElement = document.getElementById('user-description');
          let description = userDescriptionElement.innerHTML;
          userDescriptionElement.innerHTML = description.replace('{myPeeps}', peepsImg);
      }
      
      document.addEventListener('DOMContentLoaded', replacePeeps);
   </script>
</x-app-layout>

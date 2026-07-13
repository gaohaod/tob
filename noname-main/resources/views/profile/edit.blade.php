@section('title', 'Settings')

<div id="modal-container">
<div class="modal fade" id="buyItemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <p class="text-muted fw-regular" id="modal-message">
            Do you want to buy <strong>"1 place slot"</strong> for 250 <x-peep-icon :spacing="true" :size="16" />?
        </p>

        <img src="/images/twisted.png" alt="Item Thumbnail" width="200" height="200" class="place-thumbnail border rounded-sm my-3">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="refresh()">Close</button>
        <button type="button" class="btn btn-success" id="modal-buy-button" onclick="buySlot()">Buy slot</button>
      </div>
    </div>
  </div>
</div>
</div>

<x-app-layout>
   <div class="container mt-5">

              @if (\Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            {!! \Session::get('message') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

      <x-card :title="'Settings'">
         <div class="row">
            <div class="col-md-3">
            <div class="nav flex-column nav-pills text-left" id="v-pills-tab" role="tablist" id="myTab" aria-orientation="vertical">
                <button class="nav-link active text-left" id="profile-tab" href="#profile" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true"><i class="far fa-user mr-2"></i>Profile</button>
                <button class="nav-link text-left" id="discord-tab" href="#discord" data-toggle="tab" data-target="#discord" type="button" role="tab" aria-controls="discord"><i class="far fa-key mr-2"></i>Token managment</button>
                <button class="nav-link text-left" id="settings-tab" href="#settings" data-toggle="tab" data-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false"><i class="far fa-cog mr-2"></i> Settings</button>
</div>
            </div>
            <div class="col">
               <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                     @include('profile.partials.update-password-form')
                     @include('profile.partials.update-bio')        
                     <div class="mt-3">
                        <form action="/app/change-peeps" method="POST">
                        @csrf
                        <div class="form-group">
                           <label for="peep">select the way peeps display</label>
                           <select name="peep" id="peep" class="form-control @error('peep') is-invalid @enderror @if(session('success2')) is-valid @endif">
                               <option value="">Choose...</option>
                               <option value="alt" {{ old('peep') === 'alt' ? 'selected' : '' }}>Alternative Peeps (Diu-like)</option>
                               <option value="def" {{ old('peep') === 'def' ? 'selected' : '' }}>Default Peeps</option>
                           </select>
                           @error('peep')
                               <div class="invalid-feedback">
                                   {{ $message }}
                               </div>
                           @enderror
                           @if (session('success'))
                               <div class="valid-feedback">
                                   {{ session('success2') }}
                               </div>
                           @endif
                       </div>
                       <button class="btn btn-success">Update Peeps</button>
                     </form>
                     </div>
                  </div>

                  <div class="tab-pane fade" id="discord" role="tabpanel" arial-labelledby="discord-tab">
                        <h4 class="font-weight-light">token manager</h4>
                        <h6 class="font-weight-regular text-muted"> {{ Auth::user()->verified_via_discord ? "You are verified and you should have the Member role in the Discord server." : "You still haven't verified your account. Click tbe button below and copy your token." }} </h6>
                        @if (Auth::user()->verified_via_discord) 
                        <h6 class="font-weight-regular text-muted"> Token used to verify: <kbd>{{ Auth::user()->discord_token }}</kbd> </h6>
                        @endif

                        @if (!Auth::user()->verified_via_discord)
                        <button class="btn btn-success mt-2" onclick="document.location = '/app/generate-token'">generate token</button>
                        @endif
                  </div>

                  <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                     <div class="mb-0">
                        <h4 class="font-weight-light">get more place slots</h4>
                        <h6 class="font-weight-regular text-muted"> You have {{ Auth::user()->place_slots_left }} place slots left. </h6>
                        <h6 class="font-weight-regular text-muted mb-3">1 place slot costs <x-peep-icon :size="'16'" :spacing="true" /> 250 </h6>

                        <button class="btn btn-success" data-toggle="modal" data-target="#buyItemModal">buy 1 place slot</button>
                     </div>
                  </div>

               </div>
            </div>
         </div>
      </x-card>
   </div>

   <script src="/functions.js"></script>
</x-app-layout>

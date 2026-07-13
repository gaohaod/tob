@section('title', $item->name)
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
            Do you want to buy <strong>"{{ $item->name }}"</strong> for {{ $item->peeps }} <img src="/images/{{ Auth::user()->using_alternative_peeps ? 'peeps_alternative.png' : 'peeps.png' }}" alt="Peep icon" width="24" class="mx-1">?
            @if ($item->for2012 == 0)
            <br>
            <p class="text-danger">Please note that this item won't appear in the 2012 client. It has been excluded due to causing issues with the game.</p>
            @endif
        </p>

        @if ($item->type == "tshirt" || $item->type == "face")
        <img src="/images/load.gif" data-src="/asset/?id={{ $item->id - 1 }}&t={{ time() }}" alt="Item Thumbnail" width="200" height="200" class="place-thumbnail border rounded-sm my-3 lazy-load">
        @else
        <img src="/images/load.gif" data-src="/cdn/{{ $item->id }}?t={{ time() }}" alt="Item Thumbnail" width="200" height="200" class="place-thumbnail border rounded-sm my-3 lazy-load">
        @endif

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="refresh()">Close</button>
        <button type="button" class="btn btn-success" id="modal-buy-button" onclick="buyItem({{ $item->id }})">Buy item</button>
        <button type="button" class="btn btn-success hidden" id="modal-customize-button" onclick="document.location = '/app/avatar'">Customize your avatar</button>
      </div>
    </div>
  </div>
</div>
</div>

<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'View item'">
            <div class="row mb-4">
                <div class="col pr-2 d-flex justify-content-center align-items-center">
                @if ($item->type == "tshirt" || $item->type == "face")
        <img class="lazy-load place-thumbnail border rounded-sm my-5" src="/images/load.gif" data-src="/asset/?id={{ $item->id - 1 }}&t={{ time() }}" alt="Item Thumbnail" width="200" height="200">
        @else
        <img class="lazy-load place-thumbnail border rounded-sm my-5" src="/images/load.gif" data-src="/cdn/{{ $item->id }}?t={{ time() }}" alt="Item Thumbnail" width="200" height="200">
        @endif
                </div>
                <div class="col-4">
                    <h4 class="fw-bolder">{{ $item->name }}</h4>
                    <h6 class="fw-regular text-muted mb-2">by <a href="/app/user/{{ $item->user->id }}">{{ $item->user->name }} {!! $item->user->verified_via_discord ? "<span class='ml-1 text-success'><i class='far fa-check-circle'></i></span>" : "" !!}</a></h6>

<h6 class="fw-regular text-muted mb-2">
    @if ($item->peeps <= 0)
        Free
    @elseif ($item->off_sale == 1)
        Off-Sale
    @else
      <div class="d-inline-block">
        <div class="align-middle">
        {{ $item->peeps }} <img src="/images/{{ Auth::user()->using_alternative_peeps ? 'peeps_alternative.png' : 'peeps.png' }}" alt="Peep icon" class="ml-1" width="24">
        </div>
      </div>
    @endif
</h6>

                    @if ($item->peeps > 499)
                    <span class="badge bg-success text-light mb-2">Limited</span>
                    @elseif ($item->peeps > 1000)
                    <span class="badge limited-plus mb-2">Limited+</span>
                    @elseif ($item->for2012 == 0)
                    <span class="badge bg-danger text-light mb-2">2017 Only</span>
                    @endif

                    @if ($item->special == 1)
                    <span class="badge header-purple mb-2 text-white">Special</span>
                    @endif

                    <p class="text-muted mb-2"><b>Sales</b>: {{ $sales }}</p>

                    @if ($owned)
                    <p class="text-muted mb-0">You already own this.</p>
                    @else
                    <button class="btn btn-success w-100" data-toggle="modal" data-target="#buyItemModal">buy</button>
                    @endif
                </div>
            </div>

            <h5 class="fw-medium mt-4">Description</h5>
            <p class="fw-regular text-muted mb-0">
            {!! nl2br(e($item->description)) !!}
            </p>

        </x-card>

<div class="mt-4">

<div id="disqus_thread"></div>
<script>
   var disqus_developer = 1;
    /**
    *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
    *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables    */
    
    var disqus_config = function () {
    this.page.url = 'n0name.xyz';  // Replace PAGE_URL with your page's canonical URL variable
    this.page.identifier = '{{ $item->name . '-' . $item->id }}'; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
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
</x-app-layout>
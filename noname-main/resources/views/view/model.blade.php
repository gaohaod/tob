@section('title', $item->name)

<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'View model'">
            <div class="row mb-4">
                <div class="col pr-2 d-flex justify-content-center align-items-center">
                    @if ($item->type == "audio")
                        <img src="/images/audio.png" alt="Item Thumbnail" width="200" height="200" class="place-thumbnail border rounded-sm my-5">
                    @elseif ($item->type == "decal")
                        <img src="/asset/?id={{ $item->id - 1 }}&t={{ time() }}" alt="Item Thumbnail" width="200" height="200" class="place-thumbnail border rounded-sm my-5">
                    @else
                        <img src="/cdn/{{ $item->id }}?t={{ time() }}" alt="Item Thumbnail" width="200" height="200" class="place-thumbnail border rounded-sm my-5">
                    @endif
                </div>
                <div class="col-4">
                    <h4 class="fw-bolder">{{ $item->name }}</h4>

<h6 class="fw-regular text-muted mb-4">
    by <a href="/app/user/{{ $item->user->id }}">{{ $item->user->name }} {!! $item->user->verified_via_discord ? "<span class='ml-1 text-success'><i class='far fa-check-circle'></i></span>" : "" !!}</a>
</h6>

    <button class="btn btn-lg btn-success w-100" onclick="copyToClipboard({{ $item->id }})"><i class="far fa-copy mr-2"></i>copy asset link</button>
    <p class="text-center text-muted mt-2 mb-2 mb-0"><small>You can also insert it from the Toolbox in Studio.</small></p>

    @if ($item->type == "audio")

    <audio id="player" class="mt-3 w-100" controls>
        <source src="/asset/?id={{ $item->id - 1 }}&t={{ time() }}">
    </audio>

    @endif

                </div>
            </div>

            <h5 class="fw-medium mt-4">Description</h5>
            <p class="fw-regular text-muted mb-0">
            {!! nl2br(e($item->description)) !!}
            </p>

        </x-card>
    </div>


<script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
    <script>
        const player = new Plyr('#player');
    </script>
    <script>

        /*
        This is a workaround because i can't test anything properly.
        */
function copyToClipboard(itemId) {
    const assetLink = `http://www.noname.xyz/asset/?id=${itemId}`;

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(assetLink).then(function() {
            alert('Copied to the clipboard.');
        }).catch(function(error) {
            alert('Failed to copy: ', error);
        });
    } else {
        const textArea = document.createElement('textarea');
        textArea.value = assetLink;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            alert('Copied to the clipboard.');
        } catch (err) {
            alert('Failed to copy');
        }
        document.body.removeChild(textArea);
    }
}
</script>
</x-app-layout>
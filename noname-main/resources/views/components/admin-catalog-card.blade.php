@props(['title', 'under_review', 'status', 'creator', 'visits', 'year', 'id', 'thumbnail', 'price', 'offsale', 'type', 'imgsize', 'fromadmin'])

@php
$fromadmin = $fromadmin ?? "pp"; 
$types = ['model', 'audio', 'decal'];

if ($type == "shirt" && $type == "pants") {
    $id = $id - 1;
}
@endphp

@if ($fromadmin == "true")
<div class="card place-card clickable" >
@else
<div class="card place-card clickable">
@endif
    
    @if ($type == "audio") 
    <div class="mb-2 mt-0">
    <audio id="player" class="mt-3 w-100" controls>
        <source src="/asset/?id={{ $id - 1 }}&t={{ time() }}">
    </audio>
    </div>

    @endif

    <div class="position-relative">
    @if ($price > 499)
    <img src="/images/limited.png" alt="Limited" class="position-absolute bottom-1 fixed-bottom" width="50">
    @endif
    @if ($type == "audio")
    <img src="/images/audio.png" alt="Item Thumbnail" class="card-img place-thumbnail {{ $fromadmin == 'false' ? 'ugc' : '' }} border-bottom" height="{{ $imgsize ?? 120 }}" width="100%">
    @elseif ($type == "decal" || $type == "tshirt")
    <img src="/asset/?id={{ $id - 1 }}" alt="Item Thumbnail" class="card-img place-thumbnail border-bottom" height="{{ $imgsize ?? 120 }}" width="100%">
    @elseif ($type == "pants")
    <img src="/cdn/{{ $id - 1 }}?t={{ time() }}" alt="Item Thumbnail" class="card-img place-thumbnail border-bottom" height="{{ $imgsize ?? 120 }}" width="100%">
    @elseif ($type == "shirt")
    <img src="/cdn/{{ $id }}?t={{ time() }}" alt="Item Thumbnail" class="card-img place-thumbnail border-bottom" height="{{ $imgsize ?? 120 }}" width="100%">
    @else
    <img src="/cdn/{{ $id - 1 }}?t={{ time() }}" alt="Item Thumbnail" class="card-img place-thumbnail border-bottom" height="{{ $imgsize ?? 120 }}" width="100%">
    @endif
</div>
    <div class="p-3">
        <h6 class="fw-bold {{ in_array($type, $types) ? 'mb-0' : 'mb-1' }} text-truncate" title="{{ $title }}">{{ $title }}</h6>
        @if (!in_array($type, $types)) 
        <h6 class="fw-regular text-muted mb-2">
            @if ($price == 0)
                Free
            @elseif ($offsale == 1)
                Off-Sale
            @else
                {{ $status }}
            @endif
        @endif
        </h6>


        <button class="btn btn-success w-100 btn-sm mb-1" onclick="document.location = '/app/admin/approve-asset/{{ $id }}'">approve</button>
        <button class="btn btn-warning w-100 btn-sm mb-1" onclick="document.location = '/app/admin/decline-asset/{{ $id }}'">decline</button>
        <button class="btn btn-danger w-100 btn-sm" onclick="document.location = '/app/admin/decline-asset-banuser/{{ $id }}'">decline & ban creator</button>

    </div>
</div>

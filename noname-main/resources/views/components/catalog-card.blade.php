@props(['title', 'under_review', 'status', 'creator', 'visits', 'year', 'id', 'thumbnail', 'price', 'offsale', 'type', 'imgsize', 'fromadmin', 'special', 'creatorId', 'creatorName', 'fortwelve'])

@php
$fromadmin = $fromadmin ?? "pp"; 
$types = ['model', 'audio', 'decal', 'mesh'];
$creatorId = $creatorId ?? -69;
$creatorName = $creatorName ?? "Notdefinedontshow";
@endphp

@if ($fromadmin == "true")
<div class="card place-card clickable" onclick="document.location = '/app/admin/view/{{ $id }}'">
@else
<div class="card place-card clickable" onclick="document.location = '/app/{{ in_array($type, $types) ? 'model' : 'item' }}/{{ $id }}'">
@endif
    <div class="position-relative">
        @if ($special == 1)
            <div class="badge ml-1 position-absolute mt-1 header-purple">Special</div>
        @elseif ($fortwelve == 0)
            <div style="right: -1%" class="badge mr-1 position-absolute mt-1 badge-danger text-light">2017</div>
        @endif

    @if ($type == "audio")
    <img src="/images/audio.png" alt="Item Thumbnail" class="card-img place-thumbnail {{ $fromadmin == 'false' ? 'ugc' : '' }} border-bottom" height="{{ $imgsize ?? 120 }}" width="100%">
    @elseif ($type == "decal" || $type == "tshirt" || $type == "face")
    <img class="lazy-load" src="/images/load.gif" data-src="/asset/?id={{ $id - 1 }}" alt="Item Thumbnail" class="card-img place-thumbnail border-bottom" height="{{ $imgsize ?? 120 }}" width="100%">
    @elseif ($fromadmin == "true" && $type == "shirt" && $type == "pants")
    <img class="lazy-load" src="/images/load.gif" data-src="/asset/?id={{ $id - 1 }}" alt="Item Thumbnail" class="card-img place-thumbnail border-bottom" height="{{ $imgsize ?? 120 }}" width="100%">
    @else
    <img class="lazy-load" src="/images/load.gif" data-src="/cdn/{{ $id }}" alt="Item Thumbnail" class="card-img place-thumbnail border-bottom" height="{{ $imgsize ?? 120 }}" width="100%">
    @endif
</div>
    <div class="p-3 text-center">
        <p class="fw-bold align-middle {{ in_array($type, $types) ? 'mb-0' : 'mb-0' }} text-truncate" title="{{ $title }}">{{ $title }}</p>
         @if (!in_array($type, $types)) 
        <p class="fw-regular text-muted mb-0">
            @if ($price == 0)
                Free
            @elseif ($offsale == 1)
                Off-Sale
            @else
                {{ $status }} <x-peep-icon size="16" :spacing="true" />
            @endif
        @endif
        </p>

    </div>
</div>

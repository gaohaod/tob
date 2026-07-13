@props(['title', 'creator', 'id'])

<div class="card place-card clickable" onclick="document.location = '/app/videos/{{ $id }}'">
        <video src="/cdn/videos/{{ $id }}" muted playsinline class="card-img place-thumbnail border-bottom lazy-load" style="height: 150px; width: 100%; object-fit: cover;" onloadeddata="this.currentTime = 0; this.pause();"></video>
    <div class="card-body p-3">
        <h5 class="fw-bold mb-1 text-truncate">{{ $title }}</h5>
        <h6 class="fw-regular text-muted mb-0">by {{ $creator }}</h6>
    </div>
</div>

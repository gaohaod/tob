@props(['spacing', 'size'])

<img src="/images/{{ Auth::user()->using_alternative_peeps ? 'peeps_alternative.png' : 'peeps.png' }}" 
     alt="Peep icon" 
     width="{{ $size }}" 
     class="align-middle @if($spacing) mx-1 @endif">

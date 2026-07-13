@php
    $haspadding = $haspadding ?? 'yep';
    $hasall = $hasall ?? 'nope';
    $alllink = $alllink ?? '/';
    $alltext = $alltext ?? 'not defined';
    $nocaps = $nocaps ?? 'nope';
@endphp

<div class="card">
    @if ($hasall == "yep" && $alllink !== "/" && $alltext !== "not defined")
    <div class="card-header {{ $bg ?? 'bg-success' }} text-white @if ($nocaps == "nope") text-lowercase @endif">{{ $title }} <a href="{!! $alllink !!}" title="{!! $alltext !!}" class="ml-3 text-white special-link">{!! $alltext !!}</a></div>
    @else
    <div class="card-header {{ $bg ?? 'bg-success' }} text-white @if ($nocaps == "nope") text-lowercase @endif">{{ $title }}</div>
    @endif

    @if ($haspadding === 'yep')
        <div class="card-body">
            {{ $slot }}
        </div>
    @else
        <div class="card-body p-0">
            {{ $slot }}
        </div>
    @endif
</div>

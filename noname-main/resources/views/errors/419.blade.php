@section('title', 'Error')
@guest
<x-guest-layout>
    <div class="container mt-5 text-center">
        <x-card :title="'error'">
 
            <img src="/images/confused.png" alt="Confused blob emoji" width="100" class="mb-4">

            <h3 class="fw-bolder">Page Expired</h3>
            <h6 class="fw-regular text-muted mb-0">If you're here after submitting a form, this is a bug. Report this to ectoBiologist on Discord.</h6>

        </x-card>
    </div>
</x-guest-layout>
@endguest

@auth
<x-app-layout>
    <div class="container mt-5 text-center">
        <x-card :title="'error'">
 
            <img src="/images/confused.png" alt="Confused blob emoji" width="100" class="mb-4">
            
            <h3 class="fw-bolder">Page Expired</h3>
            <h6 class="fw-regular text-muted mb-0">If you're here after submitting a form, this is a bug. Report this to ectoBiologist on Discord.</h6>
        </x-card>
    </div>
</x-app-layout>
@endauth
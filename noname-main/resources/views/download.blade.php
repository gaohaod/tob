@section('title', 'Download')
<x-app-layout>
<div class="container mt-5">
    <x-card :title="'download'">
        <div class="text-center my-5">

            <img src="/images/logo.png" width="100" class="mb-3">

            <h2 class="font-weight-light">Download NONAME</h2>
            <h5 class="text-muted mb-5">It's very free!</h5>

            <button class="btn btn-success mb-2">Get the launcher</button>
            <br>
            <button class="btn btn-success" onclick="document.location = 'https://google.com/search?q=mobile+roblox+revivals'">Mobile version</button>
        </div>
    </x-card>
</div>
</x-app-layout>
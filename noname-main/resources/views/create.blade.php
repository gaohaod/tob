@section('title', 'Create')
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'create new asset'">
            <p class="text-muted fw-regular">Please select what asset type you want to create.</p>

            <div class="row">
                <div class="col-sm">
                    <div class="card card-body clickable" onclick="document.location = '/app/create/place'">
                        <h4 class="mb-2 text-info"> <i class='far fa-trophy-alt mr-2'></i> place</h4>
                        <p class="text-muted mb-0">Create a new place. RBXL and RBXLX format <i>only</i></p>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card card-body clickable" onclick="document.location = '/app/create/asset'">
                        <h4 class="mb-2 text-success"> <i class='far fa-child mr-2'></i> asset</h4>
                        <p class="text-muted mb-0">Create a decal, shirt, pants, t-shirt and etc.</p>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</x-app-layout>
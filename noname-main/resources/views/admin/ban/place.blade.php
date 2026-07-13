@section('title', 'Moderation')
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'ban asset'">
            <p class="fw-regular text-muted">Ban an asset. This action is <i class="text-danger">destructive</i> and can get you demoted.</p>

                <div class="form-group mb-2">
                        <input type="text" name="placeId" class="form-control" id="placeidtoban" placeholder="Place id to ban">
                        <button class="btn btn-danger" onclick="ban()">ban</button>

                </div>
        </x-card>
    </div>

    <script>
        async function ban() {
            let id = document.getElementById('placeidtoban');

            const response = await fetch('/app/admin/decline-asset/' + id.value);

            window.location.reload();
        }   
    </script>
</x-app-layout>
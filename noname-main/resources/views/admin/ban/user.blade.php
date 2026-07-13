@section('title', 'Moderation')
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'ban user'">
            <p class="fw-regular text-muted">Ban a user. This action is <i class="text-danger">destructive</i> and can get you demoted.</p>

                <div class="form-group mb-2">
                        <input type="text" name="usrname" class="form-control" id="usernameToBan" placeholder="Username to ban...">
                </div>

                <button class="btn btn-danger" onclick="ban()">ban</button>

        </x-card>
    </div>

        <script>
        async function ban() {
            let id = document.getElementById('usernameToBan');

            const response = await fetch('/app/ban-user/' + id.value);

            window.location.reload();

        }   
    </script>
</x-app-layout>
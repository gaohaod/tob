@section('title', 'Moderation')
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="config('app.name', 'Laravel') . ' moderation'" :bg="'bg-danger'">
            <p class="text-muted fw-regular">
                Your account has been banned from {{ config('app.name', 'Laravel') }} because you violated our rules<br>

                <strong>Ban reason:</strong> {{ Auth::user()->ban_reason }}<br>

                Your account can't be re-activated.

            </p>


            <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger w-100">log out</button>
            </form>

        </x-card>
    </div>
</x-app-layout>
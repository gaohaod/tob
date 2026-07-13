@section('title', 'Invite Keys')
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'invite keys'" :haspadding="'nope'">
            <table class="table table-striped mb-0">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Key</th>
      <th scope="col">Used</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    @if ($invites->isEmpty())
    <tr>
        <th scope="row">N/A</th>
        <th scope="row">N/A</th>
        <th scope="row">N/A</th>
        <th scope="row">N/A</th>
    </tr>
    @else
        @foreach ($invites as $invite)
    <tr>
      <th scope="row">{{ $invite->id }}</th>
      <td><code>{{ $invite->key }}</code></td>
      <td>{{ $invite->used ? "used" : "not used" }}</td>
      <td><a href="/app/admin/revoke-invite/{{ $invite->key }}" class="mr-2">revoke</a> <a href="/app/admin/renew-invite/{{ $invite->key }}">renew</a></td>
    </tr>
        @endforeach
    @endif
  </tbody>
</table>
        </x-card>
    </div>

    <script src="/functions.js"></script>
</x-app-layout>
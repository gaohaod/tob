@section('title', 'Alerts')
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'alerts'" :haspadding="'nope'">
            <table class="table table-striped mb-0">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Color</th>
      <th scope="col">Content</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    @if ($alerts->isEmpty())
    <tr>
      <th scope="row">N/A</th>
      <th scope="row">N/A</th>
      <th scope="row">N/A</th>
      <th scope="row">N/A</th>
    </tr>
    @else
        @foreach ($alerts as $alert)
    <tr>
      <th scope="row">{{ $alert->id }}</th>
      <td>{{ $alert->color }}</td>
      <td>{{ $alert->content }}</td>
      <td><a href="/app/admin/remove-alert/{{ $alert->id }}" class="mr-2">remove</a></td>
    </tr>
        @endforeach
    @endif
  </tbody>
</table>
        </x-card>
    </div>

    <script src="/functions.js"></script>
</x-app-layout>
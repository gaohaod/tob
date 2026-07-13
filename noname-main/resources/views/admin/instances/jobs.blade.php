@section('title', 'Moderation')
<x-app-layout>
   <div class="container mt-5">
      <x-card :title="'jobs'">
         <p class="fw-regular text-muted">See all RCC jobs.</p>
         <table class="table table-striped table-bordered mb-0">
            <thead>
               <tr>
                  <th scope="col">id</th>
                  <th scope="col">expiration</th>
                  <th scope="col">category</th>
                  <th scope="col">cores</th>
                  <th scope="col">options</th>
               </tr>
            </thead>
            <tbody>
@if(empty($jobs))
    <tr>
        <td colspan="5">No jobs available.</td>
    </tr>
@else
    @foreach ($jobs as $job)
        <tr>
            <th scope="row">{{ $job->id ?? 'N/A' }}</th>
            <td>{{ $job->expirationInSeconds ?? 'N/A' }}</td>
            <td>{{ $job->category ?? 'N/A' }}</td>
            <td>{{ $job->cores ?? 'N/A' }}</td>
            <td><a href="/app/admin/close-job/{{ $job->id ?? '' }}">close</a></td>
        </tr>
    @endforeach
@endif
</tbody>

         </table>
      </x-card>
   </div>
</x-app-layout>

@section('title', 'Moderation')
<x-app-layout>
   <div class="container mt-5">
      <x-card :title="'instances'">
         <p class="fw-regular text-muted">See all RCC instances.</p>
         <table class="table table-striped table-bordered mb-0">
            <thead>
               <tr>
                  <th scope="col">id</th>
                  <th scope="col">created</th>
                  <th scope="col">updated</th>
                  <th scope="col">options</th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <th scope="row">250a6f79-7b31-4aa3-866e-245e1771deb5</th>
                  <td>2024-09-18 18:43:52</td>
                  <td>2024-09-18 18:44:05</td>
                  <td><button class="btn btn-sm btn-success w-100">close</button></td>
               </tr>
            </tbody>
         </table>
      </x-card>
   </div>
</x-app-layout>

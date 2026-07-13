@section('title', 'Transactions')
<x-app-layout>
    <div class="container mt-5">
        <x-card title="Transactions" haspadding="nope">
            <table class="table table-striped mb-0">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Item Creator</th>
                    <th scope="col">Item Price</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($transactions->isEmpty())
                  <tr>
                    <th scope="row">N/A</th>
                    <th scope="row">N/A</th>
                    <th scope="row">N/A</th>
                    <th scope="row">N/A</th>
                  </tr>
                  @else
                      @foreach ($transactions as $transaction)
                  <tr>
                    <th scope="row">{{ $transaction->asset->id }}</th>
                    <td><a href="/app/item/{{ $transaction->asset->id }}">{{ $transaction->asset->name }}</a></td>
                    <td><a href="/app/user/{{ $transaction->asset->user->id }}">{{ $transaction->asset->user->name }}</a></td>
                    <td><div class="d-inline-block"><img src="/images/{{ Auth::user()->using_alternative_peeps ? 'peeps_alternative.png' : 'peeps.png' }}" width="24" class="mr-2 align-middle"> {{ $transaction->asset->peeps }}</div></td>
                  </tr>
                      @endforeach
                  @endif
                </tbody>
              </table>
        </x-card>

        <div class="mt-3 d-flex justify-content-center w-100">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>
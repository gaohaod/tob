@section('title', 'Admin Panel')
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'assets'">
            <div class="row">
            @foreach ($underReview as $item)
            <div class="col-2 mb-2 px-2">
                <x-admin-catalog-card :title="$item->name" :status="$item->peeps . ' Peeps'" :id="$item->id" :price="$item->peeps" :offsale="$item->off_sale" :under_review="$item->under_review" :type="$item->type" :imgsize="165"  />
            </div>
            @endforeach
            </div>

            <div class="mt-3 d-flex justify-content-center w-100">
            {{ $underReview->links() }}
            </div>
        </x-card>
    </div>

    <script src="/functions.js"></script>
</x-app-layout>
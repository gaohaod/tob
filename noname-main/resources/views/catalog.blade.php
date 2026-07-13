@section('title', 'Catalog')
<x-app-layout>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">

                <button type="button" class="btn btn-outline-success mb-2 w-100" onclick="document.location = '/app/create'"><i class="far fa-plus mr-2"></i>{{ __('create a new asset') }}</button>

                <h5 class="mb-2">
                    <div class="my-2" style="font-weight: 400;">Type</div>
                    <a href="/app/catalog" {!! request()->is('app/catalog') ? 'class="text-success font-weight-regular" style="font-weight: 500;"' : 'class="text-muted"' !!}>all items</a><br>
                    <a href="/app/catalog/hats" {!! request()->is('app/catalog/hats') ? 'class="text-success font-weight-regular" style="font-weight: 500;"' : 'class="text-muted"' !!}>hats</a><br>
                    <a href="/app/catalog/heads"  {!! request()->is('app/catalog/heads') ? 'class="text-success font-weight-regular" style="font-weight: 500;"' : 'class="text-muted"' !!}>heads</a><br>
                    <a href="/app/catalog/shirts"  {!! request()->is('app/catalog/shirts') ? 'class="text-success font-weight-regular" style="font-weight: 500;"' : 'class="text-muted"' !!}>shirts</a><br>
                    <a href="/app/catalog/pants"  {!! request()->is('app/catalog/pants') ? 'class="text-success font-weight-regular" style="font-weight: 500;"' : 'class="text-muted"' !!}>pants</a><br>
                    <a href="/app/catalog/tshirts"  {!! request()->is('app/catalog/tshirts') ? 'class="text-success font-weight-regular" style="font-weight: 500;"' : 'class="text-muted"' !!}>t-shirts</a><br>
                    <a href="/app/catalog/faces"  {!! request()->is('app/catalog/faces') ? 'class="text-success font-weight-regular" style="font-weight: 500;"' : 'class="text-muted"' !!}>faces</a><br>
                    <a href="/app/catalog/gears"  {!! request()->is('app/catalog/gears') ? 'class="text-success font-weight-regular" style="font-weight: 500;"' : 'class="text-muted"' !!}>gears</a><br>
                    <a href="/app/catalog/audios"  {!! request()->is('app/catalog/audios') ? 'class="text-success font-weight-regular" style="font-weight: 500;"' : 'class="text-muted"' !!}>audios</a><br>
                    <a href="/app/catalog/models"  {!! request()->is('app/catalog/models') ? 'class="text-success font-weight-regular" style="font-weight: 500;"' : 'class="text-muted"' !!}>models</a><br>
                    <a href="/app/catalog/decals"  {!! request()->is('app/catalog/decals') ? 'class="text-success font-weight-regular" style="font-weight: 500;"' : 'class="text-muted"' !!}>decals</a><br>
                    <a href="/app/catalog/mesh"  {!! request()->is('app/catalog/mesh') ? 'class="text-success font-weight-regular" style="font-weight: 500;"' : 'class="text-muted"' !!}>meshes</a><br>
                </h5>
            </div>
            <div class="col">

                <form action="/app/catalog/search" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col">
                            <input type="text" class="form-control w-100 mb-2" id="searchInput" name="search" value="{{ $search ?? '' }}" placeholder="Search">
                        </div>
                        <div class="col-auto pl-0">
                            <button class="btn btn-success w-100"><i class="far fa-search mr-2"></i> search</button>
                        </div>
                    </div>
                    
                    
                </form>

            @if ($items->isEmpty())

            <div class="text-center">
            <img src="/images/neutral.png" alt="Neutral face" width="46" class="mb-2">
            <p class="text-center text-muted">There aren't any items here.</p>
            </div>

            @else 
            <div class="row">
            @foreach ($items as $item)
            <div class="col-2 mb-2 px-2">
                <x-catalog-card :title="$item->name" :creatorName="$item->user->name" :creatorId="$item->user->id" :special="$item->special" :status="$item->peeps" :id="$item->id" :price="$item->peeps" :offsale="$item->off_sale" :under_review="$item->under_review" :type="$item->type" :fortwelve="$item->for2012" />
            </div>
            @endforeach
            </div>
            <div class="mt-3 d-flex justify-content-center w-100">
                {{ $items->links() }}
            </div>
            @endif
            </div>
        </div>

    </div>


    <script src="/functions.js"></script>
</x-app-layout>
@section('title', 'Places')
<x-app-layout>
    <div class="container">
        <h3 class="font-weight-light mt-5">Places</h3>
        <h6 class="fw-regular text-muted mb-4">See all places made by NONAME users just like you</h6>

        <form action="/app/places/search" method="GET">
        <div class="row gap-1">
           
            <div class="col pr-1">
                <div class="form-group">
                    <input type="text" class="form-control w-100" id="searchInput" name="search" value="{{ $search ?? '' }}" placeholder="Search">
                </div>
            </div>
            <div class="col-auto pl-1"><button class="btn btn-success"><i class="far fa-search"></i></button></div>
            
        </div>
        </form>

        <nav class="nav nav-pills flex-column flex-sm-row mb-4">
            <a class="flex-sm-fill text-sm-center nav-link text-lowercase" id="pills-featured-tab" data-toggle="pill" data-target="#pills-featured" type="button" role="tab" aria-controls="pills-featured" href="#">Featured</a>
            <a class="flex-sm-fill text-sm-center nav-link active text-lowercase" id="pills-places-tab" data-toggle="pill" data-target="#pills-places" type="button" role="tab" aria-controls="pills-places"  aria-selected="true" href="#">Places</a>
        </nav>


        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade" id="pills-featured" role="tabpanel" aria-labelledby="pills-featured-tab">
                @if ($featured->isEmpty())

                <p class="text-center text-muted">No one's around.</p>

                @else 
                <div class="row">
                @foreach ($featured as $feature)
                <div class="col-md-3 mb-3">
                    <x-place-card :title="$feature->name" :under_review="$feature->under_review" :status="$feature->playing" :creator="$feature->user->name" :special="$feature->special" :visits="$feature->visits" :year="$feature->year" :id="$feature->id" :thumbnail="$feature->thumbnailUrl" />
                </div>
                @endforeach
                </div>
                <div class="mt-3 d-flex justify-content-center w-100">
                    {{ $featured->links() }}
                </div>
                @endif
            </div>
            <div class="tab-pane fade show active" id="pills-places" role="tabpanel" aria-labelledby="pills-places-tab">
                
                @if ($games->isEmpty())

                <p class="text-center text-muted">No one's around.</p>

                @else 
                <div class="row">
                @foreach ($games as $game)
                <div class="col-md-3 mb-3">
                    <x-place-card :title="$game->name" :under_review="$game->under_review" :status="$game->playing" :creator="$game->user->name" :special="$game->special" :visits="$game->visits" :year="$game->year" :id="$game->id" :thumbnail="$game->thumbnailUrl" />
                </div>
                @endforeach
                </div>
                <div class="mt-3 d-flex justify-content-center w-100">
                    {{ $games->links() }}
                </div>
                @endif

            </div>
        </div>


    </div>


    <script src="/functions.js"></script>
</x-app-layout>

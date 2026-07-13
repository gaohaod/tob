@section('title', 'Home')
<x-app-layout>
    <div class="jumbotron jumbotron-fluid mb-4 jumbo-bg">
        <div class="container">
            <div class="row">
                <div class="col text-left">
                    <h1 class="display-4 text-white">welcome back, {{ Auth::user()->name }}</h1>
                    <p class="lead text-white mb-4">偷看蛋糕。不！它们会进入您的 NONAME 钱包！</p>

                    <button class="btn btn-lg btn-success">🢒 go play games</button>
                </div>

                <div class="col-auto text-right">
                    <img src="/images/load.gif" class="lazy-load" data-src="/cdn/users/{{ Auth::id() }}?t={{ time() }}" alt="User" width="200" height="100%">
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <hr>

        @if (\Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! \Session::get('success') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <h3 class="font-weight-light mb-3">popular places</h3>

        <div class="row">
        @foreach ($popular as $game)
            <div class="col-md-3">
                <x-place-card :title="$game->name" :status="$game->playing" :creator="$game->user->name" :visits="$game->visits" :year="$game->year" :id="$game->id" :under_review="$game->under_review"  />
            </div>
            @endforeach
        </div>

            <hr>

            <h3 class="font-weight-light mb-3">follow us on our socials</h3>

    <div class="row">
    <div class="col d-flex">
        <div class="card card-body border">
            <h3 class="font-weight-light"><i class="fab fa-discord mr-2" style="color: #7289DA;"></i> Discord</h3>
            <p class="mb-0 text-muted">
                
                Our community's pretty active. To chat in our Discord, you need to verify your account first. Join and DM staff with your token.

            </p>
        </div>
    </div>
<div class="col d-flex">
        <div class="card card-body border">
            <h3 class="font-weight-light"><i class="fab fa-youtube mr-2" style="color: #FF0000;"></i> YouTube</h3>
            <p class="mb-0 text-muted">
                
                Some teasers and exclusives may drop here. We also drop some gameplays and trailers often, subscribe!

            </p>
        </div>
    </div>
    
    </div>

    </div>




    <script src="/functions.js"></script>
</x-app-layout>

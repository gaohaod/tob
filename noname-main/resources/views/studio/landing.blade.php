<?php
    use App\Http\Controllers\ClientUserController;

    $usr = ClientUserController::get();

?>
<x-guest-layout>
    <title>Landing</title>
<div class="jumbotron jumbotron-fluid mb-4">
        <div class="container">
            @if (!$usr == '12' || !$usr == '11')

            <h1 class="display-4">welcome back, {{ $usr->name }}</h1>
            <p class="lead">Fun fact: Raymonf commited 19/5</p>

            @else
            
            <h1 class="display-4">welcome to Studio</h1>
            <p class="lead">Fun fact: Raymonf commited 19/5</p>

            @endif
        </div>
    </div>

    <div class="container">
        <hr>
        idk what to put here lol
    </div>

</x-guest-layout>
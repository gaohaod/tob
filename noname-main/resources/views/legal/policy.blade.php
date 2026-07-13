@section('title', 'Privacy Policy')
@auth
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'Privacy Policy'">
            <p class="text-muted mb-0">
                Welcome to {{ config('app.name', 'Laravel') }}! <br>
                By playing {{ config('app.name', 'Laravel') }}, you agree that you are over 13 years old.<br>
                We collect your e-mail just because Laravel's Breeze template requires us to do so. If we do, we will notify you.<br>
                Your IP address is stored via Laravel's session system. From 2/3/2025 we will now be storing your IP at <i>register</i> and the last time you were seen. Don't worry, they are hashed. We will use this for IP bans, mostly and alt prevention. <br>
                In the future, we plan to implement IP storing, but hash it in the database. This will be used for IP bans which will be implemented soon.<br>
                Don't sue us.<br>
                That is all. <br>
                Last updated: <code>9/6/2024 9:38PM</code>
            </p>
        </x-card>
    </div>
</x-app-layout>
@endauth

@guest
<x-guest-layout>
<nav class="navbar navbar-expand-lg navbar-dark bg-success border-bottom shadow-sm ">
    <div class="container">
  <a class="navbar-brand fst-italic fw-medium" href="/">{{ config('app.name', 'Laravel') }}</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link {{ request()->is('/') ? 'active' : ''}}" href="/">Home <span class="sr-only">(current)</span></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('app/register') ? 'active' : ''}}" href="/app/register">Register</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('app/login') ? 'active' : ''}}" href="/app/login">Log in</a>
        </li>
    </ul>
  </div>
  </div>
</nav>

<div class="container mt-5">
        <x-card :title="'Rules'">
        <p class="text-muted mb-0">
                Welcome to {{ config('app.name', 'Laravel') }}! <br>
                By playing {{ config('app.name', 'Laravel') }}, you agree that you are over 13 years old.<br>
                We collect your e-mail just because Laravel's Breeze template requires us to do so. If we do, we will notify you.<br>
                Your IP address is stored via Laravel's session system. We will <strong>not need your IP</strong> for any purpose.<br>
                In the future, we plan to implement IP storing, but hash it in the database. This will be used for IP bans which will be implemented soon.<br>
                That is all. <br>
                Last updated: <code>9/6/2024 9:38PM</code>
            </p>
        </x-card>
    </div>

</x-guest-layout>
@endguest
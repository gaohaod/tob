@section('title', 'Rules')
@auth
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'Rules'">
<p class="text-muted mb-0">
    Welcome to {{ config('app.name', 'Laravel') }}!
    <br>
    1. No spamming. This applies on both Discord and the website. Spamming on the forums will get you banned.
    <br>
    2. No exploiting. If we catch you exploiting or if you get reported, you’ll be banned right away.
    <br>
    3. No slurs. Using slurs (or any kind of hate speech) is not allowed. You’ll be banned for using them anywhere. (This applies to <mark>EVERY</mark> slur.)
    <br>
    4. You need to be 13 or older to play {{ config('app.name', 'Laravel') }}. This is just to follow privacy rules. If you’re under 13, you’ll be banned, but you can come back once you're old enough.
    <br>
    5. Immaturity isn’t a ban-worthy offense, but it’s recommended to be respectful and mature while playing.
    <br>
    6. Don’t spam your place slots. Use them wisely. If your places are just copies or used to farm Peeps (currency), they’ll be banned, and you’ll lose 5 Peeps.
    <br>
    7. No witch-hunting or doxxing on the forums or Discord. You’ll be banned immediately, no questions asked.
    <br>
    8. Racism or trying to be "edgy" isn’t cool. If you cross the line, you’ll be banned. This includes:
    <ul>
        <li>Nazism</li>
        <li>Racism</li>
        <li>Discriminating against others for their beliefs or opinions</li>
    </ul>
    <br>
    <b>{{ config('app.name', 'Laravel') }} operates on a no-tolerance policy. All bans are permanent unless we find out it was a mistake.</b>
    <br>
    <br>
    Last updated: <code>1/24/2025 9:31 AM</code>
</p>
        </x-card>

        <div class="my-2"></div>

        <x-card title="Username rules">
            <p class="text-muted mb-0">
                Your username must follow these rules:<br>
                <ul>
                    <li>Immature usernames like "GyattSkibidi69" are NOT allowed. You will be instantly banned.</li>
                    <li>If your username contains slurs, you will be banned instantly.</li>
                    <li>Just make sure it follows the rules, it's easy.</li>
                </ul>
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
    Welcome to {{ config('app.name', 'Laravel') }}!
    <br>
    1. No spamming. This applies on both Discord and the website. Spamming on the forums will get you banned.
    <br>
    2. No exploiting. If we catch you exploiting or if you get reported, you’ll be banned right away.
    <br>
    3. No slurs. Using slurs (or any kind of hate speech) is not allowed. You’ll be banned for using them anywhere. (This applies to <mark>EVERY</mark> slur.)
    <br>
    4. You need to be 13 or older to play {{ config('app.name', 'Laravel') }}. This is just to follow privacy rules. If you’re under 13, you’ll be banned, but you can come back once you're old enough.
    <br>
    5. Immaturity isn’t a ban-worthy offense, but it’s recommended to be respectful and mature while playing.
    <br>
    6. Don’t spam your place slots. Use them wisely. If your places are just copies or used to farm Peeps (currency), they’ll be banned, and you’ll lose 5 Peeps.
    <br>
    7. No witch-hunting or doxxing on the forums or Discord. You’ll be banned immediately, no questions asked.
    <br>
    8. Racism or trying to be "edgy" isn’t cool. If you cross the line, you’ll be banned. This includes:
    <ul>
        <li>Nazism</li>
        <li>Racism</li>
        <li>Discriminating against others for their beliefs or opinions</li>
    </ul>
    <br>
    <b>{{ config('app.name', 'Laravel') }} operates on a no-tolerance policy. All bans are permanent unless we find out it was a mistake.</b>
    <br>
    <br>
    Last updated: <code>1/24/2025 9:31 AM</code>
</p>

        </x-card>
    </div>

</x-guest-layout>
@endguest
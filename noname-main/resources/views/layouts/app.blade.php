<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="/css/nootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/custom.css?t={{ time() }}" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro@f96a46a/css/all.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/plyr.css" />

    <title>@yield('title', 'no title defined') - {{ config('app.name', 'Laravel') }}</title>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-noname shadow-sm">
<div class="container">
  <a class="navbar-brand fst-italic fw-medium" href="/app/home"><img src="/images/logo.png" width="32" alt="NONAME Logo" class="mr-2">
  {{ config('app.name', 'Laravel') }}
</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item {{ request()->is('app/home', '/') ? 'active' : ''}}">
        <a class="nav-link" href="/app/home">Home</a>
      </li>
      <li class="nav-item {{ request()->is('app/places', 'app/place/*', 'app/places/*') ? 'active' : ''}}">
        <a class="nav-link" href="/app/places">Places</a>
      </li>
      <li class="nav-item {{ request()->is('app/catalog', 'app/item/*', 'app/catalog/*') ? 'active' : ''}}">
        <a class="nav-link" href="/app/catalog">Catalog</a>
      </li>
      <li class="nav-item {{ request()->is('app/create', 'app/create/*') ? 'active' : ''}}">
        <a class="nav-link" href="/app/create">Create</a>
      </li>

      <li class="nav-item {{ request()->is('app/forum/*', 'app/forum') ? 'active' : ''}}">
        <a class="nav-link" href="/app/forum">Forum</a>
      </li>

      <li class="nav-item {{ request()->is('app/videos/*', 'app/videos') ? 'active' : ''}}">
        <a class="nav-link" href="/app/videos">Videos</a>
      </li>

      <li class="nav-item dropdown fw-semibold">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
        More
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="/app/download">Download</a>
          @if (Auth::user()->admin == 1)
          <a class="dropdown-item text-danger" href="/app/admin/main">Admin</a>
          @endif
        </div>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">

<li class="nav-item dropdown d-flex align-middle">
  <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
    <i class="far fa-bell"></i> <span class="badge badge-success ml-2" style="display: none;" id="notification-count">0</span>
  </a>
  <div class="dropdown-menu" id="notification-list">
    <a class="dropdown-item" href="/app/clear-all-notifications">Clear all</a>
    <div class="dropdown-divider"></div>
    <div id="notifications"></div>
  </div>
</li>

<li class="nav-item d-flex align-items-center {{ request()->is('app/message/*', 'app/messages') ? ' active' : '' }}">
  <a class="nav-link" href="/app/messages">
    <i class="far fa-envelope align-middle"></i>  @if ($unreadPMs->count() > 0) <span class="badge badge-success ml-2">{{ $unreadPMs->count() }}</span> @endif

  </a>
</li>

<li class="nav-item dropdown d-flex align-middle mx-1">
  <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
    <i class="far fa-users"></i> 
  </a>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="/app/friends/pending">Pending requests</a>
    <a class="dropdown-item" href="/app/user/{{ Auth::user()->id }}/friends">Friends</a>
  </div>
</li>

    <li class="nav-item">
        <a class="nav-link d-flex align-middle" href="/app/transactions" data-toggle="tooltip" data-placement="bottom" data-html="true" title="You have {{ Auth::user()->peeps }} Peeps<br>Click to view your transactions."><img src="/images/{{ Auth::user()->using_alternative_peeps ? 'peeps_alternative.png' : 'peeps.png' }}" width="24" class="mr-2 align-middle"> <span id="userPeeps">{{ Auth::user()->peeps }}</span></a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
        <i class='far fa-user mr-2'></i>{{ Auth::user()->name }}
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="/app/settings">Settings</a>
          <div class="dropdown-divider"></div>
          <form method="POST" class="mb-0" action="{{ route('logout') }}">
          @csrf
          <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); this.closest('form').submit();">Log out</a>
          </form>
        </div>
      </li>
    </ul>

  </div>
</div>
</nav>

    <nav class="navbar navbar-expand-lg navbar-dark bg-noname-2 shadow-sm p-0">
<div class="container">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item {{ request()->is('app/user/*') ? 'active' : ''}}">
        <a class="nav-link" href="/app/user/{{ Auth::user()->id }}">Profile</a>
      </li>
      <li class="nav-item {{ request()->is('app/avatar') ? 'active' : ''}}">
        <a class="nav-link" href="/app/avatar">Avatar</a>
      </li>
      <li class="nav-item {{ request()->is('app/users') ? 'active' : ''}}">
        <a class="nav-link" href="/app/users">Users</a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="#">Discord</a>
      </li>
    </ul>

  </div>
</div>
</nav>

@foreach ($alerts as $alert)
<div class="bg-{{ $alert->color }} w-100 p-1 text-center
@if ($alert->color == 'success' || $alert->color == 'danger')
text-light
 @endif shadow-sm">{!! $alert->content !!}</div>
@endforeach

    {{ $slot }}

    <div class="container my-5">
      <div class="card card-body">
        <p class="mb-0 fw-medium text-muted mb-0">{{ config('app.name', 'Laravel') }} &copy; {{ date('Y') }} <span class="px-1">|</span> {{ config('app.name', 'Laravel') }} is not affiated with ROBLOX.<br> Frontend inspired by Finobe. <span class="px-1">|</span> Built with Laravel and some love</p>
        <p class="mb-0"><a href="/app/policy" class="text-success">Privacy Policy</a> <span class="px-1">|</span> <a href="/app/rules" class="text-success">Rules</a></p>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script>
      let formattedPrice = numeral({{ Auth::user()->peeps }}).value();
formattedPrice = (formattedPrice >= 1000) 
  ? numeral(formattedPrice).format('0.0a') 
  : numeral(formattedPrice).format('0a');



      document.getElementById('userPeeps').innerHTML = formattedPrice;
    </script>
    <script>
        $(function () {
  $('[data-toggle="popover"]').popover()
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});

document.addEventListener("DOMContentLoaded", function () {
  const notificationCount = document.getElementById('notification-count');
  const notificationList = document.getElementById('notification-list');
  const notificationsContainer = document.getElementById('notifications');

  function clearAll() {
    fetch('/app/clear-all-notifications')
    .then(response => response.text())
    .then(data => {

      notificationsContainer.innerHTML = '';
      notificationCount.classList.add('invisible');
    })
    .catch(error => {
      console.error('oops ', error);
    });
  }

  function fetchNotifications() {
    fetch('/app/get-notifications')
      .then(response => response.json())
      .then(notifications => {
        const maxNotifications = 10;
        const recentNotifications = notifications.slice(0, maxNotifications);

        notificationsContainer.innerHTML = '';

        recentNotifications.forEach(notification => {
          const notificationItem = document.createElement('a');
          notificationItem.classList.add('dropdown-item');
          notificationItem.href = '#';
          notificationItem.innerHTML = '<i class="far mr-2 fa-' + notification.icon + '"></i>' + notification.content;
          notificationsContainer.appendChild(notificationItem);
        });

        notificationCount.textContent = notifications.length;
        if (notifications.length > 0) {
          notificationCount.style.display = "block";
        } else {
          notificationCount.style.display = "none";
        }
      })
      .catch(error => {
        console.error('couldnt fetch notifications:', error);
      });
  }

  setInterval(fetchNotifications, 60000);

  fetchNotifications();
});


    </script>
  </body>
</html>

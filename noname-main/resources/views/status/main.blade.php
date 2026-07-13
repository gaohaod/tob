<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="/css/nootstrap.min.css" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <title>NONAME Statuser 9000</title>
  </head>
  <style>
    body {
      font-family: "Source Sans 3", sans-serif;
    }
  </style>
  <body>
    
    <div class="container mt-5">

      <div class="mb-3 align-middle d-flex">
        <img src="/images/logo.png" width="24"><span class="mx-3">|</span><a href="/">Back to NONAME</a></span> <span class="mx-3">|</span> Status: <span class="text-success">OK</span>
      </div>

      <p class="text-muted mt-2">Gameserver (2017) is offline.</p>

      <p class="text-muted">
        Gameserver (2012) : <span class="text-success">online</span>
        <br>
        Gameserver (2017) : <span class="text-danger">offline</span>
        <br>
        Thumbnail Server : <span class="text-success">online</span>
        <br>
        Webserver : <span class="text-success">online</span>
      </p>

      <p>Time to load page: <span id="time"></span>ms <span class="text-muted">(so lightweight)</span></p>

    </div>


    <script>
      var loadTime = window.performance.timing.loadEventEnd ;
      document.getElementById('time').innerHTML = loadTime;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
  </body>
</html>

@section('title', 'Log in')
<x-guest-layout>

<nav class="navbar navbar-expand-lg navbar-dark bg-success border-bottom shadow-sm mb-5">
    <div class="container">
  <a class="navbar-brand fst-italic fw-medium" href="/">{{ config('app.name', 'Laravel') }}</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link {{ request()->is('/') ? 'active' : ''}}" href="/">Home</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('app/register') ? 'active' : ''}}" href="/app/register">Register</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('app/login') ? 'active' : ''}}" href="/app/login">Login</a>
        </li>
    </ul>
  </div>
  </div>
</nav>


<div class="container">
    <x-card :title="'log in'">
        <form method="POST" action="{{ route('login') }}">
        @csrf

                    <div class="mb-3 form-group">
                        <label for="emailInput">Email</label>
                        <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="emailInput" name="email" :value="old('email')" required autocomplete="username">
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group">
                        <label for="passwordInput">Password</label>
                        <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="passwordInput" name="password" aria-describedby="passwordHelp" required autocomplete="new-password">
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!--<div class="custom-control custom-checkbox mb-3">
                        <input class="form-check-input" type="checkbox" id="rememberCheck">
                        <label class="form-check-label" for="rememberCheck">
                            remember me
                        </label>
                    </div>-->

                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" name="remember" id="rememberCheck">
                        <label class="custom-control-label" for="rememberCheck">remember me</label>
                      </div>


                    <button class="btn btn-success btn-lg w-100" type="submit">log in</button>

        </form>
    </x-card>
</div>
</x-guest-layout>

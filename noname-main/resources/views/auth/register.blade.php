@section('title', 'Register')
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
    <x-card :title="'create a new account'">
        <form method="POST" action="{{ route('register') }}">
        @csrf

                    <div class="mb-3 form-group">
                        <label for="usernameInput">Username</label>
                        <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="usernameInput" aria-describedby="usernameHelp" name="name" :value="old('name')" required autofocus autocomplete="name">
                        <small id="usernameHelp" class="form-text text-muted">You can't change this later.</small>
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

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
                        <small id="passwordHelp" class="form-text text-muted">Passwords are hashed. Make sure your password is secure.</small>
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group">
                        <label for="confirmPasswordInput">Confirm password</label>
                        <input type="password" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" id="confirmPasswordInput" name="password_confirmation" required autocomplete="new-password">
                        @error('password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    @if (env('INVITES_DISABLED', false))

                    <div class="mb-3 form-group">
                        <label for="inviteKeyInput">Invite key</label>
                        <input type="text" class="form-control {{ $errors->has('invite_key') ? 'is-invalid' : '' }}" id="inviteKeyInput" name="invite_key" required>
                        <small id="inviteKeyHelp" class="form-text text-muted">You must be <i>invited</i> to play {{ config('app.name', 'Laravel') }}</small>
                        @error('invite_key')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    @endif

                    <div class="form-group mb-3">
                        <label for="genderselect">Gender</label>
                        <select class="form-control {{ $errors->has('gender') ? 'is-invalid' : '' }}" name="gender" id="genderselect">
                            <option value="m">male</option>
                            <option value="f">female</option>
                        </select>
                        @error('gender')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>


                    <button class="btn btn-success btn-lg w-100" type="submit">Create Account</button>

        </form>
    </x-card>
</div>
</x-guest-layout>

<div>
    <h4 class="font-weight-light">update password</h4>
    <h6 class="fw-regular text-muted mb-3">Please make sure your password is random and secure.</h6>

    <form method="POST" action="{{ route('password.update') }}">
    @csrf
    @method('PUT')

    <div class="form-group row mb-2">
    <label for="staticCurrentPassword" class="col-sm-2 col-form-label">current password</label>
    <div class="col-sm-10">
      <input type="password" name="current_password" class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}" id="staticCurrentPassword">
        @error('current_password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

    <div class="form-group row mb-2">
    <label for="staticNewPassword" class="col-sm-2 col-form-label">new password</label>
    <div class="col-sm-10">
        <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="staticNewPassword">
        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

    <div class="form-group row mb-2">
    <label for="staticConfirmPassword" class="col-sm-2 col-form-label">confirm password</label>
    <div class="col-sm-10">
      <input type="password" name="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" id="staticConfirmPassword">
      @error('password_confirmation')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

    @if (session('status') === 'password-updated')
        <p class="text-muted fw-regular mb-0">Saved.</p>
    @else
        <button class="btn btn-success" type="submit">Change password</button>
    @endif

    </form>

</div>
<div class="mt-4">
    <form action="/app/settings/change-bio" method="POST">
        @csrf

        <div class="mb-3 form-group">
                    <label for="bioInput">new bio</label>
                    <textarea type="text" class="form-control {{ $errors->has('bio') ? 'is-invalid' : '' }}" id="bioInput" name="bio" :value="old('bio')" placeholder="{{ Auth::user()->bio }}" required></textarea>
                    <small id="bioInput" class="form-text text-muted">Note: Your bio must be lower than 1000 characters.</small>
                    @error('bio')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

        <button class="btn btn-success" type="submit"><i class="far fa-check mr-2"></i>change bio</button>

    </form>

    <br>


<form method="POST" action="{{ route('app.user.change-gender') }}" novalidate>
    @csrf
    <div class="form-group">
        <label for="gender">select your gender</label>
        <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror @if(session('success')) is-valid @endif">
            <option value="">Choose...</option>
            <option value="m" {{ old('gender') === 'm' ? 'selected' : '' }}>Male</option>
            <option value="f" {{ old('gender') === 'f' ? 'selected' : '' }}>Female</option>
        </select>
        @error('gender')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
        @if (session('success'))
            <div class="valid-feedback">
                {{ session('success') }}
            </div>
        @endif
    </div>
    <button type="submit" class="btn btn-primary">Update Gender</button>
</form>
</div>
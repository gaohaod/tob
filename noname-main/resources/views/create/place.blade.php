@section('title', 'Create New Place')
<x-app-layout>
    <div class="container mt-5">

    @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        @if (Auth::user()->place_slots_left == 0)

        <x-card :title="'no place slots left'">
            <div class="text-center my-5">
                <img src="/images/sweat.png" alt="Sweating blob" width="100" class="mb-3">

                <h3 class="font-weight-light">You don't have any more place slots.</h3>
                <h6 class="font-weight-regular text-muted mb-4">You can buy more in Settings. To buy place slots, go to settings. It costs 250 <x-peep-icon :size="16" :spacing="true" />.</h6>

                <button class="btn btn-success" onclick="history.back()">🢐 &nbsp; go back</button> <button class="btn btn-success" onclick="document.location = '/app/settings'">🢐 &nbsp; settings</button>
            </div>
        </x-card>

        @else
        <x-card :title="'create new place'">
            <form action="/app/upload-place" method="POST" enctype="multipart/form-data">
            @csrf

                <div class="mb-3 form-group">
                    <label for="nameInput">name</label>
                    <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="nameInput" name="name" :value="old('name')" required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3 form-group">
                    <label for="descriptionInput">description</label>
                    <textarea type="text" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" id="descriptionInput" name="description" :value="old('description')" required></textarea>
                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3 form-group">
                    <label for="typeInput">year</label>
                    <select class="form-control" name="yearxd" id="typeInput">
                        <option value="2012" selected>2012</option>
                        <option value="2017" selected>2017</option>
                    </select>
                </div>

                <div class="mb-3 form-group">
                    <label for="maxPlayersInput">max players <small class="text-muted">note: this starts additional servers if the current one is full</small></label>
                    <input type="number" class="form-control {{ $errors->has('max_players') ? 'is-invalid' : '' }}" id="maxPlayersInput" name="max_players" :value="old('max_players')" required>
                    @error('max_players')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="fileInput">file</label>
                    <input type="file" class="form-control-file" name="fileupload" id="fileInput">
                    @error('fileupload')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="thumbnailInput">thumbnail</label>
                    <input type="file" class="form-control-file" name="thumbnail" id="thumbnailInput">
                    @error('fileupload')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            <div class="form-group mb-3">
                <input type="checkbox" name="gearsEnabled" class="form-check-input ml-2" id="gearsEnabled">
                <label class="form-check-label ml-5" for="gearsEnabled">Gears allowed <i class="far fa-question-circle ml-2" value="0" data-toggle="tooltip" data-placement="top" title="Whether or not the player will spawn with gears. Useful for games that can be ruined by gears"></i></label>
            </div>

                <button class="btn btn-lg btn-success w-100" type="submit">create</button>

            </form>
        </x-card>

        @endif


    </div>
</x-app-layout>
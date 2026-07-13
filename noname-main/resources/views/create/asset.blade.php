@section('title', 'Create New Asset')
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'create new asset'">

            <p class="text-muted">It costs 5 <x-peep-icon size="16" spacing="yep"/> to create clothing. For decals and models, it costs 1 <x-peep-icon size="16" spacing="yep"/>. Audios are free. You will <i>not</i> get refunded if your asset is moderated.</p>

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

        <form action="/app/upload-asset" method="POST" enctype="multipart/form-data">
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

            <div id="priceWrapper" class="mb-3 form-group" style="display: none;">
                <label for="priceInput">price <small class="text-muted">only for clothing</small></label>
                <input type="number" class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" id="priceInput" name="price" :value="old('price')">
                <small id="priceHelp" class="form-text text-muted">your price must be between 0 peeps and 50 peeps</small>
                @error('price')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3 form-group">
                <label for="typeInput">type</label>
                <select class="form-control" name="type" id="typeInput" onchange="togglePriceInput()">
                    <option value="a" selected>audio</option>
                    <option value="m">model</option>
                    <option value="d">decal</option>
                    <option value="ts">t-shirt</option>
                    <option value="s">shirt</option>
                    <option value="p">pants</option>
                    <option value="sm">mesh</option>
                </select>
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

            <button class="btn btn-lg btn-success w-100" type="submit">create</button>

        </form>
        </x-card>
    </div>

    <script>
        function togglePriceInput() {
            const typeInput = document.getElementById('typeInput').value;
            const priceWrapper = document.getElementById('priceWrapper');

            if (typeInput === 'ts' || typeInput === 's' || typeInput === 'p') {
                priceWrapper.style.display = 'block';
            } else {
                priceWrapper.style.display = 'none';
            }
        }

        window.onload = function() {
            togglePriceInput();
        }
    </script>
</x-app-layout>

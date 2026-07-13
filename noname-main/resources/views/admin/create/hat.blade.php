@section('title', 'Create New Asset')
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'create new asset'">

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

        <form action="/app/admin/create/hat" method="POST" enctype="multipart/form-data">
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

            <div id="priceWrapper" class="mb-3 form-group">
                <label for="priceInput">price <small class="text-muted">only for clothing</small></label>
                <input type="number" class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" id="priceInput" name="price" :value="old('price')">
                <small id="priceHelp" class="form-text text-muted">your price must be at least 1 peeps and under 50 peeps</small>
                @error('price')
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

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" value="0" name="for2012" id="checkTwelve">
                <label class="form-check-label" for="checkTwelve">Works on the 2012 client <i class="far fa-question-circle ml-2" value="0" data-toggle="tooltip" data-placement="top" title="Exclude this item from 2012 to prevent gameserver crashes. Be sure!"></i></label>
            </div>

            <button class="btn btn-lg btn-success w-100" type="submit">create</button>

        </form>
        </x-card>
    </div>


</x-app-layout>

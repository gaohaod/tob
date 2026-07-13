<x-app-layout>
    <div class="container mt-5">

                   @error('video')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @enderror

        <x-card title="Upload a Video">
            <p class="text-muted">Upload a video to the public Video Service.</p>

            <form action="/app/admin/create-video" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-2">
                    <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" placeholder="Name of the video" name="title" value="{{ old('title') }}">
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group mb-2">
                    <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" placeholder="Description here" name="description" value="{{ old('description') }}">
                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group mb-2">
                    <input type="file" name="video">
 
                </div>

                <button class="btn btn-success btn-lg">Upload the Video</button>
            </form>
        </x-card>
    </div>
</x-app-layout>

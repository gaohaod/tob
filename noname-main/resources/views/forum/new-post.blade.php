@section('title', 'Reply')
<x-app-layout>
    <div class="container mt-5">

        <button class="btn btn-success w-100 mb-2" onclick="history.back()">back</button>
    
        <x-card :title="'new post'">
            <form action="/app/forum/new/{{ $category_id }}" method="POST">
            @csrf

                <div class="mb-3 form-group">
                    <label for="subjectInput">subject</label>
                        <input type="text" class="form-control {{ $errors->has('subject') ? 'is-invalid' : '' }}" id="subjectInput" name="subject" :value="old('subject')" required>
                        @error('subject')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                </div>

                <div class="mb-3 form-group">
                    <label for="bodyInput">body</label>
                    <textarea type="text" class="form-control {{ $errors->has('body') ? 'is-invalid' : '' }}" id="bodyInput" name="body" :value="old('body')" required></textarea>
                    @error('body')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button class="btn btn-success w-100 btn-lg"><i class="far fa-plus mr-3"></i> post</button>

            </form>
        </x-card>
    </div>

    <script src="/functions.js"></script>
</x-app-layout>
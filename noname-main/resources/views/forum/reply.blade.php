@section('title', 'Reply')
<x-app-layout>
    <div class="container mt-5">

        <button class="btn btn-success w-100 mb-2" onclick="history.back()">back</button>
    
        <x-card :title="'Reply to ' . $post->subject">
            @if ($post->locked)
            <div class="text-center my-5">
                <img src="/images/sweat.png" alt="Sweating blob" width="100" class="mb-3">

                <h3 class="font-weight-light">This thread is locked.</h3>
                <h6 class="font-weight-regular text-muted mb-4">You cannot reply to it anymore.</h6>
            </div>
            @else
            <form action="/app/forum/reply/{{ $post->id }}" method="POST">
            @csrf

                <div class="mb-3 form-group">
                    <label for="replyInput">reply</label>
                    <textarea type="text" class="form-control {{ $errors->has('reply') ? 'is-invalid' : '' }}" id="replyInput" name="reply" :value="old('reply')" required></textarea>
                    @error('reply')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button class="btn btn-success w-100 btn-lg"><i class="far fa-plus mr-3"></i> post</button>

            </form>
            @endif
        </x-card>
    </div>
</x-app-layout>
@section('title', $category_name)
<x-app-layout>
   <div class="container mt-5">

    @if ($category_id == 5)
    <div class="card card-body border-danger mb-3">
        <div class="row">
            <div class="col-auto">
                <h1 class="ml-2">
                    <i class="far fa-ban text-danger"></i>
                </h1>
            </div>

            <div class="col">
                <h3 class="fw-bolder">This subforum is for serious discussion only.</h3>
                <h6 class="fw-regular text-muted mb-0">Offender's posts will be removed if they are not serious/on-topic</h6>
            </div>
        </div>
    </div>
    @endif

      <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">{{ config('app.name', 'Laravel') }}</a></li>
            <li class="breadcrumb-item"><a href="/app/forum">Forum</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category_name }}</li>
         </ol>
      </nav>
    <button class="btn btn-success w-100 mb-3" onclick="document.location = '/app/forum/new/{{ $category_id }}'">
        <i class="far fa-plus mr-2"></i>new post
    </button>


      <x-card :title="$category_name" :haspadding="'nope'">
        <table class="table mb-0">
            <tbody>
                @if ($posts->isEmpty())
                    <p class="text-center text-muted mt-3">No one's around to talk.</p>
                @else
                    @foreach ($posts as $post)
                        <tr>
                            <td class="text-left">
                                <a href="/app/forum/view/{{ $post->id }}">
                                    @if ($post->banned == 1)
                                    <span class="text-danger">{{ $post->subject }} <i class="far fa-ban ml-2"></i></span>
                                    @else
                                    {{ $post->subject }} {!! $post->locked ? "<i class='far fa-lock text-danger ml-2'></i>" : "" !!}
                                    @endif

                                    @if ($post->pinned == 1)
                                    <i class="far fa-thumbtack text-danger ml-2"></i>
                                    @endif
                                </a>
                                <p class="text-muted mb-0 fw-regular">{{ $post->replies->count() }} replies @if (!$post->banned) <span class="mx-2">|</span> {{ $post->votes }} votes @endif</p>
                            </td>
                            <td class="text-right">
                                <a href="/app/user/{{ $post->user->id }}">{{ $post->user->name }}</a>
                                <p class="text-muted mb-0 fw-regular">{{ $post->created_at->format('M j') }}</p>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
      </x-card>

      <div class="mt-3 d-flex justify-content-center w-100">
            {{ $posts->links() }}
    </div>

   </div>
</x-app-layout>
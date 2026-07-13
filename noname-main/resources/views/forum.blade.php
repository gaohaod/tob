@section('title', 'Forum')
<x-app-layout>
    <div class="container mt-5">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">{{ config('app.name', 'Laravel') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Forum</li>
            </ol>
        </nav>

        <x-card :title="'Forum Home'"  :haspadding="'nope'">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Posts</th>
                        <th scope="col">Last Post</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row"><a href="/app/forum/0">Announcements</a></th>
                        <td class="text-muted">Announcements about {{ config('app.name', 'Laravel') }}.</td>
                        <td>{{ $announcements_post_count }}</td>
                        <td class="text-right">
                        @if (!$announcements)
                            <strong class="text-truncate">No Post</strong><br>
                            <i class="text-muted">Nobody</i>
                            @else
                            <strong class="text-truncate">{{ $announcements->subject }}</strong><br>
                            <i class="text-muted">{{ $announcements->user->name }}</i>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><a href="/app/forum/1">General</a></th>
                        <td class="text-muted">For generic topics related to {{ config('app.name', 'Laravel') }}.</td>
                        <td>{{ $general_post_count }}</td>
                        <td class="text-right">
                        @if (!$general)
                            <strong class="text-truncate">No Post</strong><br>
                            <i class="text-muted">Nobody</i>
                            @else
                            <strong class="text-truncate">{{ $general->subject }}</strong><br>
                            <i class="text-muted">{{ $general->user->name }}</i>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><a href="/app/forum/2">Off-Topic</a></th>
                        <td class="text-muted">For off-topic purposes. Self-explanatory</td>
                        <td>{{ $off_topic_post_count }}</td>
                        <td class="text-right">
                        @if (!$off_topic)
                            <strong class="text-truncate">No Post</strong><br>
                            <i class="text-muted">Nobody</i>
                            @else
                            <strong class="text-truncate">{{ $off_topic->subject }}</strong><br>
                            <i class="text-muted">{{ $off_topic->user->name }}</i>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><a href="/app/forum/3">Development/Scripting</a></th>
                        <td class="text-muted">Have an issue with a script? Come here and (hopefully) someone will help you!</td>
                        <td>{{ $dev_post_count }}</td>
                        <td class="text-right">
                        @if (!$dev)
                            <strong class="text-truncate">No Post</strong><br>
                            <i class="text-muted">Nobody</i>
                            @else
                            <strong class="text-truncate">{{ $dev->subject }}</strong><br>
                            <i class="text-muted">{{ $dev->user->name }}</i>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><a href="/app/forum/4">Help</a></th>
                        <td class="text-muted">Report bugs, or issues with {{ config('app.name', 'Laravel') }}'s client/launcher? Ask here and a developer will help you!</td>
                        <td>{{ $help_post_count }}</td>
                        <td class="text-right">
                            
                        @if (!$help)
                            <strong class="text-truncate">No Post</strong><br>
                            <i class="text-muted">Nobody</i>
                            @else
                            <strong class="text-truncate">{{ $help->subject }}</strong><br>
                            <i class="text-muted">{{ $help->user->name }}</i>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><a href="/app/forum/5">Politics</a></th>
                        <td class="text-muted">Joe birden came to my house and bit me in the ass!!!</td>
                        <td>{{ $politics_post_count }}</td>
                        <td class="text-right">
                            @if (!$politics)
                            <strong class="text-truncate">No Post</strong><br>
                            <i class="text-muted">Nobody</i>
                            @else
                            <strong class="text-truncate">{{ $politics->subject }}</strong><br>
                            <i class="text-muted">{{ $politics->user->name }}</i>
                            @endif

                        </td>
                    </tr>
                </tbody>
            </table>
        </x-card>
    </div>
</x-app-layout>
@extends('layouts.app')

@section('content')
    <div class="justify-content-center align-items-center">


        <h1 class="mt-4">{{ $user->username }}'s Latests Comments </h1>

        @if ($user->comments->count() === 0)
            <h2 class="text-center mt-5">{{ $user->username }} hasn't made any comments</h2>
        @else
            <div class="mt-4 mb-3 container">
                <a class="mb-5 w-100 btn btn-outline-secondary" href="/profile/{{ $user->username }}">Go back to profile</a>

                @foreach ($comments as $comment)
                    @include('partials.comment_item', ['comment' => $comment, 'showPostLink' => true])
                @endforeach
            </div>
        @endif


    </div>
@endsection

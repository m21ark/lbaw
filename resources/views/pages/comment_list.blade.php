@extends('layouts.app')

@section('content')
    <div class="justify-content-center align-items-center">

        @if (Auth::user()->username !== $user->username)
            <h1 class="mt-4"><a href="/profile/{{ $user->username }}">{{ $user->username }}</a> 's Latests Comments </h1>
        @else
            <h1 class="mt-3">My Latest Comments</h1>
        @endif


        @if ($user->comments->count() === 0)
            <h2 class="text-center mt-5">{{ $user->username }} hasn't made any comments</h2>
        @else
            <div class="mt-4 mb-3 container">
                @foreach ($comments as $comment)
                    @include('partials.comment_item', ['comment' => $comment, 'showPostLink' => true])
                @endforeach
            </div>
        @endif


    </div>
@endsection

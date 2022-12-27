@extends('layouts.app')

@section('page_title', $user->username.'`s Like List')

@section('content')
    <div class="justify-content-center align-items-center">

        @if (Auth::user()->username !== $user->username)
            <h1 class="mt-4"><a href="/profile/{{ $user->username }}">{{ $user->username }}</a> 's Latests Likes </h1>
        @else
            <h1 class="mt-3">My Latest Likes</h1>
        @endif

        @if ($posts->count() + $comments->count() === 0)
            <h2 class="text-center mt-5">{{ $user->username }} hasn't liked anything</h2>
        @else
            <div class="mt-4 mb-3 container">

                <div class="d-flex justify-content-center mb-3">
                    <h4 class="me-3">Show Posts</h4>
                    <label class="switch">
                        <input type="checkbox" id="list_toggle_btn">
                        <span class="slider round"></span>
                    </label>
                    <h4 class="ms-3">Show Comments</h4>
                </div>

                <div id="toggle_list_A">
                    <h2>Posts</h2>
                    @foreach ($posts as $post)
                        @include('partials.post_item', ['post' => $post])
                    @endforeach
                </div>

                <div hidden id="toggle_list_B">

                    <h2 class="mb-5">Comments</h2>
                    @foreach ($comments as $comment)
                        @include('partials.comment_item', ['comment' => $comment, 'showPostLink' => true])
                    @endforeach
                </div>

            </div>
        @endif


    </div>
@endsection

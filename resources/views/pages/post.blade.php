@extends('layouts.app')

@section('content')
    <div id="timeline">
        @include('partials.post_item', ['post' => $post, 'showComments' => true])
    </div>

    <!-- Temporary placement -->
    @include('partials.popup.make_report_popup')

@endsection

@section('rightbar')
    <div id="post_comment_section" class="bg-light p-3 ">

        <h3 class="mt-4 mb-4">Comment Section</h3>

        @include('partials.popup.edit_comment_popup')

        @if (sizeof($post->comments) > 0)
            @include('partials.comment_section', ['comments' => $post->comments])
        @else
            <p>No comments yet</p>
        @endif
    </div>

    @include('partials.popup.edit_post_popup', ['post' => $post])
@endsection

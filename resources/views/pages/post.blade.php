@extends('layouts.app')

@section('content')
    <div id="timeline">
        @include('partials.post_item', ['post' => $post, 'showComments' => true])
    </div>
@endsection

@section('rightbar')
    <div id="post_comment_section" class="bg-light p-3 ">

        <h3 class="mt-4 mb-4">Comment Section</h3>

        @include('partials.popup.edit_comment_popup')

        @include('partials.comment_section', ['comments' => $post->comments])
    </div>

    @isset($showComments)
        <!-- TODO: Maybe not very smart to render for each post -->
        @include('partials.popup.edit_post_popup', ['post' => $post])
    @endisset
@endsection



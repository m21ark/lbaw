@foreach ($comments as $comment)
    @if ($comment->id_parent !== null)
        @continue
    @endif

    @include('partials.comment_item', ['comment' => $comment])
@endforeach

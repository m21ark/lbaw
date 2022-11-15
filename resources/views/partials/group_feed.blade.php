<div id="timeline">

    <!-- TODO make posts for groups and make POST_ITEM receive the post correctly  -->
    @foreach ($group->posts as $post)
        @include('partials.post_item', ['post' => $post]);
    @endforeach

    <!-- PLACEHOLDER -->
    @include('partials.post_item')

</div>

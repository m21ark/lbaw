<div id="timeline">

    @if ($group->posts->isEmpty())
        <h2 class="text-center mt-5">No posts yet</h2>
    @endif

    <!-- TODO make posts for groups and make POST_ITEM receive the post correctly  -->
    @foreach ($group->posts as $post)
        @include('partials.post_item', ['post' => $post])
    @endforeach

</div>

@include('partials.popup.make_post_popup', [
    'popup_id' => 'popup_show_group_post',
    'group_name' => $group->name,
])

@include('partials.popup.edit_group_popup', ['group' => $group])

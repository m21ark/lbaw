<div class="list-group list-group-checkable form-check d-flex p-1 flex justify-content-between text-bg-light"
    id="feed_filter">

    <input class="list-group-item-check pe-none" onclick="updateFeed('Posts')" type="radio" name="feed_filter"
        id="feed_radio_posts" value="" checked="">
    <label class="list-group-item rounded-3 " for="feed_radio_posts">
        Posts
    </label>

    <input class="list-group-item-check pe-none" onclick="updateFeed('Comments')" type="radio" name="feed_filter"
        id="feed_radio_comments" value="">
    <label class="list-group-item rounded-3 " for="feed_radio_comments">
        Comments
    </label>

    <input class="list-group-item-check pe-none" onclick="updateFeed('Groups')" type="radio" name="feed_filter"
        id="feed_radio_groups" value="">
    <label class="list-group-item rounded-3 " for="feed_radio_groups">
        Groups
    </label>

    <input class="list-group-item-check pe-none" onclick="updateFeed('Likes')" type="radio" name="feed_filter"
        id="feed_radio_likes" value="">
    <label class="list-group-item rounded-3 " for="feed_radio_likes">
        Likes
    </label>

</div>


<div id="timeline">


    @if ($friends || $user->visibility || $user->id === Auth::user()->id)
        @if ($user->posts->isEmpty())
            <h2 class="text-center mt-5">No posts yet</h2>
        @endif
        @foreach ($user->posts as $post)
            @include('partials.post_item', ['post' => $post])
        @endforeach
    @else

        <h2 class="mb-5"> <i class="fa-solid fa-lock fa-2x me-4 mt-5"></i>This account is private</h2>
        <p style="font-size: 1.3em">Make a friend request to access this profile content</p>
        <img src="/NotFriends.jpg" class="img-fluid mt-4" alt="You are not friends. Please make a friends request">
    @endif
</div>

<!-- Edit Profile Popup -->
@include('partials.popup.edit_profile_popup', ['user' => $user])

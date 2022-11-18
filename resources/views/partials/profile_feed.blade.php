<div id="feed_filter" class="form-check d-flex p-3 flex justify-content-between text-bg-light">


    <div class="form-check">
        <input type="radio" class="form-check-input" onclick="updateFeed('for_you')" name="feed_filter"
            id="feed_radio_foryou">Posts
        <label class="form-check-label"></label>
    </div>

    <div class="form-check">
        <input type="radio" class="form-check-input" onclick="updateFeed('viral')" name="feed_filter"
            id="feed_radio_viral">Comments
        <label class="form-check-label"></label>
    </div>

    <div class="form-check">
        <input type="radio" class="form-check-input" onclick="updateFeed('friends')" name="feed_filter"
            id="feed_radio_friends">Groups
        <label class="form-check-label"></label>
    </div>

    <div class="form-check">
        <input type="radio" class="form-check-input" onclick="updateFeed('groups')" name="feed_filter"
            id="feed_radio_groups">Likes
        <label class="form-check-label"></label>
    </div>

</div>





<div id="timeline">
    @foreach ($user->posts as $post)
        @include('partials.post_item', ['post' => $post])
    @endforeach

</div>

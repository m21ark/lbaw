<div class="list-group list-group-checkable form-check d-flex p-3 flex justify-content-between text-bg-light"
    id="feed_filter">

    <input class="list-group-item-check pe-none" onclick="updateFeed('for_you')" type="radio" name="feed_filter"
        id="feed_radio_foryou" value="" checked>
    <label class="list-group-item rounded-3 py-3" for="feed_radio_foryou">
        User
    </label>

    <input class="list-group-item-check pe-none" onclick="updateFeed('viral')" type="radio" name="feed_filter"
        id="feed_radio_viral" value="">
    <label class="list-group-item rounded-3 py-3" for="feed_radio_viral">
        Group
    </label>

    <input class="list-group-item-check pe-none" onclick="updateFeed('friends')" type="radio" name="feed_filter"
        id="feed_radio_friends" value="">
    <label class="list-group-item rounded-3 py-3" for="feed_radio_friends">
        Post
    </label>

    <input class="list-group-item-check pe-none" onclick="updateFeed('groups')" type="radio" name="feed_filter"
        id="feed_radio_groups" value="">
    <label class="list-group-item rounded-3 py-3" for="feed_radio_groups">
        Comment
    </label>
</div>





<div id="timeline">
    <!-- TODO: Add results here -->
</div>

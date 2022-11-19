<div class="list-group list-group-checkable form-check d-flex p-3 flex justify-content-between text-bg-light"
    id="feed_filter">

    <input class="list-group-item-check pe-none" onclick="updateFeed('for_you')" type="radio" name="feed_filter"
        id="search_radio_user" value="" checked>
    <label class="list-group-item rounded-3 py-3" for="search_radio_user">
        User
    </label>

    <input class="list-group-item-check pe-none" onclick="updateFeed('viral')" type="radio" name="feed_filter"
        id="search_radio_group" value="">
    <label class="list-group-item rounded-3 py-3" for="search_radio_group">
        Group
    </label>

    <input class="list-group-item-check pe-none" onclick="updateFeed('groups')" type="radio" name="feed_filter"
        id="search_radio_post" value="">
    <label class="list-group-item rounded-3 py-3" for="search_radio_post">
        Post
    </label>
</div>


<div id="timeline">
    <!-- TODO: Add results here -->
</div>

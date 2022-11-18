<div id="feed_filter" class="form-check d-flex p-3 flex justify-content-between text-bg-light">

    <div class="form-check">
        <input type="radio" class="form-check-input" onclick="updateFeed('for_you')" name="feed_filter"
            id="feed_radio_foryou">User
        <label class="form-check-label"></label>
    </div>

    <div class="form-check">
        <input type="radio" class="form-check-input" onclick="updateFeed('viral')" name="feed_filter"
            id="feed_radio_viral">Group
        <label class="form-check-label"></label>
    </div>

    <div class="form-check">
        <input type="radio" class="form-check-input" onclick="updateFeed('friends')" name="feed_filter"
            id="feed_radio_friends">Post
        <label class="form-check-label"></label>
    </div>

    <div class="form-check">
        <input type="radio" class="form-check-input" onclick="updateFeed('groups')" name="feed_filter"
            id="feed_radio_groups">Comment
        <label class="form-check-label"></label>
    </div>

</div>




<div id="timeline">
    <!-- TODO: Add results here -->
</div>

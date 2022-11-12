<div id="feed_filter">
    <h3>Filter Results:</h3>
    <label for="feed_radio_user">User</label>
    <input type="radio" name="feed_filter" id="feed_radio_user" checked>

    <label for="feed_radio_group">Group</label>
    <input type="radio" name="feed_filter" id="feed_radio_group">

    <label for="feed_radio_post">Post</label>
    <input type="radio" name="feed_filter" id="feed_radio_post">

    <label for="feed_radio_comment">Comment</label>
    <input type="radio" name="feed_filter" id="feed_radio_comment">
</div>


<div id="timeline">
    <!-- TODO: Add posts here -->
    @for ($i = 0; $i < 5; $i++)
        @include('partials.post_item');
    @endfor
</div>

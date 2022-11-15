<div id="feed_filter">

    <label for="feed_radio_viral">Viral</label>
    <input type="radio" onclick="updateFeed('viral')" name="feed_filter" id="feed_radio_viral" checked>

    <label for="feed_radio_foryou">For you</label>
    <input type="radio" name="feed_filter" id="feed_radio_foryou">
    @if (Auth::check()) 
    <label for="feed_radio_friends">Friends</label>
    <input type="radio" name="feed_filter" id="feed_radio_friends">

    <label for="feed_radio_groups">Groups</label>
    <input type="radio" name="feed_filter" id="feed_radio_groups">
    
    @endif
    
</div>


<div id="timeline">
    <!-- TODO: Add posts here -->
    
</div>

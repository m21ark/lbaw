@if (Auth::check()) 
<div id="feed_filter">
    
    <label for="feed_radio_viral">Viral</label>
    <input type="radio" onclick="updateFeed('viral')" name="feed_filter" id="feed_radio_viral">

    <label for="feed_radio_foryou">For you</label>
    <input type="radio" onclick="updateFeed('for_you')" name="feed_filter" id="feed_radio_foryou">
    
    <label for="feed_radio_friends">Friends</label>
    <input type="radio" onclick="updateFeed('friends')" name="feed_filter" id="feed_radio_friends">

    <label for="feed_radio_groups">Groups</label>
    <input type="radio" onclick="updateFeed('groups')" name="feed_filter" id="feed_radio_groups">
    
</div>
@endif

<div id="timeline">
    <!-- TODO: Add posts here -->
    
</div>

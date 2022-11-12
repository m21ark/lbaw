
        <div id="feed_head">
            <p id="bread_crumbs">Home > Feed</p>
        </div>
        <div id="feed_filter">
            <label for="feed_radio_foryou">For you</label>
            <input type="radio" name="feed_filter" id="feed_radio_foryou" checked>

            <label for="feed_radio_friends">Friends</label>
            <input type="radio" name="feed_filter" id="feed_radio_friends">

            <label for="feed_radio_groups">Groups</label>
            <input type="radio" name="feed_filter" id="feed_radio_groups">
        </div>


        <div id="timeline">

                @for($i=0;$i<10;$i++)
                    @include('partials.post_item');
                @endfor
        </div>


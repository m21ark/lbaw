@auth
    <div class="d-flex">

        <div class="list-group list-group-checkable form-check d-flex p-1 flex justify-content-between align-items-center flex-sm-fill text-bg-light"
            id="feed_filter">

            <input class="list-group-item-check pe-none" onclick="updateFeed('for_you')" type="radio" name="feed_filter"
                id="feed_radio_foryou" value="for_you">
            <label class="list-group-item rounded-3" for="feed_radio_foryou">
                For you
            </label>

            <input class="list-group-item-check pe-none" onclick="updateFeed('viral')" type="radio" name="feed_filter"
                id="feed_radio_viral" value="viral" checked>
            <label class="list-group-item rounded-3" for="feed_radio_viral">
                Viral
            </label>

            <input class="list-group-item-check pe-none" onclick="updateFeed('friends')" type="radio" name="feed_filter"
                id="feed_radio_friends" value="friends">
            <label class="list-group-item rounded-3" for="feed_radio_friends">
                Friends
            </label>

            <input class="list-group-item-check pe-none" onclick="updateFeed('groups')" type="radio" name="feed_filter"
                id="feed_radio_groups" value="groups">
            <label class="list-group-item rounded-3" for="feed_radio_groups">
                Groups
            </label>

            
        </div>

        <div class="orderFilter dropdown" style="width:8em" >
            <button class="btn dropdownPostButton" type="button">&#9776;</button>
            <div class="dropdown_menu" style="z-index: 200000" hidden>
                
                <input class="list-group-item-check pe-none feed-order" type="radio" name="feed_order"
                    id="feed_radio_order_likes" onclick="" value="likes" checked>
                <label class="list-group-item rounded-3" for="feed_radio_order_likes">
                    Order by Likes
                </label>

                <input class="list-group-item-check pe-none feed-order" type="radio" name="feed_order"
                    id="feed_radio_order_date" onclick="" value="date">
                <label class="list-group-item rounded-3" for="feed_radio_order_date">
                    Order by Date
                </label>
                
            </div>
        </div>
    
    </div>

@endauth


<div id="timeline" class="mt-3">
    <!-- Add posts here -->
</div>

@auth
    <div class="d-flex text-bg-light w-100" style="max-width:55em;margin:auto">

        <div style="width: 2em"></div>

        <div class="list-group list-group-checkable form-check d-flex p-1 flex justify-content-between align-items-center flex-sm-fill"
            id="feed_filter">

            <input class="list-group-item-check pe-none feed-filter" type="radio" name="feed_filter" id="feed_radio_foryou"
                value="for_you">
            <label class="list-group-item rounded-3" for="feed_radio_foryou" style="min-width:6em;">
                For you
            </label>

            <input class="list-group-item-check pe-none feed-filter" type="radio" name="feed_filter" id="feed_radio_viral"
                value="viral" checked>
            <label class="list-group-item rounded-3" for="feed_radio_viral">
                Viral
            </label>

            <input class="list-group-item-check pe-none feed-filter" type="radio" name="feed_filter"
                id="feed_radio_friends" value="friends">
            <label class="list-group-item rounded-3" for="feed_radio_friends">
                Friends
            </label>

            <input class="list-group-item-check pe-none feed-filter" type="radio" name="feed_filter"
                id="feed_radio_groups" value="groups">
            <label class="list-group-item rounded-3" for="feed_radio_groups">
                Groups
            </label>


        </div>

        <div class="ms-4 orderFilter dropdown" style="width:7em;">
            <button class="btn dropdownPostButton" type="button">&#9776;</button>
            <div class="dropdown_menu feed_order_dropdown_btn" style="z-index: 5;position: absolute;" hidden>

                <label class="list-group-item" for="order_by_label">
                    Order by:
                </label>
                <p class="list-group-item-check pe-none feed-order" id="order_by_label"></p>

                <input class="list-group-item-check pe-none feed-order" type="radio" name="feed_order"
                    id="feed_radio_order_popularity" onclick="" value="popularity" checked>
                <label class="list-group-item rounded-1" for="feed_radio_order_popularity">
                    Popularity
                </label>

                <input class="list-group-item-check pe-none feed-order" type="radio" name="feed_order"
                    id="feed_radio_order_date" onclick="" value="date">
                <label class="list-group-item rounded-1" for="feed_radio_order_date">
                    Date
                </label>

                <input class="list-group-item-check pe-none feed-order" type="radio" name="feed_order"
                    id="feed_radio_order_likes" onclick="" value="likes" checked>
                <label class="list-group-item rounded-1" for="feed_radio_order_likes">
                    Like Count
                </label>

            </div>
        </div>

    </div>

@endauth


<div id="timeline" class="mt-3">
    <!-- Add posts here -->
</div>

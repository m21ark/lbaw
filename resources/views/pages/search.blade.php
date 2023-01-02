@extends('layouts.app')

@section('page_title', 'Search')


@section('content')
    <div class="list-group list-group-checkable form-check d-flex p-1 mb-3 flex justify-content-between text-bg-light"
        id="search_filter">

        <input class="list-group-item-check pe-none type-search" type="radio" name="search_filter" id="search_radio_post"
            value="posts" checked>
        <label class="list-group-item rounded-3" for="search_radio_post">
            Posts
        </label>

        <input class="list-group-item-check pe-none type-search" type="radio" name="search_filter" id="search_radio_user" value="users">
        <label class="list-group-item rounded-3" for="search_radio_user">
            Users
        </label>

        <input class="list-group-item-check pe-none type-search" type="radio" name="search_filter" id="search_radio_group"
            value="groups">
        <label class="list-group-item rounded-3" for="search_radio_group">
            Groups
        </label>

        <input class="list-group-item-check pe-none type-search" type="radio" name="search_filter" id="search_radio_topic"
            value="topics">
        <label class="list-group-item rounded-3" for="search_radio_topic">
            Topics
        </label>


        <div id="order_filter_dropdown" class="orderFilter dropdown" style="width:7em;">
            <button class="btn dropdownPostButton" type="button">&#9776;</button>
            <div class="dropdown_menu search_order_dropdown_btn" style="z-index: 5;position: absolute; width:36em;" hidden>
                <input class="list-group-item-check pe-none search-order" type="radio" name="search_order"
                        id="search_radio_order_match" value="match" checked>          
                
                <label class="list-group-item rounded-1" for="search_radio_order_match">
                    Best Match
                </label>
                
                <input class="list-group-item-check pe-none search-order" type="radio" name="search_order"
                    id="search_radio_order_date" value="date">

                <label class="list-group-item rounded-1" for="search_radio_order_date">
                    Most Recent
                </label>
                
                <input class="list-group-item-check pe-none search-order" type="radio" name="search_order"
                    id="search_radio_order_likes" value="likes">

                <label class="list-group-item rounded-1" for="search_radio_order_likes">
                    Most Likes
                </label>

                <input class="list-group-item-check pe-none search-order" type="radio" name="search_order"
                    id="search_radio_order_comments" value="comments">

                <label class="list-group-item rounded-1" for="search_radio_order_comments">
                    Most Comments
                </label>
          
            </div>
        </div>

    </div>


    <div id="timeline" class="search_timeline mt-3">
        <!-- Add results here -->

    </div>
@endsection

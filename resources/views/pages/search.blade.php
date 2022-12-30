@extends('layouts.app')

@section('page_title', 'Search')


@section('content')
    <div class="list-group list-group-checkable form-check d-flex p-1 mb-3 flex justify-content-between text-bg-light"
        id="search_filter">

        <input class="list-group-item-check pe-none" type="radio" name="search_filter" id="search_radio_user" value="users">
        <label class="list-group-item rounded-3" for="search_radio_user">
            Users
        </label>

        <input class="list-group-item-check pe-none" type="radio" name="search_filter" id="search_radio_group"
            value="groups">
        <label class="list-group-item rounded-3" for="search_radio_group">
            Groups
        </label>

        <input class="list-group-item-check pe-none" type="radio" name="search_filter" id="search_radio_post"
            value="posts" checked>
        <label class="list-group-item rounded-3" for="search_radio_post">
            Posts
        </label>

        <input class="list-group-item-check pe-none" type="radio" name="search_filter" id="search_radio_topic"
            value="topics">
        <label class="list-group-item rounded-3" for="search_radio_topic">
            Topics
        </label>
    </div>


    <div id="timeline" class="search_timeline d-flex flex-wrap justify-content-center align-items-center">
        <!-- TODO: Add results here -->

    </div>
@endsection

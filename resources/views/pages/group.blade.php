@extends('layouts.app')

@section('page_title', $group->name.'`s Group')

@section('content')
    <h1 class="mt-3">Group Page</h1>

    <a style="visibility: hidden" class="float shadow-lg" id="list_toggle_btn_div">
        <label for="list_toggle_btn"><i class="fa fa-bars my-float"></i></label>
        <input style="display: none" type="checkbox" id="list_toggle_btn">
    </a>

    <div id="toggle_list_A">
        @include('partials.group_feed')
    </div>
@endsection


@section('rightbar')
    <div id="toggle_list_B">
        @include('partials.sidebar.group_sidebar')
    </div>
@endsection

@extends('layouts.app')

@section('page_title', $group->name . '`s Group')

@section('content')
    <h1 class="mt-3">Group Page</h1>

    <div style="visibility: hidden" class="float shadow-lg" id="list_toggle_btn_div">
        <label for="list_toggle_btn"><i class="fa fa-bars my-float"></i></label>
        <input style="display: none" type="checkbox" id="list_toggle_btn">
    </div>

    <div id="toggle_list_A">
        @if ($can_view_timeline)
            @include('partials.group_feed')
        @else
            <h2 class="mt-5"> <i class="fa-solid fa-lock"></i> This group is private </h2>
            <h3 class="mt-3">Join group to see content</h3>
        @endif
    </div>
@endsection


@section('rightbar')
    <div id="toggle_list_B">
        @include('partials.sidebar.group_sidebar')
    </div>
@endsection

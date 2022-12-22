@extends('layouts.app')






@section('content')
    <h1 class="mt-3">Group Page</h1>

    <a style="visibility: hidden" class="float shadow-lg" id="report_toggle_div">
        <label for="report_toggle"><i class="fa fa-bars my-float"></i></label>
        <input style="display: none" type="checkbox" id="report_toggle">
    </a>

    <div id="pendent_report_list">
        @include('partials.group_feed')
    </div>
@endsection


@section('rightbar')
    <div id="past_report_list">
        @include('partials.sidebar.group_sidebar')
    </div>
@endsection

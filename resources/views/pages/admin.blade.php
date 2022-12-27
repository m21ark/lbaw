@extends('layouts.app')

@section('page_title', 'Admin Console')

@section('content')
    <h2 class="mt-4 mb-4">Admin Console<a href="/admin/statistics" class="ms-3 btn btn-outline-primary mt-3 mb-3 enc"><i
                class="fa-solid fa-bar-chart me-2"></i>Statistics</a></h2>

    <div class="position-absolute d-flex" style="right:3%">
        <h4 class="me-3">Pendent Reports</h4>
        <label class="switch">
            <input type="checkbox" id="list_toggle_btn">
            <span class="slider round"></span>
        </label>
        <h4 class="ms-3">Past Reports</h4>
    </div>

    <div id="toggle_list_A">
        <div class="list-group d-flex mb-5 flex-wrap">
            <h3 class="mt-2">Pendent Reports</h3>
            <div class="d-flex align-items-center">
                <h5 class="me-4">Search reports:</h5>
                <div class="header_searchbar mb-3 mt-3">
                    <input id="searchBarPendent" type="search" class="form-control text-bg-light" placeholder="Search"
                        aria-label="Search">
                </div>
            </div>

            <div id="users-reported-pendent" class="list-group  d-flex mb-3 flex-wrap ">
                <!-- Reports Here -->
            </div>
        </div>
    </div>

    <div hidden id="toggle_list_B">
        <div class="list-group d-flex mb-5 flex-wrap">
            <h3 class="mt-2">Past Reports</h3>
            <div class="d-flex align-items-center">
                <h5 class="me-4">Search reports:</h5>
                <div class="header_searchbar mb-3 mt-3">
                    <input id="searchBarPast" type="search" class="form-control text-bg-light" placeholder="Search"
                        aria-label="Search">
                </div>
            </div>

            <div id="users-reported-past" class="list-group  d-flex mb-5 flex-wrap ">
                <!-- Reports Here -->
            </div>

        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    @include('partials.home_timeline')
@endsection

@if (Auth::check())
    @section('rightbar')
        @include('partials.home_sidebar')
    @endsection
@else 
    @section('rightbar')
        @include('partials.guest_sidebar')
    @endsection
@endif 
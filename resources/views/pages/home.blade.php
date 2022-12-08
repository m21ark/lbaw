@extends('layouts.app')

@if (Auth::check())
    @section('content')
        @include('partials.home_timeline', ['guest' => false])
    @endsection
@else
    @section('content')
        @include('partials.home_timeline', ['guest' => true])
    @endsection
@endif

@if (Auth::check())
    @section('rightbar')
        @include('partials.sidebar.home_sidebar')
    @endsection
@endif

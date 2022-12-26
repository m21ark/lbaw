@extends('layouts.app')

@section('page_title', 'Home')

@if (Auth::check())
    @section('content')
        @include('partials.home_timeline', ['guest' => false])
    @endsection
@else
    @section('content')
        @include('partials.home_timeline', ['guest' => true])
    @endsection
@endif



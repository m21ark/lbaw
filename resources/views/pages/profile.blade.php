@extends('layouts.app')

@section('page_title', $user->username.'`s Profile')

@section('content')
    @include('partials.profile_feed')
@endsection


@section('rightbar')
    @include('partials.sidebar.profile_sidebar')
@endsection

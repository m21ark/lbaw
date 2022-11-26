@extends('layouts.app')

@section('content')
    @include('partials.profile_feed')
@endsection


@section('rightbar')
    @include('partials.sidebar.profile_sidebar')
@endsection

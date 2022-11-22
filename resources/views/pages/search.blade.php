@extends('layouts.app')



@section('content')
    @include('partials.search_results')
@endsection

@auth 
@section('rightbar')
    @include('partials.home_sidebar')
@endsection
@endauth
@extends('layouts.app')

@section('content')
    @include('partials.post', ['post' => $post])
@endsection

@section('rightbar')
    @include('partials.sidebar.post_sidebar')
@endsection

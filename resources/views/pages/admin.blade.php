@extends('layouts.app')

@section('content')
    @include('partials.admin')
@endsection

<p>
    {{ $reports }}
</p>


@section('rightbar')
    @include('partials.sidebar.admin_sidebar')
@endsection

@extends('layouts.app')

@section('page_title', 'Not Found')

@section('content')
    <h2 class="mt-4 mb-4">Not found</h2>

    <div>
        <p>The page you're looking for doesn't exist...
            We're sorry for any inconvenience.
        </p>

        <img src="/../not_found.jpg" alt="Sad Cat" width="400" class="d-block">

        <a href={{ url('/home') }} class="mt-4 btn btn-outline-secondary">Go back to home</a>
    </div>
@endsection

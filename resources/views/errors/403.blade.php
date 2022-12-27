@extends('layouts.app')

@section('page_title', 'Forbidden')

@section('content')
    <h2 class="mt-4 mb-4">Forbidden</h2>

    <div>
        <p> Sorry, but you are not allowed to enter here.
        </p>

        <img src="https://media.tenor.com/euLKuyD9Bn4AAAAM/dancing-security-guard.gif" alt="Sad Cat" width="400" class="d-block">

        <a href={{ url('/home') }} class="mt-4 btn btn-outline-secondary">Go back to home</a>
    </div>
@endsection
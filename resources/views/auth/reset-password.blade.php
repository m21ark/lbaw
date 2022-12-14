@extends('layouts.app')

@section('content')
    <div class=" p-5 mw-10 d-flex align-items-center justify-content-around">

        <p>Password reset was successful!</p>
        {{ $token }}

    </div>
@endsection

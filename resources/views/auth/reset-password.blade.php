@extends('layouts.app')

@section('page_title', 'Reset Password')

@section('content')
    <div class=" p-5 d-flex align-items-center justify-content-around">

        <body>

            @if ($token === 'invalid_token')
                <div>
                    <h3>Sorry, that token is no longer valid! Please ask for a new password recovery email.</h3>
                    <a href="/forgot-password" class="btn btn-primary mt-4 me-5">Ask for new email</a>
                    <a href="/login" class="btn btn-primary mt-4">Go back to login </a>
                </div>
            @else
                <form method="POST" action="{{ url('/reset_password_with_token') }}"
                    style="width:90%;min-width:20em;max-width: 40em">
                    {{ csrf_field() }}

                    <h1 class="h3 mb-3 font-weight-normal">Password Recovery Page</h1>

                    <fieldset>
                        <legend>Recovery Form</legend>

                        <p>Please provide a new password</p>

                        <p>Password <small>(Required)</small></p>
                        <input type="password" id="inputPassword" class="form-control mb-4" placeholder="Password"
                            name="password" required>

                        <p>Confirm Password <small>(Required)</small></p>
                        <input type="password" id="inputRPassword" class="form-control mb-3" placeholder="Confirm Password"
                            name="password_confirmation" required>

                    </fieldset>

                    <input hidden name="token" value="{{ $token }}">

                    <button class="btn btn-lg btn-primary btn-block mt-4 w-100" type="submit">Change Password</button>

                </form>
            @endif




        </body>

    </div>
@endsection

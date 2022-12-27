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

                    <h1 class="h3 mb-3 font-weight-normal">New Password Page</h1>

                    <fieldset>
                        <legend>Password Form</legend>

                        <p>Please provide a new password</p>

                        <label for="inputPassword" data-toggle="tooltip" data-placement="top"
                            title="Your password should be composed of at least 8 characters with letters, numbers and symbols">Password
                            <small>(Required)</small></label>
                        <input type="password" id="inputPassword" class="form-control mb-4" placeholder="Password"
                            name="password" required>

                        <label for="inputRPassword" data-toggle="tooltip" data-placement="top"
                            title="Repeat the previous password to confirm it">Confirm Password
                            <small>(Required)</small></label>
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

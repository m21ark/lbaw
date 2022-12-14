@extends('layouts.app')

@section('content')
    <div class=" p-5 mw-10 d-flex align-items-center justify-content-around">

        <body class="text-center">

            @if ($token === 'invalid_token')
                <div>
                    <h3>Sorry, that token is no longer valid! Please ask for new password recovery email.</h3>

                    <a href="/forgot-password" class="btn btn-primary mt-4 me-5">Ask for new email</a>
                    <a href="/login" class="btn btn-primary mt-4">Go back to login </a>

                </div>
            @else
                <form method="POST" action="{{ url('/reset_password_with_token') }}">
                    {{ csrf_field() }}

                    <h1 class="h3 mb-3 font-weight-normal">New Password</h1>

                    <p>Please provide a new password</p>

                    <p>Password</p>
                    <input type="password" id="inputPassword" class="form-control mb-4" placeholder="Password"
                        name="password" required>

                    <p>Confirm Password</p>
                    <input type="password" id="inputRPassword" class="form-control mb-3" placeholder="Confirm Password"
                        name="password_confirmation" required>

                    <input hidden name="token" value="{{ $token }}">

                    <button class="btn btn-lg btn-primary btn-block mt-4 w-100" type="submit">Change Password</button>

                </form>
            @endif




        </body>

    </div>
@endsection

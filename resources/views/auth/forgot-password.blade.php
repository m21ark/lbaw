@extends('layouts.app')

@section('page_title', 'Forgot Password')

@section('content')
    <div class=" p-5 mw-10 d-flex align-items-center justify-content-around">

        <body>
            <form method="POST" action="{{ url('/reset_password_without_token') }}" style="max-width: 40em">
                {{ csrf_field() }}

                <h1 class="h3 mb-4 mt-5 font-weight-normal">Forgot your password?</h1>


                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <p>If you don't remember your password, you can recover your account by providing the email associated with
                    that account. If the email address provided is linked to an account, an email will be sent to reset the
                    password.
                </p>

                <fieldset>
                    <legend>Email Password Recovery Form</legend>

                    <label for="inputEmail">Email Address*</label>
                    <input type="email" id="inputEmail" class="form-control mb-3" placeholder="Email" name="email" required>

                </fieldset>

                <button class="btn btn-lg btn-primary btn-block mt-4 w-100" type="submit">Send Email</button>


                <div class="mt-3">
                    <p><span class="bold">Go back to </span> <a class="form_button" href="{{ route('login') }}">Login</a>
                    </p>

                </div>
            </form>


    </div>
    </div>
@endsection

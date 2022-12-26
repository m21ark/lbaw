@extends('layouts.app')

@section('content')
    <div class=" d-flex align-items-center justify-content-around">

        <body class="text-center">
            <form method="POST" action="{{ route('login') }}">

                <h1 class="h3 mb-3 font-weight-normal">Login Page</h1>

                {{ csrf_field() }}

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <fieldset>
                    <legend>Login Form</legend>
                    <label for="inputEmail" class="">Email address</label>
                    <input type="email" id="inputEmail" value="{{ old('email') }}" class="form-control mb-3"
                        placeholder="Email" name="email" required autofocus autofocus="">
                    <label for="inputPassword" class="">Password</label>
                    <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="password"
                        required>
                </fieldset>

                <button class="btn btn-lg btn-primary btn-block mt-4 w-100" type="submit">Login</button>

                <!-- TODO: Make better error message -->
                @if ($errors->has('password'))
                    <span class="error">
                        {{ $errors->first('password') }}
                    </span>
                @endif

                <a href="/google_redirect" class="btn btn-outline-primary w-100 mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="30px" height="30px"
                        class="me-4">
                        <path fill="#FFC107"
                            d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z" />
                        <path fill="#FF3D00"
                            d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z" />
                        <path fill="#4CAF50"
                            d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z" />
                        <path fill="#1976D2"
                            d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z" />
                    </svg>Sign in with Google
                </a>


                <div class="mt-4">
                    <p><span class="bold">Don't have an account?</span> <a class="form_button"
                            href="{{ route('register') }}">Register</a></p>

                </div>


                <div class="mt-2">
                    <p><span class="bold">Forgot your password?</span> <a class="form_button"
                            href="{{ route('password.request') }}">Recover</a></p>

                </div>
            </form>


        </body>
    </div>
@endsection

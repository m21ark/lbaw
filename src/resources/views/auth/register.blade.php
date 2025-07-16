@extends('layouts.app')

@section('page_title', 'Register')

@section('content')
    <div class="mw-10 d-flex align-items-center justify-content-around">

        <body class="text-center">
            <form method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}

                <h1 class="h3 mb-3 font-weight-normal">Register Page</h1>

                <fieldset>
                    <legend>Register Form</legend>

                    @if ($errors->has('username'))
                        <label for="inputUsername" class=" mt-2" data-toggle="tooltip" data-placement="top"
                            title="The username should be unique and serve as an identifier for each user. You can change it later.">Username
                            <small>(Required)</small></label>
                        <input type="text" id="inputUsername" value="{{ old('username') }}"
                            class="form-control mb-3 is-invalid" placeholder="Username" name="username" required autofocus>
                        <div class="text-danger">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first('username') }}
                        </div>
                    @else
                        <label for="inputUsername" class=" mt-2" data-toggle="tooltip" data-placement="top"
                            title="The username should be unique and serve as an identifier for each user. You can change it later.">Username
                            <small>(Required)</small></label>
                        <input type="text" id="inputUsername" value="{{ old('username') }}" class="form-control mb-3"
                            placeholder="Username" name="username" required autofocus>
                    @endif


                    @if ($errors->has('email'))
                        <label for="inputEmail" class=" mt-2">Email Address <small>(Required)</small></label>
                        <input type="email" id="inputEmail" class="form-control mb-3 is-invalid" placeholder="Email"
                            name="email" required>
                        <div class="text-danger">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first('email') }}
                        </div>
                    @else
                        <label for="inputEmail" class=" mt-2" data-toggle="tooltip" data-placement="top"
                            title="The email given will be associated with your Nexus account">Email Address
                            <small>(Required)</small></label>
                        <input type="email" id="inputEmail" class="form-control mb-3" placeholder="Email" name="email"
                            required>
                    @endif

                    @if ($errors->has('birthdate'))
                        <label for="inputDate" class=" mt-2" data-toggle="tooltip" data-placement="top"
                            title="You must be above 16 to register in Nexus">Birthdate <small>(Required)</small></label>
                        <input type="date" id="inputDate" class="form-control mb-3 is-invalid" name="birthdate" required>
                        <div class="text-danger">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first('birthdate') }}
                        </div>
                    @else
                        <label for="inputDate" class=" mt-2" data-toggle="tooltip" data-placement="top"
                            title="You must be above 16 to register in Nexus">Birthdate <small>(Required)</small></label>
                        <input type="date" id="inputDate" class="form-control mb-3" name="birthdate" required>
                    @endif

                    <label for="inputPassword" data-toggle="tooltip" data-placement="top"
                        title="Password should be at least 6 characters long" class=" mt-2">Password
                        <small>(Required)</small></label>
                    <input type="password" id="inputPassword" class="form-control mb-3" placeholder="Password"
                        name="password" required>

                    @if ($errors->has('password'))
                        <div class="text-danger">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first('password') }}
                        </div>
                    @endif


                    <label for="inputRPassword" data-toggle="tooltip" data-placement="top"
                        title="Password same as last input" class=" mt-2">Confirm Password
                        <small>(Required)</small></label>
                    <input type="password" id="inputRPassword" class="form-control mb-3" placeholder="Password"
                        name="password_confirmation" required>


                    <label for="inputBio" class=" mt-2" data-toggle="tooltip" data-placement="top"
                        title="Tell about yourself to others. This information will be displayed in your profile">Bio
                        <small>(Required)</small></label>
                    <input type="text" id="inputBio" class="form-control mb-3" placeholder="Tell others about yourself"
                        name="bio" required>

                </fieldset>

                <button class="btn btn-lg btn-primary btn-block mt-4 w-100" type="submit">Register</button>

                <a href="/google_redirect" class="btn btn-outline-primary w-100 mt-4 mb-3">
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

                <div class="mt-3">
                    <p><span class="bold">Already have an account?</span> <a class="form_button"
                            href="{{ route('login') }}">Login</a></p>

                </div>
            </form>


        </body>
    </div>
@endsection

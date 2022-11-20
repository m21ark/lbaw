@extends('layouts.app')

@section('content')
    <div class="mw-10 d-flex align-items-center justify-content-around">

        <body class="text-center">
            <form method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}

                <h1 class="h3 mb-3 font-weight-normal">Please Register</h1>


                

                @if ($errors->has('username'))
                    <label for="inputUsername" class="sr-only mt-2">Username</label>
                    <input type="text" id="inputUsername" value="{{ old('username') }}" class="form-control mb-3 is-invalid"
                    placeholder="Username" name="username" required autofocus>
                    <div class="text-danger">
                        {{ $errors->first('username') }}
                    </div>
                @else
                    <label for="inputUsername" class="sr-only mt-2">Username</label>
                    <input type="text" id="inputUsername" value="{{ old('username') }}" class="form-control mb-3"
                    placeholder="Username" name="username" required autofocus>
                @endif
                

                @if ($errors->has('email'))
                    <label for="inputEmail" class="sr-only mt-2">Email address</label>
                    <input type="email" id="inputEmail" class="form-control mb-3 is-invalid" placeholder="Email" name="email" required>
                    <div class="text-danger">
                        {{ $errors->first('email') }}
                    </div>
                @else
                    <label for="inputEmail" class="sr-only mt-2">Email address</label>
                    <input type="email" id="inputEmail" class="form-control mb-3" placeholder="Email" name="email" required>
                @endif


                <label for="inputDate" class="sr-only mt-2">Birthdate</label>
                <input type="date" id="inputDate" class="form-control mb-3" name="birthdate" required>


                <label for="inputPassword" class="sr-only mt-2">Password</label>
                <input type="password" id="inputPassword" class="form-control mb-3" placeholder="Password" name="password"
                    required>

                @if ($errors->has('password'))
                    <div class="text-danger">
                        {{ $errors->first('password') }}
                    </div>
                @endif


                <label for="inputRPassword" class="sr-only mt-2">Confirm Password</label>
                <input type="password" id="inputRPassword" class="form-control mb-3" placeholder="Password"
                    name="password_confirmation" required>


                <label for="inputBio" class="sr-only mt-2">Bio</label>
                <input type="text" id="inputBio" class="form-control mb-3" placeholder="Bio" name="bio" required>

                <button class="btn btn-lg btn-primary btn-block mt-4 w-100" type="submit">Register</button>


                <div class="mt-3">
                    <p><span class="bold">Already have an account?</span> <a class="form_button"
                            href="{{ route('login') }}">Login</a></p>

                </div>
            </form>


        </body>
    </div>
@endsection



@section('rightbar')
    @include('partials.guest_sidebar')
@endsection

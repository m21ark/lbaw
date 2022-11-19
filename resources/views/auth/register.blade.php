@extends('layouts.app')

@section('content')
    <div class="mw-10 d-flex align-items-center justify-content-around">

        <body class="text-center">
            <form method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}

                <h1 class="h3 mb-3 font-weight-normal">Please Register</h1>


                @if ($errors->has('username'))
                    <span class="error">
                        {{ $errors->first('username') }}
                    </span>
                @endif

                <label for="inputUsername" class="sr-only mt-2">Username</label>
                <input type="text" id="inputUsername" value="{{ old('username') }}" class="form-control mb-3"
                    placeholder="Username" name="username" required autofocus>

                @if ($errors->has('email'))
                    <span class="error">
                        {{ $errors->first('email') }}
                    </span>
                @endif

                <label for="inputEmail" class="sr-only mt-2">Email address</label>
                <input type="email" id="inputEmail" class="form-control mb-3" placeholder="Email" name="email" required>


                <label for="inputDate" class="sr-only mt-2">Birthdate</label>
                <input type="date" id="inputDate" class="form-control mb-3" name="birthdate" required>


                @if ($errors->has('password'))
                    <span class="error">
                        {{ $errors->first('password') }}
                    </span>
                @endif


                <label for="inputPassword" class="sr-only mt-2">Password</label>
                <input type="password" id="inputPassword" class="form-control mb-3" placeholder="Password" name="password"
                    required>


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

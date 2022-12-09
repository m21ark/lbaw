@extends('layouts.app')

@section('content')
    <div class=" d-flex align-items-center justify-content-around">

        <body class="text-center">
            <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <h1 class="h3 mb-3 font-weight-normal">Please Login</h1>
                <label for="inputEmail" class="sr-only">Email address</label>
                <input type="email" id="inputEmail" value="{{ old('email') }}" class="form-control mb-3" placeholder="Email"
                    name="email" required autofocus autofocus="">
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="password"
                    required>

                <button class="btn btn-lg btn-primary btn-block mt-4 w-100" type="submit">Login</button>

                <!-- TODO: Make better error message -->
                @if ($errors->has('password'))
                    <span class="error">
                        {{ $errors->first('password') }}
                    </span>
                @endif


                <div class="mt-3">
                    <p><span class="bold">Don't have an account?</span> <a class="form_button"
                            href="{{ route('register') }}">Register</a></p>

                </div>
            </form>


        </body>
    </div>
@endsection




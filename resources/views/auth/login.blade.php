@extends('layouts.app')

@section('content')
    <section id="login">
        <div class="container sign_form">
            <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <h2>Login</h2>
                <label>
                    Email <input type="text" value="{{ old('email') }}" placeholder="Email" name="email" required autofocus>
                </label>
                @if ($errors->has('email'))
                    <span class="error">
                      {{ $errors->first('email') }}
                    </span>
                @endif

                <label>
                    Password <input type="password" placeholder="Password" name="password" required >
                </label>
                @if ($errors->has('password'))
                    <span class="error">
                     {{ $errors->first('password') }}
                    </span>
                @endif

                
                <!--
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                </label>
                -->

                <button class="form_button" type="submit">
                 Login
                </button>

            </form>
            <div class="form_alternative">
                <p><span class="bold">Don't have an account?</span></p>
                <a class="form_button" href="{{ route('register') }}">Register</a>
            </div>
        </div>
    </section>
@endsection



@section('rightbar')
    @include('partials.guest_sidebar')
@endsection
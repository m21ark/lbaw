@extends('layouts.app')

@section('content')
    <section id="register">
        <div class="container sign_form">
            <form method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}
                <h2>Register</h2>
                <div class="left_login">
                    <label>
                        Username <input type="text" placeholder="Username" value="{{ old('username') }}" name="username" required autofocus>
                    </label>
                    @if ($errors->has('username'))
                      <span class="error">
                          {{ $errors->first('username') }}
                      </span>
                    @endif
                    <label>
                        Email <input type="email" placeholder="Email" name="email" required>
                    </label>
                    @if ($errors->has('email'))
                    <span class="error">
                        {{ $errors->first('email') }}
                    </span>
                    @endif

                    <label>
                        Bio <input type="text" placeholder="Bio" name="bio" required>
                    </label>

                    <label>
                        Birthday <input type="date" id="birthdate" name="birthdate" required>
                    </label>
                    
                    <label>
                        Password <input type="password" placeholder="Password" name="password" required>
                    </label>
                    @if ($errors->has('password'))
                    <span class="error">
                        {{ $errors->first('password') }}
                    </span>
                    @endif

                    <label>
                        Confirm Password <input type="password" placeholder="Confirm Password" name="password_confirmation" required>
                    </label>

                    <button class="form_button" type="submit">
                     Register
                    </button>
                </div>

            </form>
            <div class="form_alternative">
                <p><span class="bold">Already have an account?</span></p>
                <a class="form_button" href="{{ route('login') }}">Login</a>
            </div>
        </div>

    </section>
@endsection



<!--
    <form method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
        @if ($errors->has('name'))
<span class="error">
                {{ $errors->first('name') }}
            </span>
@endif

        <label for="email">E-Mail Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        @if ($errors->has('email'))
<span class="error">
                {{ $errors->first('email') }}
            </span>
@endif

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
        @if ($errors->has('password'))
<span class="error">
                {{ $errors->first('password') }}
            </span>
@endif

        <label for="password-confirm">Confirm Password</label>
        <input id="password-confirm" type="password" name="password_confirmation" required>

        <button type="submit">
            Register
        </button>
        <a class="button button-outline" href="{{ route('login') }}">Login</a>
    </form>
  


-->

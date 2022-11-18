@extends('layouts.app')

@section('content')
    <div class="mw-10 d-flex align-items-center justify-content-around">

        <body class="text-center">
            <form method="POST" action="{{ route('login') }}">

                <h1 class="h3 mb-3 font-weight-normal">Please Login</h1>
                <label for="inputEmail" class="sr-only">Email address</label>
                <input type="email" id="inputEmail" class="form-control mb-3" placeholder="Email address" required=""
                    autofocus="">
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" required="">

                <button class="btn btn-lg btn-primary btn-block mt-4" type="submit">Login</button>

            </form>


        </body>
    </div>
@endsection



@section('rightbar')
    @include('partials.guest_sidebar')
@endsection

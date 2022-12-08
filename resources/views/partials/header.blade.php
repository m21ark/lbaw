<header class="p-3 text-bg-primary">

    <div class="d-flex flex-wrap align-items-center  justify-content-between w-100">


        <div class="text-start">
            <a href="{{ url('/home') }}"><img src="/logo.jpg" alt="nexus_logo" width="120"></a>

        </div>

        <div class="header_searchbar">
            <input type="search" class="form-control text-bg-light" placeholder="Search" aria-label="Search"
                id="search_bar">
        </div>

        <div class="text-end">

            @if (Auth::check())
                <div class="d-flex align-items-center">
                    <a class="text-white text-decoration-none me-3"
                        href={{ url('/profile/' . Auth::user()->username) }}>
                        <strong class="me-2" id="auth_id"
                            data-id={{ Auth::user()->id }}>{{ Auth::user()->username }}</strong>
                        <img src="{{ asset(Auth::user()->photo) }}" alt="logo" width="40" height="40"
                            class="rounded-circle">
                    </a>

                    <a href={{ url('/logout') }} type="button" class="btn btn-light">Logout</a>
                </div>
            @else
                <a href={{ url('/login') }} type="button" class="btn btn-outline-light me-3">Login</a>
                <a href={{ url('/register') }} type="button" class="btn btn-light">Sign-up</a>
            @endif
        </div>
    </div>

</header>

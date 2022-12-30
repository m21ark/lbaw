<header class="p-3 text-bg-primary">

    <div class="d-flex flex-wrap align-items-center justify-content-between w-100">

        <div class="text-start">
            <a data-toggle="tooltip" data-placement="bottom" title="Go to homepage" href="{{ url('/home') }}"><img
                    src="/logo.jpg" alt="Nexus Logo" width="120" id="logo_img"></a>
        </div>

        <div class="header_searchbar">
            <input type="search" class="form-control text-bg-light" placeholder="Search" aria-label="Search"
                id="search_bar">
        </div>

        <div class="text-end">

            @if (Auth::check())
                <div class="d-flex align-items-center">
                    <span data-toggle="tooltip" data-placement="bottom" title="Get help in current page"
                        id="contextual-help"><i class="fa-regular fa-circle-question fa-2x"></i></span>
                    <a class="text-white text-decoration-none me-3"
                        href={{ url('/profile/' . Auth::user()->username) }}>
                        <strong class="me-2" id="auth_id"
                            data-id={{ Auth::user()->id }}>{{ Auth::user()->username }}</strong>
                        <img id="log_in_photo" src="{{ asset(Auth::user()->photo) }}" alt="User Avatar" width="40"
                            height="40" class="rounded-circle">
                    </a>

                    <a href={{ url('/logout') }} class="btn btn-outline-light" id="logout_btn">Logout</a>
                </div>
            @else
                <a href={{ url('/login') }} class="btn btn-outline-light me-3">Login</a>
                <a href={{ url('/register') }} class="btn btn-light">Sign-up</a>
            @endif
        </div>
    </div>

</header>

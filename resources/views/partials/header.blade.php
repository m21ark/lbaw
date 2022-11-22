<header class="p-3 text-bg-primary">

    <div class="d-flex flex-wrap align-items-center  justify-content-between w-100">


        <div class="text-start">
            <a href="{{ url('/home') }}"><img src="/logo.png" alt="nexus_logo" width="100"></a>

        </div>

        <form class="header_searchbar" action="/search/{{ 'QUERY_STR' }}"  >
            <input type="search" class="form-control text-bg-light" placeholder="Search"
                aria-label="Search" id="search_bar">
        </form>

        <div class="text-end">

            @if (Auth::check())
                <div class="d-flex align-items-center">
                    <a class="text-white text-decoration-none me-3" href={{ url('/profile/' . Auth::user()->username) }}>
                        <strong class="me-2">{{ Auth::user()->username }}</strong>
                        <img src="{{asset(Auth::user()->photo)}}" alt="logo" width="40" height="40">
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

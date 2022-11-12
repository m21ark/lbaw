<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/forms.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/messages.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/post.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/profile.css')); ?>" rel="stylesheet">

    <!-- Javascript -->
    <script type="text/javascript"></script>
    <script type="text/javascript" src={{ asset('js/app.js') }} defer></script>
</head>

<body>

    <nav id="navbar">
        <input type="checkbox" id="hamburger">
        <label class="hamburger" for="hamburger"></label>

        <h2> Home</h2>

        <ul>
            <li><a href="{{ url('/home') }}">Home</a></li>
            <li><a href="{{ url('/messages') }}">Messages</a></li>
            <li><a href="{{ url('/groups') }}">My Groups</a></li>
            <li><a href="#">Notifications</a></li>
        </ul>

        <div>
            <img src="../user.png" alt="" width="50">
            <a href="{{ url('/profile') }}">Profile</a>
        </div>

        <button id="post_button">Post</button>

    </nav>


    @yield('rightbar')

    <!-- Header -->
    <header>
        <a href="{{ url('/home') }}"> <img src="../logo.png" alt="nexus_logo" width="150"></a>

        <input type="text" name="search" id="search_bar" placeholder="Search contents">
        <span><a id="header_search" href="{{ url('/search') }}">&#128270;</a></span>


        @if (Auth::check())
            <a class="button" href="{{ url('/logout') }}"> Logout </a> <span>{{ Auth::user()->name }}</span>
        @else
            <div id="header_signup">
                <a class="link_button" href="{{ url('/login') }}">Login</a>
            </div>
        @endif

        <a id="header_avatar" href="{{ url('/profile') }}"><img src="../user.png" alt="logo"></a>
        <span class="user_id"></span>
    </header>

    <main>

        <div id="bread_crumbs">
            <li><a href={{ url('/home') }}>Home</a></li>
            <?php $segments = ''; ?>
            @foreach (Request::segments() as $segment)
                <?php $segments .= '/' . $segment; ?>
                <li><a href="{{ $segments }}">{{ $segment }}</a></li>
            @endforeach
        </div>

        <!-- Main Content -->
        @yield('content')


    </main>

    <!-- Footer -->
    <footer>
        <p>Nexus Website | LBAW | G2261 | 22/23</p>
    </footer>
</body>

</html>

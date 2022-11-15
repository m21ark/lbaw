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

    <!-- Header -->
    <header>
        <a href={{ url('/home') }}> <img src="../logo.png" alt="nexus_logo" width="150"></a>

        <input type="text" name="search" id="search_bar" placeholder="Search contents">
        <span><a id="header_search" href={{ url('/search/query') }}>&#128270;</a></span>


        @if (Auth::check())
            <a id="header_avatar" href={{ url('/profile/username') }}><img src="../user.png" alt="logo"></a>
            <a href={{ url('/logout') }}> Logout </a>
        @else
            <div id="header_signup">
                <a class="link_button" href={{ url('/login') }}>Login</a>
            </div>
        @endif

    </header>

    <!-- Left Sidebar -->
    <nav id="navbar">
        <input type="checkbox" id="hamburger">
        <label class="hamburger" for="hamburger"></label>

        <ul>
            <li><a href={{ url('/home') }}>Home</a></li>
            <li><a href={{ url('/messages/sender_username') }}>Messages</a></li>
            <li><a href={{ url('/group/per') }}>My Groups</a></li>
            <li><a href="#">Notifications</a></li>
        </ul>
        @if (Auth::check())
            <div>
                <a href={{ url('/profile/username') }}><img src="../user.png" alt="" width="65">
                    <p>Own Profile</p>
                </a>

            </div>

            <button id="post_button" class='make_post_popup form_button'>Post</button>
        @endif
    </nav>

    @yield('rightbar')

    <main>

        <div id="bread_crumbs">
            <li><a href={{ url('/home') }}>Home</a></li>
            <?php $segments = ''; ?>
            @foreach (Request::segments() as $segment)
                <?php $segments .= '/' . $segment; ?>
                <li><a href={{ $segments }}>{{ $segment }}</a></li>
            @endforeach
        </div>

        <!-- Main Content -->
        @yield('content')

        <!-- Hidden Overlapping Pop-ups -->
        @include('partials.make_post_popup')
        @include('partials.make_group_popup')


    </main>

    <!-- Footer -->
    <footer>
        <p>Nexus Website | LBAW | G2261 | 22/23 | <a href={{ url('/about') }}>About</a></p>
    </footer>
</body>

</html>

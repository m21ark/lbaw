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
            <li><a href="home.html">Home</a></li>
            <li><a href="messages.html">Messages</a></li>
            <li><a href="group.html">My Groups</a></li>
            <li><a href="#">Notifications</a></li>
        </ul>

        <div>
            <img src="../user.png" alt="" width="50">
            <a href="profile.html">Profile</a>
        </div>

        <button id="post_button">Post</button>

    </nav>


    <nav id="rightbar">
        <h2>Recommendations</h2>

        <div class="hot_topics">
            <h3>Hot Topics</h3>
            <ul>
                <li><a href="search.html">Topic</a> </li>
                <li><a href="search.html">Topic</a> </li>
                <li><a href="search.html">Topic</a> </li>
                <li><a href="search.html">Topic</a> </li>
            </ul>
        </div>

        <div class="might_know">
            <h3>Might Know</h3>
            <ul>
                <li>
                    <div>
                        <img src="../user.png" alt="user_avatar" width="50">
                        <a href="profile.html">Pessoa 1</a>
                    </div>
                    <a href="#" class="follow_btn">Follow</a>
                </li>

                <li>
                    <div>
                        <img src="../user.png" alt="user_avatar" width="50">
                        <a href="profile.html">Pessoa 2</a>
                    </div>
                    <a href="#" class="follow_btn">Follow</a>
                </li>

                <li>
                    <div>
                        <img src="../user.png" alt="user_avatar" width="50">
                        <a href="profile.html">Pessoa 3</a>
                    </div>
                    <a href="#" class="follow_btn">Follow</a>
                </li>

                <li>
                    <div>
                        <img src="../user.png" alt="user_avatar" width="50">
                        <a href="profile.html">Pessoa 4</a>
                    </div>
                    <a href="#" class="follow_btn">Follow</a>
                </li>

            </ul>
        </div>


    </nav>

    <main>
        <!-- Header -->
        <header>
            <a href="{{ url('/cards') }}"> <img src="logo.png" alt="nexus_logo" width="150"></a>

            <input type="text" name="search" id="search_bar" placeholder="Search contents">
            <span><a id="header_search" href="search.html">&#128270;</a></span>


            @if (Auth::check())
                <a class="button" href="{{ url('/logout') }}"> Logout </a> <span>{{ Auth::user()->name }}</span>
            @endif

            <a id="header_avatar" href="profile.html"><img src="../user.png" alt="logo"></a>
            <span class="user_id"></span>
        </header>

        <!-- Main Content -->
        <section id="content">
            @yield('content')
        </section>
        
    </main>

    <!-- Footer -->
    <footer>
        <p>Nexus Website | LBAW | G2261 | 22/23</p>
    </footer>
</body>

</html>

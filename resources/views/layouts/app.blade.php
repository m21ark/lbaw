<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">


    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Javascript -->
    <script type="text/javascript" src={{ asset('js/app.js') }} defer></script>
</head>

<body class="m-auto">

    <!-- Header -->
    @include('partials.header')

    <!-- Left Sidebar -->
    @include('partials.left_sidebar')

    @yield('rightbar')


    <main>

        <nav aria-label="breadcrumb mb-5">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Library</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data</li>
            </ol>
        </nav>

        <!-- Main Content -->
        @yield('content')

        <!-- Hidden Overlapping Pop-ups -->
        <!-- TODO: Put this somewherelse -->
        @include('partials.make_post_popup', ['popup_class' => 'make_post'])
        @include('partials.make_post_popup', ['popup_class' => 'make_group_post'])
        @include('partials.make_group_popup')


    </main>

    @include('partials.footer')
</body>

</html>

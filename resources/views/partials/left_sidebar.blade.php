<div id="leftbar" class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">

        <span class="fs-4">Home</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">

        <a href={{ url('/home') }} class=" btn btn-outline-primary mb-3">Home </a>

        @auth
            <a href="" class=" btn btn-outline-primary mb-3 drop_my_group" aria-current="page">My Groups</a>

            @include('partials.participating_groups')

            <a href={{ url('/messages/sender_username') }} class=" btn btn-outline-primary mb-3">Messages</a>
        @endauth

        @auth
            <button id="popup_btn_post" class="mt-5 make_post_popup form_button btn btn-primary"
                type="submit">Post</button>
        @endauth

        @if (!Auth::check())
            <hr class="mt-4">

            <h3>Your not logged in!</h3>
            <p>Create an account to access most features</p>

            <a href={{ url('/about') }} class=" btn btn-outline-primary mb-3">About</a>
            <a href={{ url('/contacts') }} class=" btn btn-outline-primary mb-3">Contacts </a>
            <a href={{ url('/features') }} class=" btn btn-outline-primary mb-3">Features</a>
        @endif

    </ul>
</div>

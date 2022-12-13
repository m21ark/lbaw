<div aria-live="polite" aria-atomic="true" class="l_navbar show flex-column flex-shrink-0 p-3 bg-light"
    id="notifications_container">
    <a href="#!" id="markAllAsSeen_notifications" class="btn btn-outline-secondary mt-3 mb-3 w-100">Clear all</a>
</div>
<div id="leftbar" class="flex-column flex-shrink-0 p-3 bg-light">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">

        <span class="fs-4">Home</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auwidth: 8emto">

        <a href="/home" class=" btn mb-3 d-flex align-items-center justify-content-around">
            <i class="fa-solid fa-house fa-2x"></i>
            <span style="width: 8em;font-size:1.2em" class="enc">Home</span>
        </a>

        @auth

            <a href={{ url('/user/friends/requests') }} class="btn mb-3 d-flex align-items-center justify-content-around"
                aria-current="page">
                <i class="fa-solid fa-user-group fa-2x"></i>
                <span style="width: 8em;font-size:1.2em" class="enc">Friends Requests</span>

            </a>

            <a href="{{ url('/my_groups') }}"
                class=" btn mb-3 drop_my_group d-flex align-items-center justify-content-around" aria-current="page">
                <i class="fa-solid fa-people-group fa-2x"></i>
                <span style="width: 8em;font-size:1.2em" class="enc">My Groups</span>
            </a>


            <a href="" class="btn mb-3 d-flex align-items-center justify-content-around" aria-current="page"
                id="notification_icon">
                <span class="position-relative">
                    <i class="fa-solid fa-bell fa-2x"></i>
                    <span
                        class="position-absolute top-0 start-70 translate-middle badge rounded-pill badge-notification bg-danger"
                        id="notf_nr" hidden>1</span>
                </span>
                <span style="width: 8em;font-size:1.2em" class="enc">Notifications</span>
            </a>

            <a href={{ url('/messages/sender_username') }}
                class="btn mb-3 d-flex align-items-center justify-content-around">
                <span class="position-relative">
                    <i class="fa-solid fa-envelope fa-2x"></i>
                    <span
                        class="position-absolute top-0 start-70 translate-middle badge rounded-pill badge-notification bg-danger"
                        hidden>1</span>
                </span>
                <span style="width: 8em;font-size:1.2em" class="enc">Messages</span>
            </a>


            <button id="popup_btn_post" class="mt-5 make_post_popup form_button btn btn-primary enc" type="submit">
                <span style="font-size:1.7em" class="me-4">Post</span>
                <i class="fa-regular fa-square-plus fa-2x"></i>

            </button>

            @if (Auth::user()->isAdmin)
                <a href="/admin" class=" btn btn-outline-primary mt-3 mb-3 enc">Admin Console</a>
                <a href="/admin/statistics" class=" btn btn-outline-primary mt-3 mb-3 enc">Admin Stats</a>
            @endif
        @endauth


        @guest
            <hr class="mt-4">

            <h3>Your not logged in!</h3>
            <p>Create an account to access most features</p>

            <a href={{ url('/about') }} class=" btn btn-outline-primary mb-3">About</a>
            <a href={{ url('/contacts') }} class=" btn btn-outline-primary mb-3">Contacts </a>
            <a href={{ url('/features') }} class=" btn btn-outline-primary mb-3">Features</a>
        @endguest


    </ul>
</div>



<div id="small_leftbar" class=" flex-shrink-0 p-3 bg-light">

    <ul class="d-flex nav nav-pills mb-auto align-items-center justify-content-around">


        @auth

            <a href="/home" class=" btn d-flex align-items-center justify-content-around">
                <i class="fa-solid fa-house fa-2x"></i>
            </a>

            <a href={{ url('/user/friends/requests') }} class="btn  d-flex align-items-center justify-content-around"
                aria-current="page">
                <i class="fa-solid fa-user-group fa-2x"></i>


            </a>

            <a href="{{ url('/my_groups') }}" class=" btn drop_my_group d-flex align-items-center justify-content-around"
                aria-current="page">
                <i class="fa-solid fa-people-group fa-2x"></i>
            </a>


            <a href="" class="btn  d-flex align-items-center justify-content-around" aria-current="page"
                id="notification_icon">
                <span class="position-relative">
                    <i class="fa-solid fa-bell fa-2x"></i>
                    <span
                        class="position-absolute top-0 start-70 translate-middle badge rounded-pill badge-notification bg-danger"
                        id="notf_nr" hidden>1</span>
                </span>

            </a>

            <a href={{ url('/messages/sender_username') }} class="btn d-flex align-items-center justify-content-around">
                <span class="position-relative">
                    <i class="fa-solid fa-envelope fa-2x"></i>
                    <span
                        class="position-absolute top-0 start-70 translate-middle badge rounded-pill badge-notification bg-danger"
                        hidden>1</span>
                </span>
            </a>

            <a class="text-primary btn drop_my_group d-flex align-items-center justify-content-around" aria-current="page"
                id="popup_btn_post">
                <span class="position-relative">
                    <i class="fa-regular fa-square-plus fa-2x"></i>
                </span>
            </a>






            @if (Auth::user()->isAdmin)
                <a href="/admin" class=" btn btn-outline-primary mt-3 mb-3 enc">Admin Console</a>
                <a href="/admin/statistics" class=" btn btn-outline-primary mt-3 mb-3 enc">Admin Stats</a>
            @endif
        @endauth


        @guest
            <a href={{ url('/about') }} class=" btn btn-outline-primary">About</a>
            <a href={{ url('/contacts') }} class=" btn btn-outline-primary">Contacts </a>
            <a href={{ url('/features') }} class=" btn btn-outline-primary">Features</a>
        @endguest


    </ul>
</div>

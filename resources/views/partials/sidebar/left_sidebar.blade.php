<div aria-live="polite" aria-atomic="true" class="l_navbar show flex-column flex-shrink-0 p-3 bg-light overflow-auto"
    id="notifications_container" style="max-height: 45em">
</div>
<div id="leftbar" class="flex-column flex-shrink-0 p-3 bg-light">

    <ul class="nav nav-pills flex-column mb-auwidth: 8emto">

        <a href="/home" class=" btn mb-3 mt-4 d-flex align-items-center justify-content-around">
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

            <a href={{ url('/messages/') }} class="btn mb-3 d-flex align-items-center justify-content-around">
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
            <hr class="mt-1">

            <h3>Your not logged in!</h3>
            <p class="mb-3">Create an account to access most features</p>

            <a href={{ url('/about') }} class="btn btn-outline-primary mb-3 white_span_a"><span>About</span></a>
            <a href={{ url('/contacts') }} class=" btn btn-outline-primary mb-3 white_span_a"><span>Contacts</span> </a>
            <a href={{ url('/features') }} class=" btn btn-outline-primary mb-3 white_span_a"><span>Features</span></a>
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


            <a href="{{ url('/notifications') }}" class="btn  d-flex align-items-center justify-content-around"
                aria-current="page" >
                <span class="position-relative">
                    <i class="fa-solid fa-bell fa-2x"></i>
                    <span
                        class="position-absolute top-0 start-70 translate-middle badge rounded-pill badge-notification bg-danger"
                        id="notf_nr2" hidden>1</span>
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
            <a href="/home" class=" btn d-flex align-items-center justify-content-around">
                <i class="fa-solid fa-house fa-2x"></i>
            </a>

            <a href={{ url('/about') }} class=" btn btn-outline-primary white_span_a"
                style="width:20%"><span>About</span></a>
            <a href={{ url('/contacts') }} class=" btn btn-outline-primary white_span_a"
                style="width:20%"><span>Contacts</span> </a>
            <a href={{ url('/features') }} class=" btn btn-outline-primary white_span_a"
                style="width:20%"><span>Features</span></a>
        @endguest


    </ul>
</div>

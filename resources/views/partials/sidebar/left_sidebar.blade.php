<div aria-live="polite" aria-atomic="true" class="l_navbar show flex-column flex-shrink-0 p-3 bg-light overflow-auto"
    id="notifications_container" style="max-height: 95vh">
</div>
<div id="leftbar" class="flex-column flex-shrink-0 p-3 bg-light">

    <ul class="nav nav-pills flex-column mb-auwidth: 8emto">

        <li data-toggle="tooltip" data-placement="top" title="Go to homepage">
            <a href="/home" class=" btn mb-3 mt-4 d-flex align-items-center justify-content-around">
                <i class="fa-solid fa-house fa-2x"></i>
                <span style="width: 8em;font-size:1.2em" class="enc">Home</span>
            </a>
        </li>
        @auth
            <li data-toggle="tooltip" data-placement="top" title="Go to pendent friend's requests page">
                <a href={{ url('/user/friends/requests') }}
                    class="btn mb-3 d-flex align-items-center justify-content-around" aria-current="page">
                    <i class="fa-solid fa-user-group fa-2x"></i>
                    <span style="width: 8em;font-size:1.2em" class="enc">Friends Requests</span>

                </a>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Go to your group list page">
                <a href="/group_list/{{ Auth::user()->username }}"
                    class=" btn mb-3 drop_my_group d-flex align-items-center justify-content-around" aria-current="page">
                    <i class="fa-solid fa-people-group fa-2x"></i>
                    <span style="width: 8em;font-size:1.2em" class="enc">My Groups</span>
                </a>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="See latest notification list">
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
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Go to direct messages page">
                <a href={{ url('/messages/') }} class="btn mb-3 d-flex align-items-center justify-content-around">
                    <span class="position-relative">
                        <i class="fa-solid fa-envelope fa-2x"></i>
                        <span
                            class="position-absolute top-0 start-70 translate-middle badge rounded-pill badge-notification bg-danger"
                            hidden>1</span>
                    </span>
                    <span style="width: 8em;font-size:1.2em" class="enc">Messages</span>
                </a>
            </li>
            @if (Auth::user()->isAdmin)
                <li data-toggle="tooltip" data-placement="top" title="Go to admin console page">
                    <a href="{{ url('/admin') }}"
                        class=" btn mt-2 mb-3 drop_my_group d-flex align-items-center justify-content-around"
                        aria-current="page">
                        <i class="fa-solid fa-sliders fa-2x"></i>
                        <span style="width: 8em;font-size:1.2em" class="enc">Admin Console</span>
                    </a>
                </li>
            @endif


            @if (isset($ShowGroupPostButton) &&
                isset($group) &&
                in_array(Auth::user()->id, $group->members->pluck('id_user')->toArray()))
                <button data-toggle="tooltip" data-placement="top" title="Make a post on this group"
                    id="popup_btn_group_post" class="mt-5 make_post_popup form_button btn btn-primary enc" type="submit">
                    <span style="font-size:1.6em" class="me-4">Group Post</span>
                    <i class="fa-regular fa-square-plus fa-2x"></i>
                </button>
            @else
                <button data-toggle="tooltip" data-placement="top" title="Make a post on your profile" id="popup_btn_post"
                    class="mt-5 make_post_popup form_button btn btn-primary enc" type="submit">
                    <span style="font-size:1.6em" class="me-4">Post</span>
                    <i class="fa-regular fa-square-plus fa-2x"></i>
                </button>
            @endif


        @endauth

        @guest
            <li data-toggle="tooltip" data-placement="top" title="Go to About us page">
                <a href="{{ url('/about') }}"
                    class=" btn mt-3 mb-3 drop_my_group d-flex align-items-center justify-content-around"
                    aria-current="page">
                    <i class="fa-solid fa-info-circle fa-2x"></i>
                    <span style="width: 8em;font-size:1.2em" class="enc">About Us</span>
                </a>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Go to Contacts page">
                <a href="{{ url('/contacts') }}"
                    class=" btn mb-3 drop_my_group d-flex mt-3 align-items-center justify-content-around"
                    aria-current="page">
                    <i class="fa-solid fa-people-group fa-2x"></i>
                    <span style="width: 8em;font-size:1.2em" class="enc">Contacts</span>
                </a>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Go to Features page">
                <a href="{{ url('/features') }}"
                    class=" btn mt-3 mb-3 drop_my_group d-flex align-items-center justify-content-around"
                    aria-current="page">
                    <i class="fa-solid fa-lightbulb fa-2x"></i>
                    <span style="width: 8em;font-size:1.2em" class="enc">Features</span>
                </a>
            </li>

            <hr>

            <li>
                <h3 class="mt-3 text-center">Your not logged in!</h3>
            </li>
            <li>
                <h5 class="mb-3 mt-3 text-center">Create an account to access most features</h5>
            </li>
        @endguest


    </ul>
</div>



<div id="small_leftbar" class=" flex-shrink-0 bg-light pt-2">

    <ul class="d-flex nav nav-pills mb-auto align-items-center justify-content-around">


        @auth

            <li data-toggle="tooltip" data-placement="top" title="Go to Home Page">
                <a href="/home" class="btn">
                    <i class="fa-solid fa-house fa-2x"></i>
                    <p style="padding: 0;margin:0">Home</p>
                </a>
            </li>

            <li data-toggle="tooltip" data-placement="top" title="Go to pendent friend's requests page">
                <a href={{ url('/user/friends/requests') }} class="btn">
                    <i class="fa-solid fa-user-group fa-2x"></i>
                    <p style="padding: 0;margin:0;">Friend Requests</p>
                </a>
            </li>

            <li data-toggle="tooltip" data-placement="top" title="Go to your group list page">
                <a href="/group_list/{{ Auth::user()->username }}" class="btn">
                    <i class="fa-solid fa-people-group fa-2x"></i>
                    <p style="padding: 0;margin:0;">My Groups</p>
                </a>
            </li>

            <li data-toggle="tooltip" data-placement="top" title="See latest notification list">
                <a href="{{ url('/notifications') }}" class="btn">
                    <span class="position-relative">
                        <i class="fa-solid fa-bell fa-2x"></i>
                        <span
                            class="position-absolute top-0 start-70 translate-middle badge rounded-pill badge-notification bg-danger"
                            id="notf_nr2" hidden>1</span>
                    </span>
                    <p style="padding: 0;margin:0;">Notifications</p>
                </a>
            </li>

            <li data-toggle="tooltip" data-placement="top" title="Go to direct messages page">
                <a href={{ url('/messages') }} class="btn">
                    <span class="position-relative">
                        <i class="fa-solid fa-envelope fa-2x"></i>
                        <span
                            class="position-absolute top-0 start-70 translate-middle badge rounded-pill badge-notification bg-danger"
                            hidden>1</span>
                    </span>
                    <p style="padding: 0;margin:0;">Messages</p>
                </a>
            </li>

            @if (Auth::user()->isAdmin)
                <li data-toggle="tooltip" data-placement="top" title="Go to admin console page">
                    <a href="{{ url('/admin') }}" class=" btn" aria-current="page">
                        <span class="position-relative">
                            <i class="fa-solid fa-sliders fa-2x"></i>
                        </span>
                        <p style="padding: 0;margin:0;">Admin Console</p>
                    </a>
                </li>
            @endif

            <li>
                @if (isset($ShowGroupPostButton) &&
                    isset($group) &&
                    in_array(Auth::user()->id, $group->members->pluck('id_user')->toArray()))
                    <a data-toggle="tooltip" data-placement="top" title="Make a post on this group"
                        id="popup_btn_group_post" class="text-primary btn" aria-current="page">
                        <span class="position-relative">
                            <i class="fa-regular fa-square-plus fa-2x"></i>
                        </span>

                        <p style="padding: 0;margin:0;">Post</p>
                    </a>
                @else
                    <a data-toggle="tooltip" data-placement="top" title="Make a post on your profile"
                        class="text-primary btn" aria-current="page" id="popup_btn_post">
                        <span class="position-relative">
                            <i class="fa-regular fa-square-plus fa-2x"></i>
                        </span>
                        <p style="padding: 0;margin:0;">Post</p>
                    </a>
                @endif
            </li>

        @endauth


        @guest

            <li data-toggle="tooltip" data-placement="top" title="Go to Home Page">
                <a href="/home" class="btn">
                    <i class="fa-solid fa-house fa-2x"></i>
                    <p style="padding: 0;margin:0">Home</p>
                </a>
            </li>

            <li data-toggle="tooltip" data-placement="top" title="Go to About Us page">
                <a href={{ url('/about') }} class="btn">
                    <i class="fa fa-info-circle fa-2x"></i>
                    <p style="padding: 0;margin:0;">About Us</p>
                </a>

            </li>
            <li data-toggle="tooltip" data-placement="top" title="Go to Contacts page">
                <a href="{{ url('/contacts') }}" class="btn">
                    <i class="fa-solid fa-people-group fa-2x"></i>
                    <p style="padding: 0;margin:0;">Contacts</p>
                </a>
            </li>

            <li data-toggle="tooltip" data-placement="top" title="Go to Features page">
                <a href="{{ url('/features') }}" class="btn">
                    <i class="fa-solid fa-lightbulb fa-2x"></i>
                    <p style="padding: 0;margin:0;">Features</p>
                </a>
            </li>
        @endguest


    </ul>
</div>

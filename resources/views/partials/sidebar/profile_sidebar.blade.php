<nav id="rightbar" class="text-bg-light">
    <h2 class="mb-4">Profile Info</h2>

    <div class="card border-secondary mb-4">
        <h3 class="p-2 me-5">About me
            @auth
                @if (Auth::user()->id == $user->id || Auth::user()->isAdmin)
                    <a href="#" class="btn btn-secondary w-20" id="popup_btn_profile_edit">Edit</a>
                @endif
            @endauth
        </h3>

        <div class="m-auto">
            <img class="profile_img rounded-circle" src="{{ asset($user->photo) }}" alt="" width="150">
            <h3 id="username" class="">{{ $user->username }}</h3>
        </div>

        <div class="card-body">
            <h3>Bio</h3>
            <p class="card-text">{{ $user->bio }}</p>
        </div>

        <div class="card-footer">
            <h3>Interests</h3>

            <div class="d-flex justify-content-evenly">


                @foreach ($user->interests as $interest)
                    <a class="btn btn-primary" href={{ url('/search/' . $interest->topic->topic) }}>
                        {{ $interest->topic->topic }}
                    </a>
                @endforeach
            </div>

        </div>

        @auth
            @if (Auth::user()->isAdmin)
                <div class="mt-4 pt-4 card-footer">
                    <h4>Admin Information</h4>
                    <hr>
                    <?php $ban_date = $user->ban_date ?? 'N/A'; ?>
                    <p>Birthdate: {{ $user->birthdate }}</p>
                    <p>Email: {{ $user->email }}</p>
                    <p>Ban status: <a class="ms-2" href="/admin/report/{{ $user->username }}">{{ $ban_date }}</a></p>
                </div>
            @endif
        @endauth

    </div>


    <!-- TODO: Do statistics from database -->
    <h3 class="mt-5 mb-3">Statistics</h3>
    <div class="list-group align-items-center mb-5">


        <div class="d-flex list-group-item w-100">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor"
                class="bi bi-sticky" viewBox="0 0 16 16">
                <path
                    d="M2.5 1A1.5 1.5 0 0 0 1 2.5v11A1.5 1.5 0 0 0 2.5 15h6.086a1.5 1.5 0 0 0 1.06-.44l4.915-4.914A1.5 1.5 0 0 0 15 8.586V2.5A1.5 1.5 0 0 0 13.5 1h-11zM2 2.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 .5.5V8H9.5A1.5 1.5 0 0 0 8 9.5V14H2.5a.5.5 0 0 1-.5-.5v-11zm7 11.293V9.5a.5.5 0 0 1 .5-.5h4.293L9 13.793z" />
            </svg>
            <a href="#" class="text-decoration-none p-3">{{ $statistics['post_num'] }} Posts</a>
        </div>

        <div class="d-flex list-group-item w-100">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor"
                class="bi bi-envelope-heart" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l3.235 1.94a2.76 2.76 0 0 0-.233 1.027L1 5.384v5.721l3.453-2.124c.146.277.329.556.55.835l-3.97 2.443A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741l-3.968-2.442c.22-.28.403-.56.55-.836L15 11.105V5.383l-3.002 1.801a2.76 2.76 0 0 0-.233-1.026L15 4.217V4a1 1 0 0 0-1-1H2Zm6 2.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132Z" />
            </svg>
            <a href="#" class="text-decoration-none p-3">{{ $statistics['friends_num'] }} Friends</a>
        </div>

        <div class="d-flex list-group-item w-100">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor"
                class="bi bi-people-fill" viewBox="0 0 16 16">
                <path
                    d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7Zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216ZM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />
            </svg>
            <a href="#" class="text-decoration-none p-3">{{ $statistics['group_num'] }} Groups</a>
        </div>

        <div class="d-flex list-group-item w-100">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor"
                class="bi bi-heart" viewBox="0 0 16 16">
                <path
                    d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z" />
            </svg>
            <a href="#"
                class="text-decoration-none p-3">{{ $statistics['like_comment_num'] + $statistics['like_post_num'] }}
                Likes</a>
        </div>

        <div class="d-flex list-group-item w-100">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor"
                class="bi bi-chat-left" viewBox="0 0 16 16">
                <path
                    d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
            </svg>
            <a href="#" class="text-decoration-none p-3">{{ $statistics['comment_num'] }} Comments</a>
        </div>

    </div>




</nav>

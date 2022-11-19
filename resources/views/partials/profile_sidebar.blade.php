<nav id="rightbar" class="text-bg-light">
    <h2 class="mb-4">Profile Info</h2>

    <div class="card border-secondary mb-4">
        <h3 class="p-2 me-5">About me
            @auth
                @if (Auth::user()->id == $user->id)
                    <a href="#" class="btn btn-secondary w-20" id="popup_btn_profile_edit">Edit</a>
                @endif
            @endauth
        </h3>

        <div class="m-auto">
            <img class="profile_img " src="../user.png" alt="" width="150">
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


    </div>


    <!-- TODO: Do statistics from database -->
    <h3 class="mt-5 mb-3">Statistics</h3>
    <div class="list-group align-items-center mb-5">

        <div class="d-flex list-group-item w-100">
            <img src="../user.png" alt="user_avatar" width="50">
            <a href="#" class=" p-3">34 Friends</a>
        </div>

        <div class="d-flex list-group-item w-100">
            <img src="../user.png" alt="user_avatar" width="50">
            <a href="#" class=" p-3">6 Groups</a>
        </div>

        <div class="d-flex list-group-item w-100">
            <img src="../user.png" alt="user_avatar" width="50">
            <a href="#" class=" p-3">21 Likes</a>
        </div>

        <div class="d-flex list-group-item w-100">
            <img src="../user.png" alt="user_avatar" width="50">
            <a href="#" class=" p-3">4 Comments</a>
        </div>

    </div>




</nav>

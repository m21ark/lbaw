<nav id="rightbar">
    <h2>Profile Info</h2>

    <img class="profile_img" src="../user.png" alt="" width="150">
    <h3 id="username">{{ $user->username }}</h3>

    <div class="about_me">
        <h3>About me</h3>

        <p id="user_bio">
            {{ $user->bio }}
        </p>

        <h3>Interests</h3>

        <ul id="user_interests">

            @foreach ($user->interests as $interest)
                <a href={{ url('/search/' . $interest->topic->topic) }}><li>{{ $interest->topic->topic }}</li></a>
            @endforeach

        </ul>

    </div>

    <div class="user_stats">
        <h3>Statistics</h3>
        <!-- TODO: Fetch from DB -->
        <ul>
            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="#" class="link_button">34 Friends</a>
            </li>

            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="#" class="link_button">6 Groups</a>
            </li>

            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="#" class="link_button">21 Likes</a>
            </li>
            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="#" class="link_button">4 Comments</a>
            </li>



        </ul>
    </div>

    @if (Auth::user()->id == $user->id)
        <a href="#" class="edit_profile_btn form_button">Edit</a>
    @endif


</nav>

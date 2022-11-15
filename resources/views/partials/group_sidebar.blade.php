<nav id="rightbar">

    <h2>Group Info</h2>


    <h3 id="username">{{ $group->name }}</h3>
    <!-- TODO add profile image  -->
    <img src="../user.png" alt="" width="150">

    <h3>Description</h3>

    <p id="user_bio">{{ $group->description }}</p>

    <div class="might_know">
        <h3>Members</h3>
        <ul>

            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="profile.html">Person 1</a>
            </li>

            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="profile.html">Person 1</a>
            </li>

            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="profile.html">Person 1</a>
            </li>

            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="profile.html">Person 1</a>
            </li>

        </ul>
    </div>


    <div class="might_know">
        <h3>Groups for you</h3>
        <ul>

            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="profile.html">Person 1</a>
                <a href="#" class="link_button">Join</a>
            </li>

            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="profile.html">Group 1</a>
                <a href="#" class="link_button">Join</a>
            </li>

            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="profile.html">Group 1</a>
                <a href="#" class="link_button">Join</a>
            </li>

            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="profile.html">Group 1</a>
                <a href="#" class="link_button">Join</a>
            </li>

        </ul>
    </div>

    @if (Auth::check())
        <button id="create_group_button" class='form_button create_group_button'>Create Group</button>
    @endif


</nav>

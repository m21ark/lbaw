<nav id="rightbar">
    <h2>Groups</h2>

    <div class="might_know">
        <h3>Members</h3>
        <ul>
            <li>
                <div>
                    <img src="../user.png" alt="user_avatar" width="50">
                    <a href="profile.html">Group 1</a>
                </div>
            </li>

            <li>
                <div>
                    <img src="../user.png" alt="user_avatar" width="50">
                    <a href="profile.html">Group 1</a>
                </div>
            </li>

            <li>
                <div>
                    <img src="../user.png" alt="user_avatar" width="50">
                    <a href="profile.html">Group 1</a>
                </div>
            </li>

            <li>
                <div>
                    <img src="../user.png" alt="user_avatar" width="50">
                    <a href="profile.html">Group 1</a>
                </div>
            </li>


        </ul>
    </div>

    <div class="might_know">
        <h3>Might interest you</h3>
        <ul>
            <li>
                <div>
                    <img src="../user.png" alt="user_avatar" width="50">
                    <a href="profile.html">Group 1</a>
                </div>
                <a href="#" class="link_button">Join</a>
            </li>

            <li>
                <div>
                    <img src="../user.png" alt="user_avatar" width="50">
                    <a href={{ url('/profile/username') }}>Group 2</a>
                </div>
                <a href="#" class="link_button">Join</a>
            </li>

            <li>
                <div>
                    <img src="../user.png" alt="user_avatar" width="50">
                    <a href={{ url('/profile/username') }}>Group 3</a>
                </div>
                <a href="#" class="link_button">Join</a>
            </li>

            <li>
                <div>
                    <img src="../user.png" alt="user_avatar" width="50">
                    <a href={{ url('/profile/username') }}>Group 4</a>
                </div>
                <a href="#" class="link_button ">Join</a>
            </li>

        </ul>
    </div>


    @if (Auth::check())
        <button id="create_group_button" class='form_button create_group_button'>Create Group</button>
    @endif


</nav>

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

            @foreach ($group->owners as $owner)
                <li>

                    <img src="../user.png" alt="user_avatar" width="50">
                    <a href={{ url('/profile', ['username' => $owner->user->username]) }}>&star;
                        {{ $owner->user->username }}</a>
                </li>
            @endforeach

            @foreach ($group->members as $member)
                <li>
                    <!-- in_array($member->id, $group->owners->toArray())  -->
                    <img src="../user.png" alt="user_avatar" width="50">
                    <a
                        href={{ url('/profile', ['username' => $member->user->username]) }}>{{ $member->user->username }}</a>
                </li>
            @endforeach



        </ul>
    </div>


    <div class="might_know">
        <h3>Groups for you</h3>
        <ul>

            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="profile.html">Group 1</a>
                <a href="#" class="link_button">Join</a>
            </li>

            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="profile.html">Group 2</a>
                <a href="#" class="link_button">Join</a>
            </li>

            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="profile.html">Group 3</a>
                <a href="#" class="link_button">Join</a>
            </li>

            <li>
                <img src="../user.png" alt="user_avatar" width="50">
                <a href="profile.html">Group 4</a>
                <a href="#" class="link_button">Join</a>
            </li>

        </ul>
    </div>

    @if (Auth::check())
        <!-- Temporary placement -->
        <button class='form_button create_group_button'>Create Group</button>
        <button class='form_button leave_group_button' data-idGroup="{{ $group->id }}">Leave Group</button>
    @endif


</nav>

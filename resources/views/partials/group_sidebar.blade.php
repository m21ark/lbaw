<nav id="rightbar" class="text-bg-light">

    <h2>Group Info</h2>
    <hr>
    <div class="card border-secondary mb-4">

        <div class="card-header">
            <h3 class="p-2">{{ $group->name }}</h3>
        </div>

        <div class="mt-3 m-auto ">
            <img class="profile_img " src="../user.png" alt="" width="150">
        </div>

        <div class="card-body">
            <h3>Bio</h3>
            <p class="card-text">{{ $group->description }}</p>
        </div>

        <div class="card-footer pb-0 pt-3">
            <ul class="list-unstyled">
                <li class="lead">{{ sizeof($group->owners) }} Owners</li>
                <li class="lead">{{ sizeof($group->members) }} Members</li>
                <li class="lead">{{ sizeof($group->posts) }} Posts</li>
            </ul>
        </div>

    </div>


    <button id="group_post_button" class='btn btn-primary w-100 mb-3 mt-2'>Post on group</button>


    <h3 class="mb-4">Members</h3>
    <div class="list-group align-items-center d-flex mb-4 group_member_list">

        @foreach ($group->owners as $owner)
            <div class="list-group-item max_width_rightbar">
                <img  src="../user.png" alt="user_avatar" width="50">
                <a href={{ url('/profile', ['username' => $owner->user->username]) }}>
                    {{ $owner->user->username }}</a>

                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-star-fill" viewBox="0 0 16 16">
                    <path
                        d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                </svg>

            </div>
        @endforeach


        @foreach ($group->members as $member)
            @if (!in_array($member->id_user, $group->owners->pluck('id_user')->toArray()))
                <div class="list-group-item max_width_rightbar">
                    <img src="../user.png" alt="user_avatar" width="50">
                    <a
                        href={{ url('/profile', ['username' => $member->user->username]) }}>{{ $member->user->username }}</a>

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"></svg>
                </div>
            @endif
        @endforeach


    </div>






    <h3 class="mb-4">Groups for you</h3>
    <div class="list-group align-items-center d-flex mb-4 ">

        <div class="list-group-item">
            <img class="me-5" src="../user.png" alt="user_avatar" width="50">
            <a class="me-5" href={{ url('/profile/username') }}>Group</a>
            <a href="#" class="btn btn-outline-primary">Join</a>
        </div>

        <div class="list-group-item">
            <img class="me-5" src="../user.png" alt="user_avatar" width="50">
            <a class="me-5" href={{ url('/profile/username') }}>Group</a>
            <a href="#" class="btn btn-outline-primary">Join</a>
        </div>

        <div class="list-group-item">
            <img class="me-5" src="../user.png" alt="user_avatar" width="50">
            <a class="me-5" href={{ url('/profile/username') }}>Group</a>
            <a href="#" class="btn btn-outline-primary">Join</a>
        </div>

    </div>


    <h3>Actions</h3>

    @if (Auth::check())
        <!-- Temporary placement -->
        <button class='btn btn-primary w-100 mb-3 mt-3 create_group_button'>Create Group</button>

        <!-- Should oly be visible to group owners -->
        <button class='btn btn-primary w-100 mb-3 mt-3 edit_group_button' data-idGroup="{{ $group->id }}">Edit
            Group</button>

        <!-- SHould only be visible to group members/owners -->
        <button class='btn btn-primary w-100 mb-3 mt-3 leave_group_button' data-idGroup="{{ $group->id }}">Leave
            Group</button>
    @endif

    @auth
        @if (Auth::user()->id == $group->owner_id)
            <button id="edit_group_button" class='btn btn-primary w-100 mb-3 mt-3 edit_group_button'>Edit Group</button>
        @endif
    @endauth

</nav>

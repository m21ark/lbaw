<nav id="rightbar" class="text-bg-light">

    <h2>Group Info</h2>
    <hr>
    <div class="card border-secondary mb-4">

        <div class="card-header">
            <h3 class="p-2 ">{{ $group->name }}
                @auth
                    @if (in_array(Auth::user()->id, $group->owners->pluck('id_user')->toArray()) || Auth::user()->isAdmin)
                        <a class='btn btn-secondary' href={{ '/group/' . $group->name . '/edit' }} id="popup_btn_group_edit"
                            data-idGroup="{{ $group->name }}">Edit</a>
                    @endif
                @endauth
            </h3>

            @auth

                @if (!$in_group && in_array(Auth::user()->id, $group->groupJoinRequests->pluck('id_user')->toArray()))
                    <a class="w-100 cancel_g_request btn btn-primary"><i class="fa-solid fa-clock-rotate-left"
                            data-toggle="tooltip" data-placement="bottom" title="Cancel Join Group Request"
                            data-id="{{ $group->id }}"></i>
                        <span>Cancel Request</span></a>
                @elseif (!$in_group)
                    <a class="w-100 send_g_request btn btn-primary"><i class="fa-solid fa-door-open" data-toggle="tooltip"
                            data-placement="bottom" title="Ask to join group" data-id="{{ $group->id }}"></i>
                        <span>Request to Join</span></a>
                @endif

            @endauth


        </div>



        <div class="mt-3 m-auto">
            <img class="profile_img rounded-circle" src="{{ asset($group->photo) }}" alt="Group Profile Image"
                width="150" height="150">
        </div>


        <div class="card-body">
            <h3>Bio</h3>
            <p class="card-text">{{ $group->description }}</p>

            <p class="card-text"><b>Visibility: </b><span class="card-text">
                    @if ($group->visibility)
                        Public
                    @else
                        Private
                    @endif
                </span>
            </p>
        </div>


        <div class="card-footer">
            <h3>Topics</h3>
            <div class="d-flex justify-content-evenly">
                @foreach ($group->topics as $group_topic)
                    <a class="btn btn-primary" href={{ url('/search/' . $group_topic->topic->topic) }}>
                        {{ $group_topic->topic->topic }}
                    </a>
                @endforeach
            </div>
        </div>



        <div class="card-footer pb-0 pt-3">
            <ul class="list-unstyled">
                <!-- VALUES ARE WRONG COUNTED CAUSE APART FROM FIRST OWNER, OTHER OWNERS HAVE REQUEST TO JOIN -->
                <li class="lead">{{ sizeof($group->owners) }} Owners</li>
                <li class="lead">
                    {{ sizeof($group->members->whereNotIn('id_user', $group->owners->pluck('id_user')->toArray())) }}
                    Members</li>
                <li class="lead">{{ sizeof($group->posts) }} Posts</li>

            </ul>
        </div>

    </div>


    <button id="popup_btn_group_post" onclick="groupPostResponsiveUI()" class='btn btn-primary w-100 mb-3 mt-2'>Post on
        group</button>


    <h3 class="mb-4">Members</h3>

    <div class="list-group align-items-center d-flex mb-2 group_member_list">
        @if (Auth::check() &&
            (in_array(Auth::user()->id, $group->owners->pluck('id_user')->toArray()) || Auth::user()->isAdmin))
            <a href='/group/{{ $group->name }}/requests' class="btn btn-outline-secondary mb-3"><i
                    class="fa-solid fa-user-group"></i> Pendent Requests
                ({{ count($group->groupJoinRequests->where('acceptance_status', 'Pendent')->toArray()) }})</a>
        @endif
        @foreach ($group->owners as $owner)
            <div class="list-group-item max_width_rightbar">
                <img src="{{ asset($owner->user->photo) }}" alt="Post Owner Profile Image" width="50"
                    class="rounded-circle">
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
                    <img src="{{ asset($member->user->photo) }}" alt="Group Member Profile Image" width="50"
                        class="rounded-circle">
                    <a
                        href={{ url('/profile', ['username' => $member->user->username]) }}>{{ $member->user->username }}</a>

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"></svg>

                    @auth
                        @if (in_array(Auth::user()->id, $group->owners->pluck('id_user')->toArray()) || Auth::user()->isAdmin)
                            <a class='btn btn-outline-secondary kick_member_button' data-toggle="tooltip"
                                data-placement="bottom" title="Remove user from group"
                                data-idUser="{{ $member->user->id }}" data-idGroup="{{ $group->id }}">Kick</a>
                        @endif
                    @endauth
                </div>
            @endif
        @endforeach

        @auth
            @if (in_array(Auth::user()->id, $group->members->pluck('id_user')->toArray()))
                <button class='btn btn-outline-secondary w-100 mb-3 mt-3' id="leave_group_button"
                    data-idGroup="{{ $group->id }}" data-idUser="{{ Auth::user()->id }}">Leave
                    Group</button>
            @endif

        @endauth

    </div>

</nav>

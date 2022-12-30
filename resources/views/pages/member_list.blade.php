@extends('layouts.app')
@section('page_title', 'Group Member List')
@section('content')
    <h1 class="mt-4"><a href="/group/{{ $group->name }}">{{ $group->name }}</a> Member List </h1>


    <div class="d-flex justify-content-center mb-3 mt-5" data-placement="bottom"
        title="Choose what liked content you want to see">
        <h4 class="me-3">Members</h4>
        <label class="switch">
            <input type="checkbox" id="list_toggle_btn">
            <span class="slider round"></span>
        </label>
        <h4 class="ms-3">Owners</h4>
    </div>


    <div id="toggle_list_A">
        <h2>Members</h2>

        @if (sizeof($group->members) == sizeof($group->owners))
            <h4> There are no members yet </h4>
        @endif

        <div class="mt-4 mb-3 d-flex flex-wrap justify-content-center align-items-center">
            @foreach ($group->members as $member)
                @if (!in_array($member->id_user, $group->owners->pluck('id_user')->toArray()))
                    <div class="card mt-4 me-3" style="width: 15em;height:29em" id="friend_request_{{ $member->user->id }}">
                        <img height="50%" src="/{{ $member->user->photo }}" class="card-img-top" alt="User Profile Image">
                        <div class="card-body">
                            <h5 class="card-title friend_request_sender">
                                <a href="/profile/{{ $member->user->username }}"> {{ $member->user->username }}</a>
                            </h5>
                            <p class="card-text">{{ substr($member->user->bio, 0, 100) . '...' }}</p>
                        </div>

                        @auth
                            @if (Auth::user()->isAdmin || in_array(Auth::user()->id, $group->owners->pluck('id_user')->toArray()))
                                <div class="card-footer d-flex flex-wrap justify-content-center align-items-center bg-white">
                                    <span>
                                        <button class=" promoteToOwner btn btn-outline-primary w-40 p-3 me-3"
                                            data-toggle="tooltip" data-placement="bottom"
                                            title="Promote this member to an owner" style="min-width: 5em"
                                            data-idGroup="{{ $group->id }}" data-idUser="{{ $member->user->id }}">Promote
                                        </button>

                                        <a class='btn btn-outline-secondary kick_member_button w-40 p-3' style="min-width: 5em"
                                            data-toggle="tooltip" data-placement="bottom" title="Remove user from group"
                                            data-idUser="{{ $member->user->id }}" data-idGroup="{{ $group->id }}">Kick</a>
                                    </span>
                                </div>
                            @endif
                        @endauth

                    </div>
                @endif
            @endforeach

        </div>
    </div>

    <div hidden id="toggle_list_B">
        <h2 class="mb-5">Owners</h2>

        <div class="mt-4 mb-3 d-flex flex-wrap justify-content-center align-items-center">
            @foreach ($group->owners as $member)
                <div class="card mt-4 me-3" style="width: 15em;height:29em" id="friend_request_{{ $member->user->id }}">
                    <img height="50%" src="/{{ $member->user->photo }}" class="card-img-top" alt="User Profile Image">
                    <div class="card-body">
                        <h5 class="card-title friend_request_sender">
                            <a href="/profile/{{ $member->user->username }}"> {{ $member->user->username }}</a>
                        </h5>
                        <p class="card-text">{{ substr($member->user->bio, 0, 100) . '...' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>










@endsection

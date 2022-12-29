@extends('layouts.app')
@section('page_title','Group Member List')
@section('content')
<h1 class="mt-4"><a href="/group/{{ $group->name }}">{{ $group->name }}</a> Group List </h1>
<div id="timeline" class="d-flex flex-wrap justify-content-center align-items-center">

@if (sizeof($group->members)== sizeof($group->owners))
<h4> No members to promote </h4>
@endif

@foreach ($group->members as $member)
            @if (!in_array($member->id_user, $group->owners->pluck('id_user')->toArray()))
            <div class="card mt-4 me-3" style="width: 15em;height:29em"
                    id="friend_request_{{ $member->user->id }}">
                    <img height="50%" src="/{{ $member->user->photo }}" class="card-img-top"
                        alt="User Profile Image">
                    <div class="card-body">
                        <h5 class="card-title friend_request_sender">
                            <a href="/profile/{{ $member->user->username }}"> {{ $member->user->username }}</a>
                        </h5>
                        <p class="card-text">{{ substr($member->user->bio, 0, 100) . '...' }}</p>
                    </div>
                    <div class="card-footer d-flex flex-wrap justify-content-center align-items-center bg-white">
                        <span>
                         <button class=" promoteToOwner btn btn-primary btn-lg w-100" data-idGroup="{{$group->id}}" data-idUser="{{$member->user->id}}">  Promote 
</button>
                        </span>
                    </div>
                </div>
            @endif
        @endforeach

    </div>
@endsection

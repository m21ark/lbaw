@extends('layouts.app')

@section('page_title', $user->username.'`s Group List')

@section('content')
    <div class="justify-content-center align-items-center">

        @if (Auth::user()->username === $user->username)
            <h1 class="mt-4">My Group List</h1>
        @else
            <h1 class="mt-4"><a href="/profile/{{ $user->username }}">{{ $user->username }}</a> 's Group List </h1>
        @endif

        @if ($user->groupsOwner->count() + $user->groupsMember->count() === 0)
            <h2 class="text-center mt-5">{{ $user->username }} is not part of any groups</h2>
        @endif


        <div>

            @auth
                @if (Auth::user()->username === $user->username)
                    <button class='btn btn-primary w-100 mb-3 mt-3' onclick="window.scrollTo(0, 0);" id="popup_btn_group_create">Create Group</button>
                    @include('partials.popup.make_group_popup')
                @endif
            @endauth

            @if ($user->groupsOwner->count() > 0)
                <div class="mt-3 mb-4 container">
                    <h2>Groups Owned ({{ $user->groupsOwner->count() }})</h2>
                    <hr>
                    <div class="d-flex flex-wrap">
                        @foreach ($user->groupsOwner as $x)
                            <?php $group = $x->group; ?>
                            <div class="card mt-4 me-3" style="width: 15em;height:22em">
                                <img height="60%" alt="Group Profile Image" src="{{ asset($x->group->photo) }}"
                                    class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">{{ $x->group->name }}</h5>

                                </div>

                                <div
                                    class="card-footer d-flex flex-wrap justify-content-center align-items-center bg-white">
                                    <a href="/group/{{ $x->group->name }}" class="btn btn-primary w-100">Open Group</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div>
            @if ($user->groupsMember->count() > 0)
                <div class="mt-5 mb-4 container">
                    <h2>Groups Member ({{ $user->groupsMember->count() }})</h2>
                    <hr>
                    <div class="d-flex flex-wrap">
                        @foreach ($user->groupsMember as $x)
                            <div class="card mt-4 me-3" style="width: 15em;height:25em">
                                <img height="60%" alt="Group Profile Image" src="{{ asset($x->group->photo) }}"
                                    class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">{{ $x->group->name }}</h5>

                                </div>

                                <div
                                    class="card-footer d-flex flex-wrap justify-content-center align-items-center bg-white">
                                    <a href="/group/{{ $x->group->name }}" class="btn btn-primary w-100">Open Group</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection

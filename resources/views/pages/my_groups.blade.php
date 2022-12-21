@extends('layouts.app')

@section('content')
    <div class="justify-content-center align-items-center">

        <div>
            @auth
                <button class='btn btn-primary w-100 mb-3 mt-3' id="popup_btn_group_create">Create Group</button>
                @include('partials.popup.make_group_popup')
            @endauth
            @if (Auth::user()->groupsOwner->count() > 0)
                <div class="mt-3 mb-4 container">
                    <h2>Groups Owned ({{ Auth::user()->groupsOwner->count() }})</h2>
                    <hr>
                    <div class="d-flex flex-wrap">
                        @foreach (Auth::user()->groupsOwner as $x)
                            <?php $group = $x->group; ?>
                            <div class="card mt-4 me-3" style="width: 15em;height:22em">
                                <img height="60%" src="{{ asset($x->group->photo) }}" class="card-img-top" alt="user_avatar">
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
            @if (Auth::user()->groupsMember->count() > 0)
                <div class="mt-5 mb-4 container">
                    <h2>Groups Member ({{ Auth::user()->groupsMember->count() }})</h2>
                    <hr>
                    <div class="d-flex flex-wrap">
                        @foreach (Auth::user()->groupsMember as $x)
                            <div class="card mt-4 me-3" style="width: 15em;height:25em">
                                <img height="60%" src="{{ asset($x->group->photo) }}" class="card-img-top"
                                    alt="user_avatar">
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

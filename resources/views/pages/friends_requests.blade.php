@extends('layouts.app')



@section('content')
    @if (Auth::user()->username !== $user->username)
        <h1 class="mt-4"><a href="/profile/{{ $user->username }}">{{ $user->username }}</a> 's Friends </h1>
    @else
        <h1 class="mt-3">My Friends</h1>
    @endif

    <div id="timeline" class="d-flex flex-wrap justify-content-center align-items-center">

        @if ($requests->count() == 0 && !$isrequests)
            <h1 class="text-center mt-5" style="height: 1em;">No friends to show</h1>
        @elseif ($requests->count() == 0 && $isrequests)
            <h1 class="text-center mt-5" style="height: 1em;">No friend requests to show</h1>
        @endif

        @foreach ($requests as $requester)
            @if ($requester->acceptance_status == 'Pendent')
                <div class="card mt-4 me-3" style="width: 15em;height:29em" id="friend_request_{{ $requester->sender->id }}">
                    <img height="50%" src="/{{ $requester->sender->photo }}" class="card-img-top" alt="user_avatar">
                    <div class="card-body">
                        <h5 class="card-title friend_request_sender">
                            <a href="/profile/{{ $requester->sender->username }}"> {{ $requester->sender->username }}</a>
                        </h5>
                        <p class="card-text">{{ substr($requester->sender->bio, 0, 100) . '...' }}</p>
                    </div>
                    <div class="card-footer d-flex flex-wrap justify-content-center align-items-center bg-white">
                        <span data-sender="{{ $requester->sender->id }}">
                            <input type="button" class="btn btn-primary btn-lg friends_request_accept w-40 me-2"
                                value="Accept" id="acceptReq_{{ $requester->sender->id }}">
                            <input type="button"
                                class="btn btn-default btn-lg btn-outline-secondary friends_request_reject w-40"
                                value="Reject" id="rejectReq_{{ $requester->sender->id }}">
                        </span>
                    </div>
                </div>
            @elseif ($requester->acceptance_status == 'Accepted' && !$isrequests)
                <?php $friend = $requester->sender->id == $user->id ? $requester->receiver : $requester->sender; ?>
                <div class="card mt-4 me-3 " style="width: 15em;height:29em" id="friend_request_{{ $friend->id }}">
                    <img height="50%" src="/{{ $friend->photo }}" class="card-img-top" alt="user_avatar">
                    <div class="card-body">
                        <h5 class="card-title friend_request_sender">
                            <a href="/profile/{{ $friend->username }}"> {{ $friend->username }}</a>
                        </h5>
                        <p class="card-text">{{ substr($friend->bio, 0, 100) . '...' }}</p>
                    </div>

                    @if (Auth::user()->username === $user->username)
                        <div class="card-footer d-flex flex-wrap justify-content-center align-items-center bg-white">
                            <span data-sender="{{ $requester->sender->id }}">
                                <input type="button" class="btn btn-primary btn-lg cancel_friend w-100"
                                    value="Remove Friend" data-id="{{ $friend->id }}">
                            </span>
                        </div>
                    @else
                        <div class="card-footer d-flex flex-wrap justify-content-center align-items-center bg-white">
                            <span>
                                <a href="/profile/{{ $requester->sender->username }}" type="button"
                                    class="btn btn-primary btn-lg w-100">See Profile</a>
                            </span>
                        </div>
                    @endif

                </div>
            @endif
        @endforeach
    </div>
@endsection

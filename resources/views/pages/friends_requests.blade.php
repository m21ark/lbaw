@extends('layouts.app')



@section('content')
    <div id="timeline" class="d-flex flex-wrap justify-content-center align-items-center">
        @foreach ($requests as $requester)
            @if ($requester->acceptance_status == 'Pendent')
                <div class="card mt-4 me-3" style="width: 15em;height:29em" id="friend_request_{{ $requester->sender->id }}">
                    <img height="50%" src="/{{ $requester->sender->photo }}" class="card-img-top" alt="user_avatar">
                    <div class="card-body">
                        <h5 class="card-title friend_request_sender">
                            <a href="/profile/{{ $requester->sender->username }}"> {{ $requester->sender->username }}</a>
                        </h5>
                        <p class="card-text">{{ substr($requester->sender->bio, 0, 100) . '...' }}</p>
                        <span data-sender="{{ $requester->sender->id }}">
                            <input type="button" class="btn btn-primary btn-lg friends_request_accept" value="Accept"
                                id="acceptReq_{{ $requester->sender->id }}">
                            <input type="button"
                                class="btn btn-default btn-lg btn-outline-secondary friends_request_reject" value="Reject"
                                id="rejectReq_{{ $requester->sender->id }}">
                        </span>
                    </div>
                </div>
            @elseif ($requester->acceptance_status == "Accepted" && !$requests )
                <?php $friend = $requester->sender->id == $user->id ? $requester->receiver : $requester->sender ?>
                <div class="card mt-4 me-3 " style="width: 15em;height:29em" id="friend_request_{{ $friend->id }}">
                    <img height="50%" src="/{{ $friend->photo }}" class="card-img-top" alt="user_avatar">
                    <div class="card-body">
                        <h5 class="card-title friend_request_sender">
                            <a href="/profile/{{ $friend->username }}"> {{ $friend->username }}</a>
                        </h5>
                        <p class="card-text">{{ substr($friend->bio, 0, 100) . '...' }}</p>
                        <span data-sender="{{ $requester->sender->id }}">
                            <input type="button" class="btn btn-primary btn-lg cancel_friend" value="Cancel Friendship"
                                data-id="{{ $friend->id }}">
                        </span>
                    </div>
                </div>
            @endif

        @endforeach
    </div>
@endsection



@extends('layouts.app')



@section('content')
    <div id="timeline" class="d-flex flex-wrap justify-content-center align-items-center">
        @foreach ($user->friendsRequests as $requester)
            <div class="card mt-4 me-3" style="width: 15em;height:29em">
                <img height="50%" src="/{{$requester->sender->photo}}" class="card-img-top" alt="user_avatar" >
                <div class="card-body">
                    <h5 class="card-title friend_request_sender" data-sender="{{$requester->sender->id}}">
                        <a href="/profile/{{$requester->sender->username}}"> {{$requester->sender->username}}</a>
                    </h5>
                    <p class="card-text">{{substr($requester->sender->bio, 0, 100) . '...'}}</p>
                    <span>
                        <input type="button" class="btn btn-primary btn-lg" value="Accept">
                        <input type="button" class="btn btn-default btn-lg btn-outline-secondary" value="Reject">
                    </span>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@auth
@section('rightbar')
    @include('partials.sidebar.home_sidebar')
@endsection
@endauth

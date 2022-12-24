@extends('layouts.app')



@section('content')
    <div id="timeline" class="d-flex flex-wrap justify-content-center align-items-center">
        @foreach ($requests as $requester)
            @if ($requester->acceptance_status == 'Pendent')
                <div class="card mt-4 me-3" style="width: 15em;height:29em" id="group_request_{{ $requester->user->id }}">
                    <img height="50%" src="/{{ $requester->user->photo }}" class="card-img-top" alt="Requester Profile Image">
                    <div class="card-body">
                        <h5 class="card-title group_request_sender">
                            <a href="/profile/{{ $requester->user->username }}"> {{ $requester->user->username }}</a>
                        </h5>
                        <p class="card-text">{{ substr($requester->user->bio, 0, 100) . '...' }}</p>

                    </div>

                    <div class="card-footer d-flex flex-wrap justify-content-center align-items-center bg-white">
                        <span data-sender="{{ $requester->user->id }}">
                            <input type="button" class="btn btn-primary btn-lg groups_request_accept" value="Accept"
                                id="acceptReq_{{ $requester->user->id }}">
                            <input type="button" class="btn btn-default btn-lg btn-outline-secondary groups_request_reject"
                                value="Reject" id="rejectReq_{{ $requester->user->id }}">
                        </span>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection

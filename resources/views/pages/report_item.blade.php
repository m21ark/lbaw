@extends('layouts.app')

@section('content')
    <h2 class="mt-5">User Report</h2>
    <div class="d-flex justify-content-center">

        <div class="mt-4 p-4 card text-bg-light" style="width:50%;max-width:35em">
            <h3 id="username"><a class="text-decoration-none" href="/profile/{{ $user->username }}">{{ $user->username }}</a>
            </h3>
            <img class="profile_img rounded-circle" src="{{ asset($user->photo) }}" alt="" width="250">

            <h3>Bio</h3>
            <p class="card-text">{{ $user->bio }}</p>
        </div>

        <div class="mt-4 p-4 card text-bg-light" style="width:50%;max-width:35em">
            <?php $ban_date = $user->ban_date ?? 'N/A'; ?>
            <h4>Information</h4>

            <div class="list-group align-items-center mb-3 mt-3">

                <div class="d-flex list-group-item w-100">
                    <p class="pt-3">{{ $statistics['post_num'] }} Posts</p>
                </div>

                <div class="d-flex list-group-item w-100">
                    <p class="pt-3">{{ $statistics['friends_num'] }} Friends</p>
                </div>

                <div class="d-flex list-group-item w-100">
                    <p class="pt-3">{{ $statistics['group_num'] }} Groups</p>
                </div>

                <div class="d-flex list-group-item w-100">
                    <p class="pt-3">{{ $statistics['like_comment_num'] + $statistics['like_post_num'] }}
                        Likes</p>
                </div>

                <div class="d-flex list-group-item w-100">
                    <p class="pt-3">{{ $statistics['comment_num'] }} Comments</p>
                </div>

            </div>

            <hr>
            <p>Birthdate: {{ $user->birthdate }}</p>
            <p>Email: {{ $user->email }}</p>
            <p>Banned until: {{ $ban_date }}</p>
        </div>


    </div>

    <div class="mt-4 d-flex justify-content-evenly">
        <a href="#!" class="btn btn-warning p-3">
            <h5>Reject all reports</h5>
        </a>

        <a href="#!" class="btn btn-danger p-3">
            <h5>Ban User</h5>
        </a>
    </div>


    <div class="mt-4 text-bg-light p-4">
        <h3>Reports</h3>
        @foreach ($reports as $report)
            <div class="card p-3 mb-4">
                <h5>Made:</h5>
                <p>{{ $report->report_date }} by <a
                        href="/profile/{{ $report->reporter->username }}">{{ $report->reporter->username }}</a></p>

                <hr>
                <h5>Report description:</h5>
                <p>{{ $report->description }}</p>

                <hr>
                <h5>Reported content:</h5>

                @if ($report->id_post === null)
                    <p>comment</p>
                @else
                    @include('partials.post_item', ['post' => $report->post])
                @endif

                <hr>
                <h5>Other information:</h5>
                <ul>
                    <li>Decision: {{ $report->decision }}</li>
                    <li>Decision date: {{ $report->decision_date ?? 'N/A' }}</li>
                    <li>Decision made by: {{ $report->id_admin ?? 'N/A' }}</li>
                </ul>

                <a href="#!" class="mt-3 btn btn-outline-danger" style="width: 20%;margin:auto">Remove</a>
            </div>
        @endforeach
    </div>
@endsection

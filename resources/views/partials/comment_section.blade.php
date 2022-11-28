@foreach ($comments as $comment)
    @if ($comment->id_parent !== null)
        @continue
    @endif

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-center">
            @auth
                @if (Auth::user()->id == $comment->id_commenter)
                    <a href="#!" data-id="{{ $comment->id }}" data-text="{{ $comment->text }}"
                        class="text-decoration-none popup_btn_comment_edit me-4">🖉</a>
                @endif
            @endauth
            <small>{{ $comment->post_date }}</small>
            @auth
                @if ($comment->poster->id !== Auth::user()->id)
                    <a href="#" class="text-decoration-none comment_reply_btn" data-id="{{ $comment->id }}"
                        data-username="{{ $comment->poster->username }}">Reply</a>
                @endif
            @endauth
        </div>
        <div class="card-header d-flex justify-content-around align-items-center">
            <img href="/profile/{{ $comment->poster->username }}" src="/{{ $comment->poster->photo }}" alt=""
                width="50" class="rounded-circle">
            <a class="text-decoration-none"
                href="/profile/{{ $comment->poster->username }}">{{ $comment->poster->username }}</a>
        </div>

        <div class="card-body">
            <p class="card-text">{{ $comment->text }}</p>
        </div>

        <div class="pt-3 card-footer d-flex justify-content-center ">
            <p class="like_count mt-1 me-3" style="font-size:1.3em;">{{ $comment->likes->count() }}</p>
            @auth
                <a class="like_btn_comment text-decoration-none " data-uid={{ Auth::user()->id }}
                    data-id={{ $comment->id }} href="#!">

                    <?php
                    $userLiked = false;
                    foreach ($comment->likes as $like) {
                        if (Auth::user()->id == $like->id_user) {
                            $userLiked = true;
                        }
                    }
                    ?>

                    @if ($userLiked)
                        <h3 class="me-1 p-1" data-liked='1' style="font-size:1.2em;">&#x2764;</h3>
                    @else
                        <h3 class="me-1  p-1" data-liked='0' style="font-size:1.5em;">&#9825;</h3>
                    @endif
                </a>
            @else
                <a class="like_btn_comment text-decoration-none">
                    <h2 class="me-1"><strong>&#9825;</strong></h2>
                </a>
            @endauth
        </div>

        @if (sizeof($comment->replies) > 0)
            <div class="comment_reply_section">
                <h4 class="mt-4">Replies</h4>
                @foreach ($comment->replies as $reply)
                    <div class="card mb-4 comment_reply">

                        <div class="card-header d-flex justify-content-center">
                            @auth
                                @if (Auth::user()->id == $reply->id_commenter)
                                    <a href="#!" data-id="{{ $reply->id }}" data-text="{{ $reply->text }}"
                                        class="text-decoration-none popup_btn_comment_edit me-4">🖉</a>
                                @endif
                            @endauth
                            <small>{{ $reply->post_date }}</small>

                        </div>
                        <div class="card-header d-flex justify-content-around align-items-center">
                            <img href="/profile/{{ $reply->poster->username }}" src="/{{ $reply->poster->photo }}"
                                alt="" width="50" class="rounded-circle">
                            <a class="text-decoration-none"
                                href="/profile/{{ $reply->poster->username }}">{{ $reply->poster->username }}</a>
                        </div>

                        <div class="card-body">
                            <p class="card-text">{{ $reply->text }}</p>
                        </div>

                        <div class="pt-3 card-footer d-flex justify-content-center ">
                            <p class="like_count mt-1 me-3" style="font-size:1.3em;">{{ $reply->likes->count() }}</p>
                            @auth
                                <a class="like_btn_comment text-decoration-none " data-uid={{ Auth::user()->id }}
                                    data-id={{ $reply->id }} href="#!">

                                    <?php
                                    $userLiked = false;
                                    foreach ($reply->likes as $like) {
                                        if (Auth::user()->id == $like->id_user) {
                                            $userLiked = true;
                                        }
                                    }
                                    ?>

                                    @if ($userLiked)
                                        <h3 class="me-1 p-1" data-liked='1' style="font-size:1.2em;">&#x2764;</h3>
                                    @else
                                        <h3 class="me-1  p-1" data-liked='0' style="font-size:1.5em;">&#9825;</h3>
                                    @endif
                                </a>
                            @else
                                <a class="like_btn_comment text-decoration-none">
                                    <h2 class="me-1"><strong>&#9825;</strong></h2>
                                </a>
                            @endauth
                        </div>


                    </div>
                @endforeach
            </div>
        @endif



    </div>
@endforeach

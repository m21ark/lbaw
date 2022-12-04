@foreach ($comments as $comment)
    @if ($comment->id_parent !== null)
        @continue
    @endif
    <div class="card mb-4">
        <a href="#!" class="btn popup_btn_report_comment_create" data-id="{{ $comment->id }}">Report</a>
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
                    <a href="#" class="text-decoration-none comment_reply_btn ms-4" data-id="{{ $comment->id }}"
                        data-username="{{ $comment->poster->username }}"><svg xmlns="http://www.w3.org/2000/svg"
                            width="30" height="30" fill="currentColor" class="bi bi-reply-fill" viewBox="0 0 16 16">
                            <path
                                d="M5.921 11.9 1.353 8.62a.719.719 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.719V6c1.5 0 6 0 7 8-2.5-4.5-7-4-7-4v1.281c0 .56-.606.898-1.079.62z" />
                        </svg></a>
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

            @if (sizeof($comment->replies) > 0)
                <a class="ms-4 reveal_comment_replies text-decoration-none" data-id="{{ $comment->id }}"
                    href="#!"><span class="me-3"
                        style="font-size: 1.3em">{{ sizeof($comment->replies) }}</span><svg
                        xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor"
                        class="bi bi-menu-up" viewBox="0 0 16 16">
                        <path
                            d="M7.646 15.854a.5.5 0 0 0 .708 0L10.207 14H14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h3.793l1.853 1.854zM1 9V6h14v3H1zm14 1v2a1 1 0 0 1-1 1h-3.793a1 1 0 0 0-.707.293l-1.5 1.5-1.5-1.5A1 1 0 0 0 5.793 13H2a1 1 0 0 1-1-1v-2h14zm0-5H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v2zM2 11.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 0-1h-8a.5.5 0 0 0-.5.5zm0-4a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 0-1h-11a.5.5 0 0 0-.5.5zm0-4a.5.5 0 0 0 .5.5h6a.5.5 0 0 0 0-1h-6a.5.5 0 0 0-.5.5z" />
                    </svg></a>
            @endif

        </div>

        @if (sizeof($comment->replies) > 0)
            <div class="comment_reply_section" id="comment_reply_section_{{ $comment->id }}" hidden>
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

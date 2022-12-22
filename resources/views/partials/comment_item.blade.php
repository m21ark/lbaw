<div class="card mb-4 comment_item">
    <div class="card-header d-flex justify-content-evenly align-items-center">
        @auth
            @if (Auth::user()->id == $comment->id_commenter)
                <a href="#!" data-id="{{ $comment->id }}" data-text="{{ $comment->text }}"
                    class="pt-1 text-decoration-none popup_btn_comment_edit ">
                    <h4><i class="fa-solid fa-pencil text-primary"></i></h4>
                </a>
            @else
                <a href="#!" class="pt-2 btn popup_btn_report_comment_create" data-id="{{ $comment->id }}">
                    <h4><i class=" fa-solid fa-flag text-primary"></i></h4>
                </a>
            @endif
        @endauth

        <small>{{ $comment->post_date }}</small>
        @auth
            @if ($comment->poster->id !== Auth::user()->id)
                <a href="#" class="pt-2 text-decoration-none comment_reply_btn" data-id="{{ $comment->id }}"
                    data-username="{{ $comment->poster->username }}">
                    <h3><i class="fa-solid fa-reply"></i></h3>
                </a>
                </a>
            @endif
        @endauth
    </div>

    <div class="card-header d-flex justify-content-center align-items-center">
        <img href="/profile/{{ $comment->poster->username }}" src="/{{ $comment->poster->photo }}" alt=""
            width="50" class="rounded-circle me-5">
        <a class="text-decoration-none"
            href="/profile/{{ $comment->poster->username }}">{{ $comment->poster->username }}</a>
    </div>

    <div class="card-body">
        <p class="card-text">{{ $comment->text }}</p>
    </div>

    <div class="pt-3 card-footer d-flex justify-content-center ">

        @auth

            <?php
            $userLiked = false;
            foreach ($comment->likes as $like) {
                if (Auth::user()->id == $like->id_user) {
                    $userLiked = true;
                }
            }
            ?>

            <a class="text-decoration-none " data-uid={{ Auth::user()->id }} onclick="sendLikeCommentRequest(event)"
                data-id={{ $comment->id }} data-liked="{{ $userLiked }}" href="#!">

                <span class="me-3 text-dark" style="font-size:1.5em;">{{ $comment->likes->count() }}</span>


                @if ($userLiked)
                    <span style="font-size: 1.7em">
                        <i class="fa-solid fa-heart text-danger"></i>
                    </span>
                @else
                    <span style="font-size: 1.7em">
                        <i class="fa-regular fa-heart"></i>
                    </span>
                @endif
            </a>
        @else
            <a class="text-decoration-none">
                <span class="me-3 text-dark" style="font-size:1.5em;">{{ $comment->likes->count() }}</span>
                <span style="font-size: 1.7em">
                    <i class="fa-regular fa-heart"></i>
                </span>
            </a>
        @endauth

        <span class="me-5"></span>

        @if (sizeof($comment->replies) > 0)
            <a class="ms-5 reveal_comment_replies text-decoration-none" data-id="{{ $comment->id }}" href="#!">

                <span class="mt-1 me-3 text-dark" style="font-size:1.5em">{{ sizeof($comment->replies) }}</span>

                <span class="me-3" style="font-size: 1.7em">
                    <i class="ms-3 fa-regular fa-comment-dots"></i>
                </span>


            </a>
        @endif

    </div>

    @if (sizeof($comment->replies) > 0)
        <div class="comment_reply_section" id="comment_reply_section_{{ $comment->id }}" hidden>
            <h4 class="mt-4 mb-2">Replies</h4>
            @foreach ($comment->replies as $reply)
                <div class="card mb-4 comment_reply">

                    <div class="card-header d-flex justify-content-evenly align-items-center">
                        @auth
                            @if (Auth::user()->id == $reply->id_commenter)
                                <a href="#!" data-id="{{ $reply->id }}" data-text="{{ $reply->text }}"
                                    class="pt-1 text-decoration-none popup_btn_comment_edit ">
                                    <h4><i class="fa-solid fa-pencil text-primary"></i></h4>
                                </a>
                            @else
                                <a href="#!" class="pt-2 btn popup_btn_report_comment_create"
                                    data-id="{{ $reply->id }}">
                                    <h4><i class=" fa-solid fa-flag text-primary"></i></h4>
                                </a>
                            @endif
                        @endauth

                        <small>{{ $reply->post_date }}</small>

                    </div>

                    <div class="card-header d-flex justify-content-center align-items-center">
                        <img href="/profile/{{ $reply->poster->username }}" src="/{{ $reply->poster->photo }}"
                            alt="" width="50" class="rounded-circle me-5">
                        <a class="text-decoration-none"
                            href="/profile/{{ $reply->poster->username }}">{{ $reply->poster->username }}</a>
                    </div>

                    <div class="card-body">
                        <p class="card-text">{{ $reply->text }}</p>
                    </div>

                    <div class="pt-3 card-footer d-flex justify-content-center ">

                        @auth

                            <?php
                            $userLiked = false;
                            foreach ($reply->likes as $like) {
                                if (Auth::user()->id == $like->id_user) {
                                    $userLiked = true;
                                }
                            }
                            ?>

                            <a class="text-decoration-none " data-uid={{ Auth::user()->id }}
                                onclick="sendLikeCommentRequest(event)" data-id={{ $reply->id }}
                                data-liked="{{ $userLiked }}" href="#!">

                                <span class="me-3 text-dark" style="font-size:1.5em;">{{ $reply->likes->count() }}</span>

                                @if ($userLiked)
                                    <span style="font-size: 1.7em">
                                        <i class="fa-solid fa-heart text-danger"></i>
                                    </span>
                                @else
                                    <span style="font-size: 1.7em">
                                        <i class="fa-regular fa-heart"></i>
                                    </span>
                                @endif
                            </a>
                        @else
                            <a class="text-decoration-none">
                                <span style="font-size: 1.7em">
                                    <i class="fa-regular fa-heart"></i>
                                </span>
                            </a>
                        @endauth

                    </div>



                </div>
            @endforeach
        </div>
    @endif



</div>

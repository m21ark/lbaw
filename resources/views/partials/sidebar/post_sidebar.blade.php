<nav id="rightbar" class="text-bg-light">
    <h3 class="mb-4">Comment Section</h3>

    @include('partials.popup.edit_comment_popup')
    @foreach ($post->comments as $comment)
        <div class="card border-secondary mb-4">

            <div class="card-header d-flex justify-content-center">
                @auth
                    @if (Auth::user()->id == $comment->id_commenter)
                        <a href="#!" data-id={{ $comment->id }} data-text="{{ $comment->text }}"
                            class="text-decoration-none popup_btn_comment_edit me-4">🖉</a>
                    @endif
                @endauth
                <small>{{ $comment->post_date }}</small>
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

            <div class="card_footer form-control d-flex align-items-center ">
                <input type="text" name="comment_reply" class="me-2 form-control mt-3 mb-2 comment_reply"
                    placeholder="Reply to this comment">
                <a href="#!" class="btn btn-primary comment_reply_send">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-send" viewBox="0 0 16 16">
                        <path
                            d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z" />
                    </svg>
                </a>
            </div>

        </div>
    @endforeach


</nav>

<!--
        if ($like->user_id == Auth::user()->id){
                            $userLiked = true;
                            break;
                        }
-->

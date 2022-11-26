<nav id="rightbar" class="text-bg-light">
    <h3 class="mb-4">Comment Section</h3>


    @foreach ($post->comments as $comment)
        <div class="card border-secondary mb-4">

            <div class="card-header text-center">
                <small>{{ $comment->post_date }}</small>
            </div>
            <div class="card-header d-flex justify-content-around align-items-center">
                <img href="/profile/{{ $comment->poster->username }}" src="/{{ $comment->poster->photo }}" alt=""
                    width="50">
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
                        data-id={{ $comment->id }} href="#">

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

        </div>
    @endforeach


</nav>

<!--
        if ($like->user_id == Auth::user()->id){
                            $userLiked = true;
                            break;
                        }
-->

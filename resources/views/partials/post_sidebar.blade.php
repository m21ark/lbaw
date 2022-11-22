<nav id="rightbar" class="text-bg-light">
    <h3 class="mb-4">Comment Section</h3>


    @foreach ($post->comments as $comment)
        <div class="card border-secondary mb-4">

            <div class="card-header text-center">
                <small>{{ $comment->post_date }}</small>
            </div>
            <div class="card-header d-flex justify-content-around align-items-center">
                <img href="/profile/{{ $comment->poster->username }}" src="/{{$comment->poster->photo}}" alt="" width="50">
                <a class="text-decoration-none"
                    href="/profile/{{ $comment->poster->username }}">{{ $comment->poster->username }}</a>
            </div>

            <div class="card-body">
                <p class="card-text">{{ $comment->text }}</p>
            </div>

            <div class="pt-3 card-footer d-flex justify-content-around ">
                <p class="like_count mt-2"><strong>{{ $comment->likes->count() }} Likes</strong></p>
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
                            <h4 class="p-1">&#x2764;</h4>
                        @else
                            <h2 class="me-1"><strong>&#9825;</strong></h2>
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

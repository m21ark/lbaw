<nav id="rightbar" class="text-bg-light">
    <h3 class="mb-4">Comment Section</h3>


    @foreach ($post->comments as $comment)
        <div class="card border-secondary mb-4">

            <div class="card-header text-center">
                <small>{{ $comment->post_date }}</small>
            </div>
            <div class="card-header d-flex justify-content-around align-items-center">
                <img href="/profile/{{ $comment->user->username }}" src="../user.png" alt="" width="50">
                <a class="text-decoration-none"
                    href="/profile/{{ $comment->user->username }}">{{ $comment->user->username }}</a>
            </div>


            <div class="card-body">
                <p class="card-text">{{ $comment->text }}</p>
            </div>

            <div class="pt-3 card-footer d-flex justify-content-around ">
                <p class="like_count mt-2"><strong>{{ $comment->likes->count() }} Likes</strong></p>
                <a class="text-decoration-none btn" href="#">
                    <h4>&#x2764;</h4>
                </a>
            </div>

        </div>
    @endforeach


</nav>

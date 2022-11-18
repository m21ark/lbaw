<nav id="rightbar" class="text-bg-light">
    <h3 class="mb-4">Comment Section</h3>


    @foreach ($post->comments as $comment)
        <div class="card border-secondary mb-4">

            <div class="card-header d-flex justify-content-between align-items-center">
                <img src="../user.png" alt="" width="50">
                <p>Username</p>
                <p>Date</p>
            </div>

            <div class="card-body">

                <p class="card-text">{{ $comment->text }}</p>



            </div>

            <div class="card-footer d-flex justify-content-between">
                <p class="like_count">{{ $comment->likes->count() }} Likes</p>
                <a href="#">&#x2764;</a>
            </div>

        </div>
    @endforeach


</nav>

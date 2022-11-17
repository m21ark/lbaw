<nav id="rightbar">
    <h3>Comment Section</h3>

    <div class="might_know">

        <ul>

            @foreach ($post->comments as $comment)
                <li>
                    <article class="comment_item">

                        <img src="../user.png" alt="" width="50">
                        <p>{{ $comment->username }}</p>
                        <p>{{ $comment->date }}</p>

                        <p class="comment_text">{{ $comment->text }}</p>

                        <p class="like_count">{{ $comment->likes->count() }} Likes</p>
                        <a href="#">&#x2764;</a>
                    </article>
                </li>
            @endforeach
        </ul>
    </div>


</nav>

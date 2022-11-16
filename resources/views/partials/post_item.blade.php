<article class="post">
    <div class="post_head">
        <a href={{ url('/profile/' . $post->owner->username) }}><img src="../user.png" alt="" width="50"></a>
        <a href={{ url('/profile/' . $post->owner->username) }}>{{$post->owner->username}}</a>
        <a href={{ url('/messages') }}><span class="shareicon">&lt;</span></a>
        <a href={{ url('/post/{$post->id}') }}>&vellip;</a>
    </div>

    <div class="post_body">
        <p>{{$post->text}}</p>
        <img src="../post.jpg" alt="" width="400">
    </div>

    <div class="post_footer">

        <p>{{$post->likes_count}}</p>
        <a href="#"><span class="likeicon">&#128077;</span></a>

        <p>{{$post->comments_count}}</p>
        <a href="#"><span class="commenticon">&#128172;</span></a>

        <p>{{$post->post_date}}</p>


    </div>

</article>

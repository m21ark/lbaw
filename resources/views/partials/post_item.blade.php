<div class="container mt-5 mb-5 post_item">
    <div class="row d-flex align-items-center justify-content-center ">
        <div>
            <div class="card post_card">

                <div class="card-header d-flex justify-content-between p-2 px-3">

                    <a href={{ url('/profile/' . $post->owner->username) }}
                        class="text-decoration-none d-flex flex-row align-items-center">
                        <img src="/../user.png" width="60" class="rounded-circle me-3">
                        <strong class="font-weight-bold">{{ $post->owner->username }}</strong>
                    </a>

                    <small class="me-5">{{ $post->post_date }}</small>



                    <div class="dropdown">
                        <button class="btn dropdownPostButton" type="button">&vellip;</button>
                        <div class="dropdown_menu " hidden>
                            <a class="dropdown-item" href="{{ url('/profile/' . $post->owner->username) }}">Go to
                                Profile</a>

                            @auth
                                @if (Auth::user()->id == $post->owner->id)
                                    <a class="dropdown-item" href="/profile/{{ $post->owner->id }}">Edit Post</a>
                                    <a class="dropdown-item" href="#">Delete Post</a>
                                @else
                                    <a class="dropdown-item" href="#">Send Message</a>
                                @endif
                            @endauth
                        </div>
                    </div>



                </div>

                <!-- TODO: Ver imagens da database -->
                <img src="/../post.jpg" class="img-fluid">

                <div class="p-2">
                    <p class="text-justify">{{ $post->text }}</p>


                    <div class="card-footer d-flex justify-content-evenly">

                        <!-- TODO: Aqui devia se passar a contagem da database e n o array completo -->
                        <div class="d-flex">
                            <p class="me-3">{{ sizeof($post->likes) }}</p>
                            <a href="#" class="text-decoration-none"><span class="likeicon">&#128077;</span></a>

                        </div>
                        <div class="d-flex">
                            <p class="me-3">{{ sizeof($post->comments) }}</p>
                            <a href="#" class="text-decoration-none"><span
                                    class="commenticon">&#128172;</span></a>
                        </div>

                    </div>


                </div>


            </div>
        </div>
    </div>
</div>

<div class="container mt-5 mb-5 post_item">
    <div class="row d-flex align-items-center justify-content-center ">
        <div>
            <div class="card">

                <div class="card-header d-flex justify-content-between p-2 px-3">

                    <a href="#" class="text-decoration-none d-flex flex-row align-items-center">
                        <img src="/../user.png" width="60" class="rounded-circle me-3">
                        <strong class="font-weight-bold">{{ $post->owner->username }}</strong>
                    </a>

                    <small class="me-5">{{ $post->post_date }}</small>
                    <div class="dropdown">
                        <button class="btn dropdownPostButton" type="button">:</button>
                        <div class="dropdown_menu " hidden>
                            <a class="dropdown-item" href="{{ url('/profile/' . $post->owner->username) }}">Go to
                                Profile</a>
                            @if (Auth::user()->id == $post->owner->id)
                                <a class="dropdown-item" href="#">Edit Post</a>
                                <a class="dropdown-item" href="#">Delete Post</a>
                            @else
                                <a class="dropdown-item" href="#">Send Message</a>
                            @endif


                        </div>
                    </div>


                </div>

                <img src="https://i.imgur.com/xhzhaGA.jpg" class="img-fluid">

                <div class="p-2">
                    <p class="text-justify">{{ $post->text }}</p>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-row icons d-flex align-items-center"> <i class="fa fa-heart"></i> <i
                                class="fa fa-smile-o ml-2"></i> </div>
                        <div class="d-flex flex-row muted-color"> <span>2 comments</span> <span
                                class="ml-2">Share</span> </div>
                    </div>


                </div>


            </div>
        </div>
    </div>
</div>

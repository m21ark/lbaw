<nav id="rightbar" class="text-bg-light">
    <h2>Recommendations</h2>
    <hr>

    <h3 class="mt-4 mb-3">Hot Topics</h3>
    <div class="list-group align-items-center mb-5 expand_tag_a">
        <a href="{{ url('/search/query') }}" class="list-group-item p-3">Topic 1</a>
        <a href="{{ url('/search/query') }}" class="list-group-item p-3">Topic 2</a>
        <a href="{{ url('/search/query') }}" class="list-group-item p-3">Topic 3</a>
        <a href="{{ url('/search/query') }}" class="list-group-item p-3">Topic 4</a>
    </div>

    <h3 class="mb-3">Might Know</h3>

    <div class="list-group align-items-center d-flex mb-5 ">

        <div class="list-group-item">
            <img class="me-3" src="{{asset('user/user.jpg')}}" alt="user_avatar" width="50">
            <a class="me-3" href={{ url('/profile/username') }}>Username</a>
            <a href="#" class="btn btn-outline-primary">Follow</a>
        </div>

        <div class="list-group-item">
            <img class="me-3" src="{{asset('user/user.jpg')}}" alt="user_avatar" width="50">
            <a class="me-3" href={{ url('/profile/username') }}>Username</a>
            <a href="#" class=" btn btn-outline-primary">Follow</a>
        </div>

        <div class="list-group-item">
            <img class="me-3" src="{{asset('user/user.jpg')}}" alt="user_avatar" width="50">
            <a class="me-3" href={{ url('/profile/username') }}>Username</a>
            <a href="#" class=" btn btn-outline-primary">Follow</a>
        </div>


    </div>

</nav>

<nav id="rightbar" class="text-bg-light">
    <h2 class="mb-5">Recommendations</h2>

    <h3 class="mb-3">Hot Topics</h3>
    <div class="list-group align-items-center  mb-5">
        <a href="{{ url('/search/query') }}" class="list-group-item p-3">Topic 1</a>
        <a href="{{ url('/search/query') }}" class="list-group-item p-3">Topic 2</a>
        <a href="{{ url('/search/query') }}" class="list-group-item p-3">Topic 3</a>
        <a href="{{ url('/search/query') }}" class="list-group-item p-3">Topic 4</a>
    </div>

    <h3 class="mb-3">Hot Topics</h3>

    <div class="list-group align-items-center d-flex mb-5">

        <div class="list-group-item">
            <img src="../user.png" alt="user_avatar" width="50">
            <a href={{ url('/profile/username') }}>Username</a>
            <a href="#" class="btn btn-primary">Follow</a>
        </div>

        <div class="list-group-item">
            <img src="../user.png" alt="user_avatar" width="50">
            <a href={{ url('/profile/username') }}>Username</a>
            <a href="#" class="btn btn-primary">Follow</a>
        </div>

        <div class="list-group-item">
            <img src="../user.png" alt="user_avatar" width="50">
            <a href={{ url('/profile/username') }}>Username</a>
            <a href="#" class="btn btn-primary">Follow</a>
        </div>


    </div>

</nav>

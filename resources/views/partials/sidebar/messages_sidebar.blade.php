<nav id="rightbar" class="text-bg-light">
    <h2>Recent Messages</h2>
    <hr>

    <div class="list-group w-auto">

        @foreach ($user->getContactedUsers() as $message)
            <?php $name = $message->id_sender === Auth::user()->id ? $message->receiver->username : $message->sender->username?>
            <a href="/messages/{{$name}}" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                <img src="/{{ $message->id_sender === Auth::user()->id ? $message->receiver->photo : $message->sender->photo}}" alt="user_avatar" width="50" height="50">
                <div class="d-flex gap-2 w-100 justify-content-between">
                    <div>
                        <h6 class="mb-0">{{$name}}</h6>
                        <p class="mb-0 opacity-75">{{$message->text}}</p>
                    </div>
                    <small class="opacity-50 text-nowrap">{{date( 'W', strtotime(date('Y-m-d H:i:s')) ) - date( 'W', strtotime( $message->date ) );}}w</small>
                </div>
            </a>
        @endforeach
    </div>

    <a class="mt-3 btn link_button" href={{ url('/search/username') }} style="font-size:1.4em">Find more friends</a>


</nav>

<nav id="rightbar" class="text-bg-light">
    <h2>Recent Messages</h2>

    <div class="mt-4 list-group w-auto">
        <div class="w-100 m-auto">
            @foreach ($user->getContactedUsers() as $message)
                <?php $name = $message->id_sender === Auth::user()->id ? $message->receiver->username : $message->sender->username; ?>
                <a href="/messages/{{ $name }}" class="list-group-item list-group-item-action d-flex gap-3 py-3"
                    aria-current="true">
                    <img class="rounded-circle"
                        src="/{{ $message->id_sender === Auth::user()->id ? $message->receiver->photo : $message->sender->photo }}"
                        alt="Message Contact Profile Image" width="50" height="50">
                    <div class="d-flex gap-2 w-100 justify-content-between">
                        <div>
                            <h6 class="mb-0">{{ $name }}</h6>
                            <p class="mb-0 opacity-75" style="max-width: 20em;overflow:hidden">{{ $message->text }}</p>
                        </div>
                        <small
                            class="opacity-50 text-nowrap">{{ Carbon\Carbon::parse($message->date)->diffForHumans() }}</small>
                    </div>
                </a>
            @endforeach
        </div>
    </div>



</nav>

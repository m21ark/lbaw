<div class="message_body overflow-auto">

    <?php 
        $messageGroupDay = [];
        foreach($messages as $message) { 
            $messageGroupDay[Str::substr($message->date, 0, 10)][] = $message;
        }
        ?>

    @foreach ($messageGroupDay as $date=>$messageInDay)
        <div class="divider d-flex align-items-center mb-4">
            <p class="text-center mx-3 mb-0" style="color: #a2aab7;">{{$date}}</p>
        </div>

        <?php 
            $separateBySender = [];
            $separateBySender[0][0] = $messageInDay[0];
            $j = 0;
            for ($i=1; $i < count($messageInDay); $i++) { 
                if ($messageInDay[i].id_sender !== $messageInDay[i-1].id_sender) {
                    $j++;
                }
                $separateBySender[$j][] = $messageInDay[i];
            }
        ?>

        @foreach ($separateBySender as $messages)
            <div class="d-flex flex-row {{$message->id_sender === Auth::user()->id ? 'justify-content-start' : 'justify-content-end' }}}}">
                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3-bg.webp"
                  alt="avatar 1" style="width: 45px; height: 100%;">
                <div>
                    @foreach ($messages as $message)
                              <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">{{$message->text}}</p>
                    @endforeach
                    <p class="small ms-3 mb-3 rounded-3 text-muted">{{Str::substr($message->date, 10, 10)}}</p>
                </div>
            </div>
        @endforeach


    @endforeach


</div>

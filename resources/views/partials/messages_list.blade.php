<div class="message_body">

    @foreach ($messages as $message)
        <article class="message_txt {{$message->id_sender === Auth::user()->id ? 'text_sender' : 'text_rcv'}}">
            <p>{{$message->date}}</p>
            <p>{{$message->text}}</p>
        </article>
    @endforeach


</div>

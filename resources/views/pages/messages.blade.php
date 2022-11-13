@extends('layouts.app')

@section('content')
    <div id="message_frame">

        <div class="message_head">
            <a href="profile.html"><img src="../user.png" alt="" width="60"></a>
            <a href="profile.html">Username</a>
            <a href="#">&vellip;</a>
        </div>

        @include('partials.messages_list')

        <div class="message_footer">
            <input type="text" name="sms_input" id="sms_input" placeholder="Type something...">
            <a href="#">&nearr;</a>
        </div>


    </div>
@endsection

@section('rightbar')
    @include('partials.messages_sidebar')
@endsection

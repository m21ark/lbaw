@extends('layouts.app')

@section('page_title', 'Messages')

@section('content')

    <div style="visibility: hidden" class="float shadow-lg" id="list_toggle_btn_div">
        <label for="list_toggle_btn"><i class="fa fa-bars my-float"></i></label>
        <input style="display: none" type="checkbox" id="list_toggle_btn">
    </div>

    <div id="toggle_list_A">
        <div id="message_frame" class="card">
            @if ($sender != null)
                <div class="message_head card-header text-bg-primary">
                    <a class="white_span_a" href="/profile/{{ $sender->username }}" id='sms_rcv'
                        data-id="{{ $sender->id }}">
                        <img id="sms_photo" src="/{{ $sender->photo }}" alt="Contact Profile Image" width="50"
                            class="me-4 rounded-circle">
                        <span>{{ $sender->username }}</span>
                    </a>
                    <a id="#videoCall" onclick="callUser({{ $sender->id }})">
                        <span id="video_call_span"><i class="fa-solid fa-video me-2"></i></span>
                    </a>
                </div>

                @include('partials.messages_list')

                <div class="card-footer text-bg-light d-flex ">
                    <input type="text" id="sms_input" class="form-control me-5" placeholder="Type something...">
                    <a href="#" id="sms_send_btn">
                        <i class="fa-solid fa-paper-plane"></i>
                    </a>
                </div>
            @else
                <img src="/Illustration 18.png" alt="No Conversation Selected Art" width="100%" id="default_img">
            @endif

        </div>
    @endsection


    @section('rightbar')
        <div id="toggle_list_B">
            @include('partials.sidebar.messages_sidebar')
        </div>
    @endsection

@extends('layouts.app')

@section('content')
    <div id="message_frame" class="card">

        <div class="message_head card-header text-bg-primary">
            <a href="#"><img src="../user.png" alt="" width="60"></a>
            <a href="#">Username</a>
            <a href="#">&vellip;</a>
        </div>

        @include('partials.messages_list')

        <div class="card-footer text-bg-light d-flex ">
            <input type="text" id="sms_input" class="form-control me-5" placeholder="Type something...">
            <a href="#" id="sms_send_btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send"
                    viewBox="0 0 16 16">
                    <path
                        d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z" />
                </svg>
            </a>
        </div>


    </div>
@endsection

@section('rightbar')
    @include('partials.messages_sidebar')
@endsection

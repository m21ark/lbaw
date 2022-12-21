@extends('layouts.app')

@section('content')
    <h2 class="mt-4 mb-4">Contacts</h2>
    <p>Portuguese Customer Help <a href="#">+351 912345678</a></p>
    <p>English Customer Help <a href="#">+44 912345678</a></p>
    <p>French Customer Help <a href="#">+33 912345678</a></p>


    <h3 class="mt-5 mb-3">Team Members</h3>
    <div class="list-group mx-3 my-2">
        <div class="list-group-item pb-3">
            <div class="text-bg-light d-flex align-items-center mb-3">
                <img src="{{ asset('user/user.jpg') }}" alt="user_avatar" width="50" class="me-4">
                <h3>David</h3>
            </div>
            <p>
            <p>Third year Software Engineering student working as a part-time web developer. Specializes in HTML/CSS.</p>
        </div>


        <div class="list-group-item pb-3">
            <div class="text-bg-light d-flex align-items-center mb-3">
                <img src="{{ asset('user/user.jpg') }}" alt="user_avatar" width="50" class="me-4">
                <h3>João</h3>
            </div>
            <p>Third year Software Engineering student working as a part-time web developer. Specializes in PHP.</p>
        </div>



        <div class="list-group-item pb-3">
            <div class="text-bg-light d-flex align-items-center mb-3">
                <img src="{{ asset('user/user.jpg') }}" alt="user_avatar" width="50" class="me-4">
                <h3>Marco</h3>
            </div>
            <p>Third year Software Engineering student working as a part-time web developer. Specializes in Bootstrap.</p>
        </div>


        <div class="list-group-item pb-3">
            <div class="text-bg-light d-flex align-items-center mb-3">
                <img src="{{ asset('user/user.jpg') }}" alt="user_avatar" width="50" class="me-4">
                <h3>Ricardo</h3>
            </div>
            <p>Third year Software Engineering student working as a part-time web developer. Specializes in JavaScript.</p>
        </div>

    </div>
@endsection

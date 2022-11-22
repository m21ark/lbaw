@extends('layouts.app')

@section('content')
    <h2 class="mt-4 mb-4">Contacts</h2>
    <p> Portuguese Customer Help +351 912345678
</p>
    <p>  English Customer Help +44 912345678
    </p>
    <p> French Customer Help  +33 912345678
    </p>

    <div class="list-group">
            <div class="list-group-item">
                <div class="text-bg-light d-flex align-items-center mb-3">
                    <img src="{{ asset('user/user.jpg')}}" alt="user_avatar" width="50" class="me-4">
                    <h3>David</h3>
                </div>
                <p> <p>Estudante do 3 ano da lincenciatura de informatica e computaçao na feup ,especialista em sql.</p>
            </div>

    </div>
    <div class="list-group">
            <div class="list-group-item">
                <div class="text-bg-light d-flex align-items-center mb-3">
                    <img src="{{ asset('user/user.jpg')}}"  alt="user_avatar" width="50" class="me-4">
                    <h3>João</h3>
                </div>
                <p>Estudante do 3 ano da lincenciatura de informatica e computaçao na feup ,especialista em php.</p>
            </div>

    </div>
    <div class="list-group">
            <div class="list-group-item">
                <div class="text-bg-light d-flex align-items-center mb-3">
                    <img src="{{ asset('user/user.jpg')}}"  alt="user_avatar" width="50" class="me-4">
                    <h3>Marco</h3>
                </div>
                <p>Estudante do 3 ano da lincenciatura de informatica e computaçao na feup , especialista em bootstap</p>
            </div>

    </div>
    <div class="list-group">
            <div class="list-group-item">
                <div class="text-bg-light d-flex align-items-center mb-3">
                    <img src="{{ asset('user/user.jpg')}}"  alt="user_avatar" width="50" class="me-4">
                    <h3>Ricardo</h3>
                </div>
                 <p>Estudante do 3 ano da lincenciatura de informatica e computaçao na feup , especialista em JavaScript.</p>
            </div>

    </div>
@endsection


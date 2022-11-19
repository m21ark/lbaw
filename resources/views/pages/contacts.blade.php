@extends('layouts.app')

@section('content')
    <h2 class="mt-4 mb-4">Contacts</h2>
    <div class="list-group">
        @for ($i = 0; $i < 3; $i++)
            <div class="list-group-item">
                <div class="text-bg-light d-flex align-items-center mb-3">
                    <img src="/../user.png" alt="user_avatar" width="50" class="me-4">
                    <h3>Creator Name</h3>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ratione aut natus voluptates, sint
                    provident
                    ullam dolorum facilis maxime a quae illum nesciunt delectus placeat tempore corrupti ab amet quis
                    adipisci.</p>
            </div>
        @endfor

    </div>
@endsection

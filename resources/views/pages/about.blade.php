@extends('layouts.app')

@section('content')
    <div class="list-group">
        @for ($i = 0; $i < 3; $i++)
            <div class="list-group-item ">
                <div class="text-bg-light">
                    <h3> Who we are?</h3>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ratione aut natus voluptates, sint
                    provident
                    ullam dolorum facilis maxime a quae illum nesciunt delectus placeat tempore corrupti ab amet quis
                    adipisci.</p>
            </div>
        @endfor

        <a href={{ url('/contacts') }} class="mt-4 btn btn-outline-secondary">Contacts</a>
        <a href={{ url('/features') }} class="mt-3 btn btn-outline-secondary">Main Features</a>
    </div>
@endsection

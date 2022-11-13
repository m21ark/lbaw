@extends('layouts.app')

@section('content')
    <div id="aboutus">
        @for ($i = 0; $i < 3; $i++)
            <article class="about_paragraph">
                <h3> Who we are?</h3>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ratione aut natus voluptates, sint
                    provident
                    ullam dolorum facilis maxime a quae illum nesciunt delectus placeat tempore corrupti ab amet quis
                    adipisci.</p>
            </article>
        @endfor

        <a href={{ url('/contacts') }}>Contacts</a>
    </div>
@endsection

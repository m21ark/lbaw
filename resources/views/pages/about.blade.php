@extends('layouts.app')

@section('content')
    <h2 class="mt-4 mb-4">About Us</h2>
    <div class="list-group">
       
            <div class="list-group-item ">
                <div class="text-bg-light">
                    <h3> Who we are?</h3>
                </div>
                <p>   <article class="about_paragraph">
                <p> We are a social network that allows the user to comunicate with his friends,share the best moment of his life and express his feeling.Nexus allows the user to organize groups on any topic to discuss issues on that topic</p>
            </article>
            </div>
        
            <div class="list-group-item ">
            <div class="text-bg-light">
                <h3> How did nexus come about?</h3>
                </div>
                <p>   <article class="about_paragraph">
                <p> Nexus came about because of the LBAW project and our Owners think nexus was good plataform for comunicate and see some photos from our friends.</p>
            </article>
            </div>


        <a href={{ url('/contacts') }} class="mt-4 btn btn-outline-secondary">Contacts</a>
        <a href={{ url('/features') }} class="mt-3 btn btn-outline-secondary">Main Features</a>
    </div>
@endsection

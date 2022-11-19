@extends('layouts.app')

@section('content')
    <div class="list-group">
        <div class="container px-4 py-5" id="hanging-icons">
            <h2 class="pb-2 border-bottom">Main Features</h2>
            <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">

                @for ($i = 0; $i < 9; $i++)
                    <div class="col d-flex align-items-start">
                        <div>
                            <h3 class="fs-2">Featured title</h3>
                            <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another
                                sentence and probably just keep going until we run out of words.</p>
                            <a href="#" class="btn btn-primary">
                                Primary button
                            </a>
                        </div>
                    </div>
                @endfor


            </div>
        </div>


    </div>
@endsection

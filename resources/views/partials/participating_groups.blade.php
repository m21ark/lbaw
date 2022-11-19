@auth
    @if (Auth::user()->groupsOwner->count() > 0)
        <div class="mt-3 container">
            <h5>Owner Groups</h5>
            <ul class="list-unstyled">
                @foreach (Auth::user()->groupsOwner as $x)
                    <li>
                        <a href={{ url('/group/' . $x->group->name) }} class=" btn btn-outline-secondary mb-3"
                            aria-current="page">{{ $x->group->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <hr class="mb-4">

    @if (Auth::user()->groupsMember->count() > 0)
        <div class="mt-3 mb-4 container">


            <h5>Member Groups</h5>
            <ul class="list-unstyled">
                @foreach (Auth::user()->groupsMember as $x)
                    <li>
                        <a href={{ url('/group/' . $x->group->name) }} class=" btn btn-outline-secondary mb-3"
                            aria-current="page">{{ $x->group->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
@endauth

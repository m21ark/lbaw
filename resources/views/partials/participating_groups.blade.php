@auth
    <div class="drop_groups" style="display:none">
    @if (Auth::user()->groupsOwner->count() > 0)
        <div class="mt-3 container">
            <h5>Owner Groups</h5>
            <ul class="list-unstyled list-group list-group-flush">
                @foreach (Auth::user()->groupsOwner as $x)
                    <a href={{ url('/group/' . $x->group->name) }} class="btn" aria-current="page">
                        <li class="list-group-item">{{ $x->group->name }} </li>
                    </a>
                @endforeach
            </ul>
        </div>
    @endif

    @if (Auth::user()->groupsMember->count() > 0)
        <div class="mt-3 mb-4 container">
            <h5>Member Groups</h5>
            <ul class="list-unstyled list-group list-group-flush">
                @foreach (Auth::user()->groupsMember as $x)
                    <a href={{ url('/group/' . $x->group->name) }} class="btn" aria-current="page">
                        <li class="list-group-item">{{ $x->group->name }} </li>
                    </a>
                @endforeach
            </ul>
        </div>
    @endif
    </div>
@endauth

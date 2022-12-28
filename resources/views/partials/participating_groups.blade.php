@auth
    <div class="drop_groups bg-light" style="display:none;position:absolute;">
        @if (Auth::user()->groupsOwner->count() > 0)
            <div class="mt-3 container">
                <h5 data-placement="right" title="Groups where you are an owner">Groups Owned</h5>
                <ul class="list-unstyled list-group list-group-flush">
                    @foreach (Auth::user()->groupsOwner as $x)
                        <li class="list-group-item">
                            <a href={{ url('/group/' . $x->group->name) }} class="btn"
                                aria-current="page">{{ $x->group->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (Auth::user()->groupsMember->count() > 0)
            <div class="mt-3 mb-4 container">
                <h5 data-placement="right" title="Groups where you are a member">Member Groups</h5>
                <ul class="list-unstyled list-group list-group-flush">
                    @foreach (Auth::user()->groupsMember as $x)
                        <li class="list-group-item">
                            <a href={{ url('/group/' . $x->group->name) }} class="btn" aria-current="page">
                                {{ $x->group->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endauth

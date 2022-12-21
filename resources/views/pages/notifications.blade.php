@extends('layouts.app')



@section('content')
    <h2 class="mt-3">Notification List</h2>
    <div aria-live="polite" aria-atomic="true" class="l_navbar show flex-column p-3 bg-light overflow-auto"
        id="notifications_list_container" style="max-width:50em;justify-content:center">
    </div>
@endsection

<div id="leftbar" class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">

        <span class="fs-4">Home</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">


        <a href={{ url('/home') }} class=" btn btn-outline-primary mb-3">Home </a>
        <a href={{ url('/group/mine') }} class=" btn btn-outline-primary mb-3" aria-current="page">My Groups</a>
        <a href={{ url('/messages/sender_username') }} class=" btn btn-outline-primary mb-3">Messages</a>

        @auth
            <button id="popup_btn_post" class="mt-5 make_post_popup form_button btn btn-primary" type="submit">Post</button>
        @endauth

    </ul>
</div>



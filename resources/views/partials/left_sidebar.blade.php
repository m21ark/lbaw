<div id="leftbar" class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <svg class="bi pe-none me-2" width="40" height="32">
            <use xlink:href="#bootstrap"></use>
        </svg>
        <span class="fs-4">Sidebar</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">

        <li class="nav-item">
            <a href={{ url('/home') }} class="nav-link text-bg-secondary mb-3" aria-current="page">
                <svg class="bi pe-none me-2" width="16" height="16">
                    <use xlink:href="#home"></use>
                </svg>
                Home
            </a>
        </li>

        <li class="nav-item">
            <a href={{ url('/messages/sender_username') }} class="nav-link text-bg-secondary mb-3" aria-current="page">
                <svg class="bi pe-none me-2" width="16" height="16">
                    <use xlink:href="#home"></use>
                </svg>
                Messages
            </a>
        </li>

        <li class="nav-item">
            <a href={{ url('/group/mine') }} class="nav-link text-bg-secondary mb-3" aria-current="page">
                <svg class="bi pe-none me-2" width="16" height="16">
                    <use xlink:href="#home"></use>
                </svg>
                My Groups
            </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link text-bg-secondary mb-3" aria-current="page">
                <svg class="bi pe-none me-2" width="16" height="16">
                    <use xlink:href="#home"></use>
                </svg>
                Home
            </a>
        </li>

        @auth
            <button c id="post_button" class="mt-5 make_post_popup form_button btn btn-primary" type="submit">Post</button>
        @endauth

    </ul>
</div>

function addEventListeners() {

    // Toggle para botões que escondem paginas
    let listener_list = [
        ['#popup_btn_post', logItem('#popup_show_post')],
        ['#popup_btn_group_post', logItem('#popup_show_group_post')],
        ['#popup_btn_group_create', logItem('#popup_show_group_create')],
        ['#popup_btn_group_edit', logItem('#popup_show_group_edit')]
    ];


    listener_list.forEach(function (l) {
        let element = document.querySelectorAll(l[0]);
        [].forEach.call(element, function (element) {
            element.addEventListener('click', l[1]);
        });
    }
    );

    let create_button = document.querySelector('#profile_post_button_action');
    create_button.addEventListener('click', sendCreatePostRequest(true));

    let create_group_post_button = document.querySelector('#group_post_button_action');
    if (create_group_post_button)
        create_group_post_button.addEventListener('click', sendCreatePostRequest(false));

    let remove_groupMember_button = document.querySelector('#leave_group_button');
    if (remove_groupMember_button)
        remove_groupMember_button.addEventListener('click', sendDeleteGroupMemberRequest);

    let create_group_button = document.querySelector('#create_group_button');
    if (create_group_button)
        create_group_button.addEventListener('click', sendCreateGroupRequest);


    let edit_group_button = document.querySelector('#edit_group_button');
    if (edit_group_button)
        edit_group_button.addEventListener('click', sendEditGroupRequest);


    let delete_group_button = document.querySelector('#delete_group_button');
    if (delete_group_button)
        delete_group_button.addEventListener('click', sendDeleteGroupRequest);

    let close_popups = document.querySelectorAll('.close_popup_btn');
    if (close_popups)
        if (close_popups.length > 0) {
            close_popups.forEach(e => {
                e.addEventListener('click', closePopups);
            });
        }

    let post_dropDowns = document.querySelectorAll('.dropdownPostButton');
    // console.log(post_dropDowns);
    // No caso dos post da home q recebem load com javascript nao da :(
    // Ricardo help. I hate JS
    if (post_dropDowns.length > 0)
        for (var i = 0; i < post_dropDowns.length; i++)
            post_dropDowns[i].addEventListener('click', togglePostDropDown);

}

function togglePostDropDown() {
    document.querySelector('.dropdown_menu').toggleAttribute('hidden')
}

function logItem(btn_id) {
    return function (e) {
        const item = document.querySelector(btn_id);
        item.toggleAttribute('hidden');
    }
}

function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();

    request.open(method, url, true);
    request.withCredentials = true;
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
}

function sendCreatePostRequest(isProfile) {
    return function (event) {
        if (isProfile) {
            let textarea = document.querySelector('#popup_show_post textarea');
            let res = confirm('Are you sure you want to profile post this?');
            if (res && textarea.value != null)
                sendAjaxRequest('post', '/api/post/', { text: textarea.value }, () => { });
            document.querySelector('#popup_show_post').toggleAttribute('hidden');
            textarea.value = '';
        }
        else {
            let textarea = document.querySelector('#popup_show_group_post textarea');
            let res = confirm('Are you sure you want to group post this?');
            if (res && textarea.value != null)
                sendAjaxRequest('post', '/api/post/', { text: textarea.value, group_name: textarea.dataset.group }, () => { });
            document.querySelector('#popup_show_group_post').toggleAttribute('hidden');
            textarea.value = '';
        }

        event.preventDefault();
    }
}

function addedHandler(class_name) {
    return function () {
        if (this.status < 200 && this.status >= 300) window.location = '/';
        // create alert notification - use switch
        logItem(class_name)(0);
        // talvez dar redirect para a pagina do post
    }
}

function closePopups() {
    let popups = document.querySelectorAll('.pop_up')
    if (popups)
        popups.forEach(e => {
            e.setAttribute('hidden', true);
        });
}


// ============================================ GROUPS ============================================

function sendCreateGroupRequest() {

    let name = document.querySelector('#popup_show_group_create #group_name').value
    let description = document.querySelector('#popup_show_group_create #group_description').value
    let visibility = document.querySelector('#popup_show_group_create #group_visibility').value

    if (name == '' || description == '' || visibility == null) {
        alert('Invalid input');
        return;
    }

    let res = confirm('Are you sure you want to create this group?');
    if (res) {
        sendAjaxRequest('post', '/api/group', { name: name, description: description, visibility: visibility }, addedHandler('.make_group'));
        // TODO: Fazer redirect para grupo criado
    }

}

function sendEditGroupRequest() {

    let name = document.querySelector('#popup_show_group_edit #group_name').value
    let description = document.querySelector('#popup_show_group_edit #group_description').value
    let visibility = document.querySelector('#popup_show_group_edit #group_visibility').value
    let oldName = document.querySelector('#popup_show_group_edit #group_description').dataset.name
    let id_group = document.querySelector('#popup_show_group_edit #group_description').dataset.id

    console.log(name, description, visibility, oldName, id_group);

    if (name == '' || description == '' || visibility == null) {
        alert('Invalid input');
        return;
    }

    let res = confirm('Are you sure you want to edit this group?');
    if (res)
        sendAjaxRequest('put', '/api/group/' + oldName, { name: name, description: description, visibility: visibility, id_group: id_group }, () => { });

}

function sendDeleteGroupRequest() {
    let oldName = document.querySelector('#popup_show_group_edit #group_description').dataset.name
    console.log('/api/group/' + oldName);
    let res = confirm('Are you sure you want to delete this group?');
    if (res) {
        sendAjaxRequest('delete', '/api/group/' + oldName, {}, () => { });
    }
}


// ============================================ END GROUPS ============================================


function sendDeleteGroupMemberRequest() {

    let id = document.querySelector('#leave_group_button').getAttribute('data-idGroup');

    let res = confirm("Are you sure you want to leave this group?");

    if (!res)
        return;

    sendAjaxRequest('delete', '/api/group_member/' + id, null, () => { });

    location.reload();
}

addEventListeners();

// Home =============================================================================

function updateFeed(feed) {

    let pathname = window.location.pathname
    if (pathname !== '/home') return;

    if (!document.querySelector('#timeline')) {
        return;
    }

    sendAjaxRequest('get', '/api/post/feed/' + feed, {}, function () {
        let received = JSON.parse(this.responseText);

        let timeline = document.querySelector('#timeline');
        timeline.innerHTML = '';
        received.forEach(function (post) {
            timeline.appendChild(createPost(post))
        })

    })
}

function createPost(post) {
    let new_post = document.createElement('article');
    new_post.classList.add('post');
    new_post.innerHTML = `

    <div class="container mt-5 mb-5 post_item">
    <div class="row d-flex align-items-center justify-content-center ">
        <div>
            <div class="card post_card">

                <div class="card-header d-flex justify-content-between p-2 px-3">

                    <a href="/profile/${post.owner}" class="text-decoration-none d-flex flex-row align-items-center">
                        <img src="/../user.png" width="60" class="rounded-circle me-3">
                        <strong class="font-weight-bold">${post.owner}</strong>
                    </a>

                    <small class="me-5">${post.post_date}</small>
                    <div class="dropdown">
                        <button class="btn dropdownPostButton" onclick="togglePostDropDown()" type="button">&vellip;</button>
                        <div class="dropdown_menu " hidden>
                            <a class="dropdown-item" href="/profile/${post.owner}">Go to
                                Profile</a>
                            @if (Auth::user()->id == $post->owner->id)
                                <a class="dropdown-item" href="#">Edit Post</a>
                                <a class="dropdown-item" href="#">Delete Post</a>
                            @else
                                <a class="dropdown-item" href="#">Send Message</a>
                            @endif


                        </div>
                    </div>

                </div>

                <!-- TODO: Ver imagens da database -->
                <img src="/../post.jpg" class="img-fluid">

                <div class="p-2">
                    <p class="text-justify">${post.text}</p>


                    <div class="card-footer d-flex justify-content-evenly">

                        <!-- TODO: Aqui devia se passar a contagem da database e n o array completo -->
                        <div class="d-flex">
                            <p class="me-3">${post.likes_count}</p>
                            <a href="#" class="text-decoration-none"><span class="likeicon">&#128077;</span></a>

                        </div>
                        <div class="d-flex">
                            <p class="me-3">${post.comments_count}</p>
                            <a href="#" class="text-decoration-none"><span
                                    class="commenticon">&#128172;</span></a>
                        </div>

                    </div>


                </div>


            </div>
        </div>
    </div>
</div>

  `
    return new_post;
}

function updateFeedOnLoad() {
    let feed_filters = document.querySelector('#feed_radio_viral')

    if (feed_filters) {
        feed_filters.checked = true
    }

    updateFeed('viral')
}

updateFeedOnLoad();

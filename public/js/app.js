// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

var pusher = new Pusher('c827040c068ce8231c02', {
    cluster: 'eu'
});

let user_header = document.querySelector('#auth_id');
if (user_header != null) {
    let id = user_header.dataset.id;
    var channel = pusher.subscribe('App.User.' + id);
    channel.bind('my-event', function (data) {

        if (data.type == "message" && window.location.pathname == '/messages/' + data.sender) {
            uploadSms(false, data.message)();
        }
        else {
            addNotification(data.sender + ' message you: ' + data.message, data.sender, data.photo);
        }
    });
}

function createElementFromHTML(htmlString) {
    var div = document.createElement('div');
    div.innerHTML = htmlString.trim();

    return div.firstChild;
}

function addNotification(message_body, sender, photo) {
    let notf_container = document.querySelector('#notf_container');
    // add js bootstrap Toast to notf_container 
    let notf = createElementFromHTML(`
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <img src="/${photo}" class="rounded me-2 img-fluid" alt="${sender} photo" style="max-width: 100%; height: auto; width: 3em">
          <strong class="me-auto">${sender}</strong>
          <small class="text-muted">just now</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
          ${message_body}
        </div>
    </div>`);

    notf_container.appendChild(notf)

    _n = new bootstrap.Toast(notf, { delay: 5000, animation: true });

    _n.show()

    setTimeout(() => toastElement.remove(), 7000);
    //_n.dispose()
}


function addEventListeners() {

    // Toggle para botões que escondem paginas
    let listener_list = [
        ['#popup_btn_post', logItem('#popup_show_post')],
        ['#popup_btn_group_post', logItem('#popup_show_group_post')],
        ['#popup_btn_group_create', logItem('#popup_show_group_create')],
        ['#popup_btn_group_edit', logItem('#popup_show_group_edit')],
        ['#popup_btn_profile_edit', logItem('#popup_show_profile_edit')],
        ['#popup_btn_post_edit', logItem('#popup_show_post_edit')],
        ['#sms_send_btn', sendMessage],
        ['#profile_post_button_action', sendCreatePostRequest(true)],
        ['#group_post_button_action', sendCreatePostRequest(false)],
        ['#edit_post_button', sendEditPostRequest],
        ['#delete_post_button', sendDeletePostRequest],
        ['#create_group_button', sendCreateGroupRequest],
        ['#edit_group_button', sendEditGroupRequest],
        ['#delete_group_button', sendDeleteGroupRequest],
        ['#edit_profile_button', sendEditProfileRequest],
        ['#delete_profile_button', sendDeleteProfileRequest],
        ['#leave_group_button', sendDeleteGroupMemberRequest],
        ['#comment_post_send', sendCreateCommentRequest],
        ['#edit_comment_button', sendEditCommentRequest],
        ['#delete_comment_button', sendDeleteCommentRequest],
        ['#notification_icon', createNotificationList],
    ];


    listener_list.forEach(function (l) {
        let element = document.querySelectorAll(l[0]);
        [].forEach.call(element, function (element) {
            element.addEventListener('click', l[1]);
        });
    }
    );


    // LIKES ACTIONS
    assignFunctionClickAll('.like_btn_post', sendLikePostRequest)
    assignFunctionClickAll('.like_btn_comment', sendLikeCommentRequest)
    assignFunctionClickAll('.kick_member_button', sendKickpMemberRequest)

    // OPEN COMMENT POPUPS
    commentPopupsController()
    commentRepliesController()
    commentRepliesShow()

    // CLOSE POP-UPS ACTION
    assignFunctionClickAll('.close_popup_btn', closePopups)

    // TODO ... passar para o array
    let post_dropDowns = document.querySelectorAll('.dropdownPostButton');
    [].forEach.call(post_dropDowns, function (element) {
        element.addEventListener('click', togglePostDropDown(element.parentNode));
    });

    let d_group_sidebar = document.querySelector('.drop_my_group');
    if (d_group_sidebar)
        d_group_sidebar.addEventListener('click', function (event) {
            event.preventDefault();
            let drop = document.querySelector('.drop_groups');
            drop.style.display = drop.style.display === 'none' ? '' : 'none';
        })
}

function commentPopupsController() {
    let aux = document.querySelectorAll('.popup_btn_comment_edit');
    if (aux)
        if (aux.length > 0)
            aux.forEach(e => e.addEventListener('click', (e) => {
                let id = e.currentTarget.dataset.id
                let elem = document.querySelector('#popup_show_comment_edit')
                elem.toggleAttribute('hidden')
                document.querySelector('#comment_text_edit').value = e.currentTarget.dataset.text
                document.querySelector('#edit_comment_button').setAttribute('data-id', id)
                document.querySelector('#delete_comment_button').setAttribute('data-id', id)
            }));
}

function commentRepliesController() {
    let aux = document.querySelectorAll('.comment_reply_btn');
    if (aux)
        if (aux.length > 0)
            aux.forEach(e => e.addEventListener('click', (e) => {
                let inp = document.querySelector('#comment_post_input')
                inp.value = '@' + e.currentTarget.dataset.username + ' '
                inp.setAttribute('data-parent', e.currentTarget.dataset.id)
                inp.focus()
            }));
}

function commentRepliesShow() {

    let aux = document.querySelectorAll('.reveal_comment_replies');
    if (!aux) return;

    if (aux.length > 0)
        aux.forEach(e => e.addEventListener('click', (e) => {
            let aux2 = document.querySelector('#comment_reply_section_' + e.currentTarget.dataset.id);
            if (!aux2) return;
            aux2.toggleAttribute('hidden')
        }));
}

function assignFunctionClick(querySelector, func) {
    let aux = document.querySelector(querySelector);
    if (aux)
        aux.addEventListener('click', func);
}

function assignFunctionClickAll(querySelector, func) {

    let aux = document.querySelectorAll(querySelector);
    if (aux)
        if (aux.length > 0)
            aux.forEach(e => e.addEventListener('click', (e) => func(e)));
}

function togglePostDropDown(parent) {
    return function () {
        parent.querySelector('.dropdown_menu').toggleAttribute('hidden')
    }
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

function sendFormData(method, url, data, handler) {
    let request = new XMLHttpRequest();

    request.open(method, url, true);
    request.withCredentials = true;
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.addEventListener('load', handler);

    request.send(data);
}

function addedHandler(class_name) {
    return function () {
        if (class_name != null)
            logItem(class_name)(0);
        class_alert = 'alert-success'
        let alert = document.createElement('div');
        alert.innerHTML = 'Action successful';
        if (this.status < 200 || this.status >= 300) {
            class_alert = 'alert-danger'
            alert.innerHTML = 'Error, something went wrong';
        }
        alert.classList.add('alert', class_alert, 'alert-dismissible', 'fade', 'show');
        alert.setAttribute('role', 'alert');
        alert.style = 'position: fixed; bottom: 0; right: 1em; z-index: 1000;transition: all 0.8s;';
        document.querySelector('body').appendChild(alert);
        setTimeout(() => {
            alert.remove();
            // talvez meter aqui o location.reload(), n sei se valerá a pena
        }, 3000);
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

function sendCreateGroupRequest(event) {

    event.preventDefault()
    let name = document.querySelector('#popup_show_group_create #group_name').value
    let description = document.querySelector('#popup_show_group_create #group_description').value
    let visibility = document.querySelector('#popup_show_group_create #group_visibility').value

    if (name == '' || description == '' || visibility == null) {
        alert('Invalid input');
        return;
    }

    let res = confirm('Are you sure you want to create this group?');
    if (res) {
        sendAjaxRequest('post', '/api/group', { name: name, description: description, visibility: visibility }, addedHandler('#popup_show_group_create'));
        // TODO: Fazer redirect para grupo criado
    }

}


function sendEditGroupRequest(event) {

    event.preventDefault();
    let name = document.querySelector('#popup_show_group_edit #group_name').value
    let description = document.querySelector('#popup_show_group_edit #group_description').value
    let visibility = document.querySelector('#popup_show_group_edit #group_visibility').value
    let oldName = document.querySelector('#popup_show_group_edit #group_description').dataset.name
    let id_group = document.querySelector('#popup_show_group_edit #group_description').dataset.id
    let pho = document.querySelector('#popup_show_group_edit #group_photo').files[0]

    if (name == '' || description == '' || visibility == null) {
        alert('Invalid input');
        return;
    }

    let formData = new FormData();
    formData.append('name', name);
    formData.append('description', description);
    formData.append('visibility', visibility);
    formData.append('id_group', id_group);
    formData.append('photo', pho);

    let res = confirm('Are you sure you want to edit this group?');
    if (res)
        sendFormData('post', '/api/group/' + oldName, formData, addedHandler('#popup_show_group_edit'));

}

function sendDeleteGroupRequest() {
    let oldName = document.querySelector('#popup_show_group_edit #group_description').dataset.name;
    let res = confirm('Are you sure you want to delete this group?');
    if (res) {
        sendAjaxRequest('delete', '/api/group/' + oldName, {}, () => { });
    }
}

function sendKickpMemberRequest(event) {

    let e = event.currentTarget

    let id_group = e.getAttribute('data-idGroup')
    let id_user = e.getAttribute('data-idUser')

    let res = confirm("Are you sure you want to kick this user?");

    if (!res)
        return;

    sendAjaxRequest('delete', `/api/group/${id_group}/member/${id_user}`, null, () => { });

    location.reload();
}





function sendDeleteGroupMemberRequest() {

    let id_group = document.querySelector('#leave_group_button').getAttribute('data-idGroup');
    let id_user = document.querySelector('#leave_group_button').getAttribute('data-idUser');

    let res = confirm("Are you sure you want to leave this group?");

    if (!res)
        return;

    sendAjaxRequest('delete', `/api/group/${id_group}/member/${id_user}`, null, () => { });

    location.reload();
}


// ============================================ Profile ============================================


function sendEditProfileRequest(event) {

    event.preventDefault();
    let username = document.querySelector('#popup_show_profile_edit #user_name').value
    let email = document.querySelector('#popup_show_profile_edit #user_email').value
    let bdate = document.querySelector('#popup_show_profile_edit #user_bdate').value
    let bio = document.querySelector('#popup_show_profile_edit #user_bio').value
    let visibility = document.querySelector('#popup_show_profile_edit #profile_visibility').value

    let oldName = document.querySelector('#popup_show_profile_edit #user_name').dataset.name
    let idUser = document.querySelector('#popup_show_profile_edit #user_name').dataset.id
    let pho = document.querySelectorAll('#popup_show_profile_edit #profile_pic')[0].files[0];

    console.log(username, email, bdate, bio, visibility, oldName, idUser, pho);

    if (username == '' || email == '' || bio == '' || oldName == '' || bdate == null) {
        alert('Invalid input');
        return;
    }

    let formData = new FormData();
    formData.append('username', username);
    formData.append('email', email);
    formData.append('bdate', bdate);
    formData.append('bio', bio);
    formData.append('visibility', visibility);
    formData.append('oldName', oldName);
    formData.append('photo', pho);


    let res = confirm('Are you sure you want to edit your profile?');
    if (res) {
        sendFormData('post', '/api/profile/' + oldName, formData, addedHandler('#popup_show_profile_edit'));
        //todo: fazer redirect para o perfil editado
    }
}


function sendDeleteProfileRequest() {
    let username = document.querySelector('#popup_show_profile_edit #user_name').dataset.name

    let res = prompt('Are you sure you want to delete your ' + username + ' account?\nPlease insert your username to confirm:');
    if (res === username) {
        sendAjaxRequest('delete', '/api/profile/' + username, {}, () => { });
    } else {
        alert('Invalid input! Account not deleted!');
    }
}



// ============================================ Likes ============================================



function sendLikePostRequest(event) {
    toggleLikeHTML(event)
    let id_user = event.dataset.uid
    let id_post = event.dataset.id
    sendAjaxRequest('post', '/api/like_post', { id_user: id_user, id_post: id_post }, () => { });
}

function sendLikeCommentRequest(event) {
    toggleLikeHTML(event)
    let id_user = event.dataset.uid
    let id_comment = event.dataset.id
    sendAjaxRequest('post', '/api/like_comment', { id_user: id_user, id_comment: id_comment }, () => { });
}

function toggleLikeHTML(event) {
    let like_icon = event.firstElementChild
    hasLiked = like_icon.dataset.liked === '1'

    event.previousElementSibling.innerHTML = parseInt(event.previousElementSibling.innerHTML) + (hasLiked ? -1 : 1);
    like_icon.setAttribute('data-liked', (hasLiked ? '0' : '1'));
    like_icon.innerHTML = hasLiked ? '&#9825;' : '&#x2764;'

    if (hasLiked)
        like_icon.style.fontSize = '1.3em'
    else
        like_icon.style.fontSize = '1.2em'
}


// ============================================ Comments ============================================

function sendCreateCommentRequest() {

    let elem = document.querySelector('#comment_post_input')
    let text = elem.value
    let id_post = elem.dataset.pid
    let id_user = elem.dataset.uid
    let id_parent = elem.dataset.parent

    if (text === '') {
        alert('Invalid input');
        return;
    }

    let res = confirm('Are you sure you want to publish this comment?');
    if (res) {
        sendAjaxRequest('post', `/api/comment/${id_post}`, { id_user: id_user, id_post: id_post, text: text, id_parent: id_parent }, () => { });
        location.reload();
    }

}

function sendEditCommentRequest() {
    let id_comment = document.querySelector('#edit_comment_button').dataset.id
    let text = document.querySelector('#comment_text_edit').value

    if (text === '') {
        alert('Invalid input');
        return;
    }

    let res = confirm('Are you sure you want edit this comment?');
    if (res) {
        sendAjaxRequest('put', `/api/comment`, { id_comment: id_comment, text: text }, () => { });
        //location.reload();
        logItem('#popup_show_comment_edit')(0);
    }

}


function sendDeleteCommentRequest() {
    let id_comment = document.querySelector('#delete_comment_button').dataset.id

    let res = confirm('Are you sure you want delete this comment?');
    if (res) {
        sendAjaxRequest('delete', `/api/comment/${id_comment}`, {}, () => { });
        location.reload();
    }
}



// ============================================ Post ============================================


function sendCreatePostRequest(isProfile) {
    return function (event) {
        if (isProfile) {
            let textarea = document.querySelector('#popup_show_post textarea');
            let photos = document.querySelector('#popup_show_post #post_photos').files;
            let res = confirm('Are you sure you want to profile post this?');

            let formData = new FormData();
            formData.append('text', textarea.value);
            for (var x = 0; x < photos.length; x++) {
                formData.append("photos[]", photos[x]);
            }
            console.log(formData)

            if (res && textarea.value != null)
                sendFormData('post', '/api/post/', formData, addedHandler('#popup_show_post'));
            textarea.value = '';
        }
        else {
            let textarea = document.querySelector('#popup_show_group_post textarea');

            let res = confirm('Are you sure you want to group post this?');
            let photos = document.querySelector('#popup_show_group_post #post_photos').files;

            let formData = new FormData();
            formData.append('text', textarea.value);
            formData.append('group_name', textarea.dataset.group);
            for (var x = 0; x < photos.length; x++) {
                formData.append("photos[]", photos[x]);
            }

            console.log(formData)

            if (res && textarea.value != null)
                sendFormData('post', '/api/post/', formData, addedHandler('#popup_show_group_post'));
            textarea.value = '';
        }

        event.preventDefault();
    }
}


function sendEditPostRequest(event) {

    let res = confirm('Are you sure you want to edit this post?');

    let textarea = document.querySelector('#popup_show_post_edit textarea');
    let photos = document.querySelector('#popup_show_post_edit #edit_post_photos').files;
    let id = document.querySelector('#popup_show_post_edit #delete_post_button').dataset.id


    let formData = new FormData();
    formData.append('text', textarea.value);
    for (var x = 0; x < photos.length; x++) {
        formData.append("photos[]", photos[x]);
    }

    console.log(formData)
    if (res && textarea.value != null)
        sendFormData('post', '/api/post/' + id, formData, addedHandler('#popup_show_post_edit'));

    event.preventDefault();
}


function sendDeletePostRequest() {
    let id = document.querySelector('#popup_show_post_edit #delete_post_button').dataset.id

    let res = confirm('Are you sure you want to delete this post?');
    if (res)
        sendAjaxRequest('delete', '/api/post/' + id, {}, () => { });
    // location.reload();

    //EM VEZ DO RELOAD DAR DELETE NO DOM TODO

}


// ==================================== MESSAGES =====================================

function sendMessage(event) {
    event.preventDefault();
    let text = document.querySelector('#sms_input').value;
    let receiver = document.querySelector('#sms_rcv');

    sendAjaxRequest('post', "/api/message/" + receiver.dataset.id, { text: text }, uploadSms(true, text))
    document.querySelector('#sms_input').value = ''
}

function uploadSms(isSender, message) { // NAO QUERO SABER SE DEU CORRETO, TALVEZ VER ISSO DPS
    return function () {
        const art = document.createElement("article");

        art.classList.add('message_txt');
        art.classList.add(isSender ? 'text_sender' : 'text_rcv');

        date = new Date().toLocaleString().replace(',', '').replaceAll('/', '-');

        var p1 = document.createElement('p');
        p1.innerHTML = date;

        var p2 = document.createElement('p');
        p2.innerHTML = message;

        art.appendChild(p1);
        art.appendChild(p2);
        document.querySelector('.message_body').append(art);
    }
}


addEventListeners();


// =================================== Home ==========================================

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

    setTimeout(() => assignFunctionClickAll('.like_btn_post', sendLikePostRequest), 1000);
}

function createPost(post) {
    let new_post = document.createElement('article');
    new_post.classList.add('post');

    let images = '', bottom = '', like = '', dropdown = '';

    if (post.hasLiked) {
        like = '<h3 data-liked="1">&#x2764;</h3>'
    } else {
        like = '<h3 data-liked="0">&#9825;</h3>'
    }

    if (post.auth !== 0) {
        if (post.isOwner) {
            dropdown = `<a class="dropdown-item" href="/post/${post.id}">See Post</a>`
        } else {
            dropdown = `
            <a class="dropdown-item" href="#">Report Post</a>
            <a class="dropdown-item" href="#">Send Message</a>`
        }

        bottom = `
        <div class="d-flex">
            <p class="me-3">${post.likes_count}</p>

            <a href="#!" class="like_btn_post text-decoration-none" data-uid=${post.auth} data-id=${post.id}>

               ${like}

            </a>
        </div>
        `

    } else {

        bottom = `
        <div class="d-flex">
            <p class="me-3">${post.likes_count}</p>
            <a href="#!" class="like_btn_post text-decoration-none">
                <h2><strong>&#9825;</strong></h2>
            </a>
        </div>
        `
    }

    if (post.images.length !== 0) {
        let imageDiv = '';

        post.images.forEach(function (image) {
            imageDiv += `
                <div class="carousel-item active">
                    <img class="d-block w-100" src="/${image.path}" alt="Primeiro Slide">
                </div>
            `
        })

        images = `
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                ${imageDiv}
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev" style="filter: invert(100%);">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next" style="filter: invert(100%);">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </a>
        </div>

        `
    }



    new_post.innerHTML = `

<div class="container mt-5 mb-5 post_item" style="width:50em">
    <div class="row d-flex align-items-center mw-50 justify-content-center ">
            <div class="card post_card">
                <div>
                    <div class="card-header d-flex justify-content-between p-2 px-3">

                        <a href='/profile/${post.owner}' .
                            class="text-decoration-none d-flex flex-row align-items-center">
                            <img src="/${post.photo}" width="60" class="rounded-circle me-3">
                            <strong class="font-weight-bold">${post.owner}</strong>
                        </a>

                        <small class="me-5">${post.post_date}</small>


                        <div class="dropdown">
                            <button class="btn dropdownPostButton" onclick="togglePostDropDown(this.parentNode)()" type="button">&vellip;</button>
                            <div class="dropdown_menu" style="z-index: 200000" hidden>
                                <a class="dropdown-item" href='/profile/${post.owner}'>Go to Profile</a>

                                ${dropdown}

                            </div>
                        </div>

                    </div>

                    <!-- TODO: Ver imagens da database -->

                    ${images}


                    <div class="p-2">
                        <p class="text-justify">${post.text}</p>


                        <div class="card-footer d-flex justify-content-evenly">

                            <!-- TODO: Aqui devia se passar a contagem da database e n o array completo -->

                            ${bottom}

                            <div class="d-flex">
                                <p class="me-3">${post.comments_count}</p>
                                <a href="/post/${post.id}" class="text-decoration-none"><span
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
    if (feed_filters)
        feed_filters.checked = true
    updateFeed('viral')
}
updateFeedOnLoad();



//  ======================================= Search ======================================

function updateSearchOnInputAndClick() {

    let pathname = window.location.pathname
    if (!/\/search\/[#\*?!.@_\w ]*/.test(pathname)) return;

    if (!document.querySelector('#timeline')) {
        return;
    }

    let search_filters = document.querySelector('input#search_radio_user')

    if (search_filters) {
        search_filters.checked = true
    }

    // Search if there is a query_string in the route (and add it to the search bar)
    const searchBar = document.querySelector('#search_bar')

    query_string = pathname.replaceAll('%20', ' ').match(/(?<=\/search\/)[#\*?!.@_\w ]+/)[0]

    if (query_string) {
        if (query_string !== '*') searchBar.value = query_string
        updateSearch()
    }

    // Add event listeners when input changes

    if (searchBar) {
        searchBar.addEventListener('input', function () {
            updateSearch()

            let searchBarString = searchBar.value.trim()

            // Update the path on top
            if (searchBarString !== '') {
                window.history.replaceState('', '', '/search/' + searchBarString.replaceAll(' ', '%20'))
            } else {
                window.history.replaceState('', '', '/search/*')
            }

        })

    }


    // Add event listeners when a radio has a click
    const filters = document.querySelectorAll('#search_filter input')

    if (filters) {
        filters.forEach(function (filter) {
            filter.addEventListener('click', updateSearch)
        })
    }

    updateSearch();
}


function updateSearch() {
    let type_search = '', query_string = '';

    // Get the type_search from the radio input
    const filters = document.querySelectorAll('#search_filter input')
    if (!filters) return;

    filters.forEach(filter => {
        if (filter.checked) { type_search = filter.value }
    })

    // Get the query string from the search bar
    const searchBar = document.querySelector('#search_bar')
    if (!searchBar) return;

    query_string = searchBar.value

    if (query_string === '')
        query_string = '*';

    sendAjaxRequest('get', '/api/search/' + query_string + '/type/' + type_search, {}, function () {

        let timeline = document.querySelector('#timeline');

        if (!timeline) return;
        let received;
        try {
            received = JSON.parse(this.responseText);
        } catch (error) {
            // ignore for now
        }

        if (received == null) return;

        timeline.innerHTML = '';
        received.forEach(function (searchHit) {

            if (type_search === 'posts') {
                timeline.appendChild(createPost(searchHit));
            } else if (type_search === 'groups') {
                timeline.appendChild(createGroupCard(searchHit))
            } else if (type_search === 'users') {
                timeline.appendChild(createUserCard(searchHit))
            } else if (type_search === 'topics') {
                timeline.appendChild(createTopicCard(searchHit))
            }

        })

    })

    setTimeout(() => assignFunctionClickAll('.like_btn_post', sendLikePostRequest), 1000);
}



function createUserCard(user) {
    let new_card = document.createElement('article');

    let bio_short = user.bio;
    if (bio_short.length > 50)
        bio_short = user.bio.substring(0, 100) + '...'

    new_card.innerHTML = `
    <div class="card mt-4 me-3" style="width: 15em;height:29em">
        <img height="50%" src="/${user.photo}" class="card-img-top" alt="user_avatar">
        <div class="card-body">
            <h5 class="card-title">${user.username}</h5>
            <p class="card-text">${bio_short}</p>
            <a href="/profile/${user.username}" class="btn btn-primary w-100">Visit Profile</a>
        </div>
    </div>
    `
    return new_card;
}



function createGroupCard(group) {
    let new_card = document.createElement('article');

    let bio_short = group.description;
    if (bio_short.length > 50)
        bio_short = bio_short.substring(0, 100) + '...'

    new_card.innerHTML = `
    <div class="card mt-4 me-3" style="width: 15em;height:25em">
        <img height="60%" src="/${group.photo}" class="card-img-top" alt="user_avatar">
        <div class="card-body">
            <h5 class="card-title">${group.name}</h5>
            <p class="card-text">${bio_short}</p>
            <a href="/group/${group.name}" class="btn btn-primary w-100">Visit Group</a>
        </div>
    </div>
    `
    return new_card;
}


function createTopicCard(topic) {
    let new_card = document.createElement('article');

    new_card.innerHTML = `
    <div class="card mt-4 me-3" style="height:4em">
        <div class="d-flex align-items-center card-body">
            <h4 class="card-title">${topic.topic}</h5>
        </div>
    </div>
    `
    return new_card;
}

function searchRedirect() {

    // Needs to redirect except if it already is in the search page
    let pathname = window.location.pathname
    if (/\/search\/[#*?!.@_\w ]*/.test(pathname)) return;

    const searchBar = document.querySelector('#search_bar')
    if (!searchBar) return;

    searchBar.addEventListener('keypress', function (event) {

        //window.location.href = '/search/hey' + this.value

        if (event.key !== "Enter") return;

        this.value = this.value.trim()

        if (this.value !== '') {
            window.location.href = '/search/' + this.value.replaceAll(' ', '%20')
        } else {
            window.location.href = '/search/*';
        }

    })

}

searchRedirect();

updateSearchOnInputAndClick();




//  ======================================= Admin ======================================


function updateUserReportSearchOnInput() {

    const searchBarPendent = document.querySelector('#searchBarPendent');
    const searchBarPast = document.querySelector('#searchBarPast');

    if (!searchBarPendent || !searchBarPast) return;

    // Update on Page Loading
    updateUserReportsSearch(searchBarPendent, 'pendent')
    updateUserReportsSearch(searchBarPast, 'past')

    // Add event listeners for both search bars
    searchBarPendent.addEventListener('input', function () {
        updateUserReportsSearch(searchBarPendent, 'pendent')
    })

    searchBarPast.addEventListener('input', function () {
        updateUserReportsSearch(searchBarPast, 'past')
    })
}



function updateUserReportsSearch(searchBar, decision) {
    let query_string = searchBar.value;

    if (query_string.trim() === '') query_string = '*';

    sendAjaxRequest('get', '/api/admin/' + decision + '_reports/' + query_string, {}, function () {
        let display = document.querySelector("#users-reported-" + decision)

        if (!display) return;

        const received = JSON.parse(this.responseText)
        display.innerHTML = ''

        if (decision === 'pendent') {
            received.forEach(function (userReported) {
                display.appendChild(createUserReportCardPending(userReported))
            })

        } else if (decision === 'past') {
            received.forEach(function (userReported) {
                display.appendChild(createUserReportCardPast(userReported))
            })
        }



    })

}

function createUserReportCardPending(user) {
    let new_card = document.createElement('div')
    new_card.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center', 'mb-2')

    new_card.innerHTML = `
        <img class="me-3 rounded-circle" src="/${user.photo}" alt="user_avatar" width="50" height="50">
        <a class="me-3" href='/profile/${user.username}'>${user.username}</a>
        <a>${user.report_count} reports</a>
        <a href="#" class="btn btn-outline-secondary">Take</a>
    `

    return new_card;
}


function createUserReportCardPast(user) {
    let button, new_card = document.createElement('div')
    new_card.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center', 'mb-2')

    if (user.decision === 'Rejected') {
        button = `<a href="#" class=" btn btn-success">REJ</a>`
    } else if (user.decision === 'Accepted') {
        let banDate = new Date(user.ban_date).getTime();
        let today = new Date().getTime();

        if (user.ban_date != null) {
            time = Math.ceil((banDate - today) / (1000 * 3600 * 24));
        } else {
            time = -1;
        }

        if (time > 0) {
            button = `<a href="#" class=" btn btn-warning">${time}d</a>`
        } else {
            button = `<a href="#" class=" btn btn-info">Finished</a>`
        }

    }

    new_card.innerHTML = `
        <img class="me-3 rounded-circle" src="/${user.photo}" alt="user_avatar" width="50" height="50">
        <a class="me-3" href='/profile/${user.username}'>${user.username}</a>
        <a class="text-muted text-decoration-none">${user.decision_date}</a>
    ` + button + `
        <a href="#" class="btn btn-outline-dark">Retake</a>
    `;

    return new_card;
}

updateUserReportSearchOnInput()


// ========================== GETTING NOTIFICATIONS ===============================

/*
    All the notifications in this array.
    when a push happens this array should be updated 
*/
var _notifications = []

function getNotifications() {
    sendAjaxRequest('get', "/api/user/notifications", {}, function () {

        let received = JSON.parse(this.responseText);
        _notifications = _notifications.concat(received);
    });
}

getNotifications();

/*
    this should mark a notification as seen when tapping in the notf.
*/
async function markAsSeen($id) {
    sendAjaxRequest('get', "/api/user/notification/" + $id + "/seen", {}, function () {
        if (this.status == 200) {
            let _x = _notifications.findIndex(x => x.id == $id);
            _notifications[_x].seen = true;
        }
    });
}



function createNotificationList(event) {
    event.preventDefault();
    let notifications = document.querySelector('#notifications_container');

    for (let i = 0; i < _notifications.length; i++) {

        let notf = createElementFromHTML(`
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <img src="/User/user.jpg" class="rounded me-2 img-fluid" alt="User photo" style="max-width: 100%; height: auto; width: 3em">
          <strong class="me-auto">User</strong>
          <small class="text-muted">${}</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
          You have a new ${_notifications[i].type}.
        </div>
    </div>`);

        notifications.appendChild(notf);
    }
    console.log("dkd")
    notifications.style.display = 'auto';
}



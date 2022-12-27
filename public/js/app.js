// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

var pusher = new Pusher('c827040c068ce8231c02', { // WE CAN ADD ENCRYPTION HERE
    cluster: 'eu',
    authEndpoint: '/broadcast/auth',
    encrypted: true,
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }
    }
});

let user_header = document.querySelector('#auth_id');
if (user_header != null) {
    let id = user_header.dataset.id;
    var channel1 = pusher.subscribe('App.User.' + id);
    channel1.bind('my-event', function (data) {

        // TODO: VER O CASO DO REPLY
        let notfiableJsonPrototype = {
            id_post: data.obj.id_post,
            sender: data.sender,
            notification_date: Date.now(),
            id_parent: data.obj.id_parent,
            tipo: data.type,
        }
        if (data.type == "message") {
            if (window.location.pathname == '/messages/' + data.sender.username) {
                uploadSms(false, data.obj.text)();
            }
            else {
                addNotification(data.sender.username + ' message you: ' + data.obj.text, data.sender);
            }
        }
        else if (data.type == "Like") {
            _notifications.push(notfiableJsonPrototype);
            updateNrNotfications();
            addNotification(createCustomMessageBody(notfiableJsonPrototype), data.sender);
        }
        else if (data.type == "Comment") {
            _notifications.push(notfiableJsonPrototype);
            updateNrNotfications();
            addNotification(createCustomMessageBody(notfiableJsonPrototype), data.sender);
        }
        else if (data.type == "FriendRequest") {
            _notifications.push(notfiableJsonPrototype);
            updateNrNotfications();
            addNotification(createCustomMessageBody(notfiableJsonPrototype), data.sender);
        }


    });


    var usersOnline,
        users = [],
        sessionDesc,
        currentcaller,
        room,
        caller,
        localUserMedia;
    const channel = pusher.subscribe("presence-videocall");

    channel.bind("pusher:subscription_succeeded", members => {
        //set the member count
        usersOnline = members.count;
        id = channel.members.me.id;
        members.each(member => {
            if (member.id != channel.members.me.id) {
                users.push(member.id);
            }
        });

        render();
    });

    channel.bind("pusher:member_added", member => {
        users.push(member.id);
        render();
    });

    channel.bind("pusher:member_removed", member => {
        // for remove member from list:
        var index = users.indexOf(member.id);
        users.splice(index, 1);
        if (member.id == room) {
            endCall();
        }
        render();
    });

    function render() {
      // ONLINE STATUS OF USERS
    }

    /////////////////////////////////////////////
    // FROM PUSHER WEBRTC TUTORIAL
    //To iron over browser implementation anomalies like prefixes
    GetRTCPeerConnection();
    GetRTCSessionDescription();
    GetRTCIceCandidate();
    //prepare the caller to use peerconnection
    prepareCaller();
    function GetRTCIceCandidate() {
        window.RTCIceCandidate =
            window.RTCIceCandidate ||
            window.webkitRTCIceCandidate ||
            window.mozRTCIceCandidate ||
            window.msRTCIceCandidate;

        return window.RTCIceCandidate;
    }

    function GetRTCPeerConnection() {
        window.RTCPeerConnection =
            window.RTCPeerConnection ||
            window.webkitRTCPeerConnection ||
            window.mozRTCPeerConnection ||
            window.msRTCPeerConnection;
        return window.RTCPeerConnection;
    }

    function GetRTCSessionDescription() {
        console.log("GetRTCSessionDescription called");
        window.RTCSessionDescription =
            window.RTCSessionDescription ||
            window.webkitRTCSessionDescription ||
            window.mozRTCSessionDescription ||
            window.msRTCSessionDescription;
        return window.RTCSessionDescription;
    }
    //////////////////////////////////////////////////
    function prepareCaller() {

        const servers = {
            iceServers: [
                {
                    urls: [
                        "stun:stun1.l.google.com:19302",
                        "stun:stun2.l.google.com:19302",
                    ]
                },
                {
                    url: 'turn:turn.anyfirewall.com:443?transport=tcp',
                    credential: 'webrtc',
                    username: 'webrtc'
                }
            ],
            iceCandidatePoolSize: 10,
        };

        //Initializing a peer connection
        caller = new window.RTCPeerConnection(servers);
        //Listen for ICE Candidates and send them to remote peers
        caller.onicecandidate = function (evt) {
            if (!evt.candidate) return;
            onIceCandidate(caller, evt);
        };
        //onaddstream handler to receive remote feed and show in remoteview video element
        caller.onaddstream = function (evt) {
            if (window.URL) {
                document.getElementById("remoteview").srcObject = evt.stream;
            } else {
                document.getElementById("remoteview").src = evt.stream;
            }
        };
    }

    function onIceCandidate(peer, evt) {
        if (evt.candidate) {
            channel.trigger("client-candidate", {
                "candidate": evt.candidate,
                "room": room
            });
        }
    }

    channel.bind("client-candidate", function(msg) {
        if(msg.room==room){
            caller.addIceCandidate(new RTCIceCandidate(msg.candidate));
        }
    });

    function getCam() {
        return navigator.mediaDevices.getUserMedia({
            video: true,
            audio: true
        });
    }
    
    //Create and send offer to remote peer on button click
    function callUser(user) {

        let res = confirm('Are you sure you want to make a video call?');
        if (!res)
            return;

        getCam()
            .then(stream => {
                toggleVideoPopUp();
                caller.addStream(stream);
                localUserMedia = stream;
                caller.createOffer().then(function (desc) {
                    caller.setLocalDescription(new RTCSessionDescription(desc));
                    channel.trigger("client-sdp", {
                        sdp: desc,
                        room: user,
                        from: document.querySelector(".me-2").textContent
                    });
                    room = user;
                });

                // remove self audio 
                var audioTrack = stream.getAudioTracks();

                if (audioTrack.length > 0) {
                    stream.removeTrack(audioTrack[0]);
                }

                if (window.URL) {
                    document.getElementById("selfview").srcObject = stream;
                } else {
                    document.getElementById("selfview").src = stream;
                }
            })
            .catch(error => {
                console.log("an error occured", error);
            });
    }
    function toggleVideoPopUp() {
        if (document.getElementById("video_call").style.display == "block") {
            document.getElementById("video_call").style.display = "none";
        } else {
            document.getElementById("video_call").style.display = "block";
        }
    }

    // Incoming video call
    channel.bind("client-sdp", function (msg) {
        if (msg.room == id) {
            var answer = confirm("You have a call from: " + msg.from + ". Would you like to answer?");
            if (!answer) {
                return channel.trigger("client-reject", { "room": msg.room, "rejected": id });
            }
            room = msg.room;
            getCam()
                .then(stream => {
                    localUserMedia = stream;
                    toggleVideoPopUp();

                    caller.addStream(stream);
                    var sessionDesc = new RTCSessionDescription(msg.sdp);
                    caller.setRemoteDescription(sessionDesc);
                    caller.createAnswer().then(function (sdp) {
                        caller.setLocalDescription(new RTCSessionDescription(sdp));
                        channel.trigger("client-answer", {
                            "sdp": sdp,
                            "room": room
                        });
                    });

                    var audioTrack = stream.getAudioTracks();

                    if (audioTrack.length > 0) {
                        stream.removeTrack(audioTrack[0]);
                    }

                    if (window.URL) {
                        document.getElementById("selfview").srcObject = stream;
                    } else {
                        document.getElementById("selfview").src = stream;
                    }
                })
                .catch(error => {
                    console.log('an error occured', error);
                })
        }
    });
    channel.bind("client-answer", function (answer) {
        if (answer.room == room) {
            caller.setRemoteDescription(new RTCSessionDescription(answer.sdp));
        }
    });

    channel.bind("client-reject", function (answer) {
        if (answer.room == room) {
            console.log("Call declined");
            alert("call to " + answer.rejected + "was politely declined");
            endCall();
        }
    });

    function endCall() {
        room = undefined;
        caller.close();
        for (let track of localUserMedia.getTracks()) {
            track.stop();
        }
        prepareCaller();
        toggleVideoPopUp();
    }
}

//////////////////////////  Contextual Help //////////////////////////

function startContextualHelp() {
    introJs().setOptions({
        steps: [{
          intro: "Hello world!"
        }, {
          element: document.querySelector('#leftbar').firstElementChild,
          intro: "Here you can quickly access the main features of our app"
        },
        {
          element: document.querySelector("#popup_btn_post"),
          intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
        },
        {
            element: document.querySelector("#feed_filter"),
            intro: "We allow you to see different timelines. Isn't that amazing???😮"
        },
        {
            element: document.querySelector(".me-2").parentElement,
            intro: "You can rapidly go to your profile ...  🏃🏃🏃"
        },
        {
            intro: "Hope you have fun using it <img src=\"https://www.mememaker.net/static/images/memes/4851592.jpg\" class=\"help_photo\"/>" 
        }
        ]
      }).start();
}

function createElementFromHTML(htmlString) {
    var div = document.createElement('div');
    div.innerHTML = htmlString.trim();

    return div.firstChild;
}

function addNotification(message_body, sender) {
    let notf_container = document.querySelector('#notf_container');
    // add js bootstrap Toast to notf_container
    let notf = createElementFromHTML(`
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <img src="/${sender.photo}" class="rounded me-2 img-fluid" alt="${sender.username} Profile Image" style="max-width: 100%; height: auto; width: 3em">
          <strong class="me-auto">${sender.username}</strong>
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

    //setTimeout(() => toastElement.remove(), 7000);
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
        ['#popup_btn_report_post_create', popupControllReportPost],
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
        ['#create_report_button', sendCreateReportRequest],
        ['#reject_all_reports', sendRejectAllReportsRequest],
        ['#ban_user_btn', sendBanUserRequest],
        ['#unban_user_btn', sendUnbanUserRequest],
        ['.friends_request_accept', sendRequestResponse(true, true)],
        ['.friends_request_reject', sendRequestResponse(true, false)],
        ['.groups_request_accept', sendRequestResponse(false, true)],
        ['.groups_request_reject', sendRequestResponse(false, false)],
        ['.send_request', sendRequest],
        ['.cancel_request', deleteFriendship],
        ['.cancel_friend', deleteFriendFromFriendPage],
        ['.send_g_request', sendGRequest],
        ['.cancel_g_request', deleteGRequest],
        ['#contextual-help', startContextualHelp],
    ];


    listener_list.forEach(function (l) {
        let element = document.querySelectorAll(l[0]);
        [].forEach.call(element, function (element) {
            element.addEventListener('click', l[1]);
        });
    }
    );

    assignFunctionClick('#list_toggle_btn', () => {
        document.querySelector('#toggle_list_A').toggleAttribute('hidden')
        document.querySelector('#toggle_list_B').toggleAttribute('hidden')
    })

    assignFunctionClickAll('.reject_user_report_btn', sendRejectReportRequest)


    // LIKES ACTIONS
    assignFunctionClickAll('.kick_member_button', sendKickpMemberRequest)
    assignFunctionClickAll('.reveal_comment_replies', toggleReplySectionShow)
    assignFunctionClickAll('.popup_btn_report_comment_create', sendCreateReportCommentRequest)

    // OPEN COMMENT POPUPS
    commentPopupsController()
    commentRepliesController()

    // CLOSE POP-UPS ACTION
    assignFunctionClickAll('.close_popup_btn', closePopups)

    // TODO ... passar para o array
    let post_dropDowns = document.querySelectorAll('.dropdownPostButton');
    [].forEach.call(post_dropDowns, function (element) {
        element.addEventListener('click', togglePostDropDown(element.parentNode));
    });


}

function sendRejectAllReportsRequest(event) {
    userID = document.querySelector('#reject_all_reports').dataset.userid

    let res = confirm('Are you sure you want to reject all reports?');
    if (res)
        sendAjaxRequest('put', `/api/report/reject_all/${userID}`, {}, () => { });
}

function sendRejectReportRequest(event) {
    id = event.currentTarget.dataset.reportid

    let res = confirm('Are you sure you want to reject this report?');
    if (res)
        sendAjaxRequest('put', '/api/report', { decision: 'Rejected', id: id }, () => { });
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

function sendBanUserRequest(event) {
    const elem = document.querySelector('#ban_time_select')
    userID = elem.dataset.userid
    time_selected = elem.value

    let res = confirm('Are you sure you want to ban this user?');
    if (res)
        sendAjaxRequest('put', `/api/user/ban/${userID}/${time_selected}`, {}, () => { });
}

function sendUnbanUserRequest(event) {
    userID = document.querySelector('#unban_user_btn').dataset.userid

    let res = confirm('Are you sure you want to unban this user?');
    if (res)
        sendAjaxRequest('put', `/api/user/ban/${userID}/8`, {}, () => { });
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

function toggleReplySectionShow(e) {
    let id = e.currentTarget.dataset.id
    let elem = document.querySelector('#comment_reply_section_' + id)
    elem.toggleAttribute('hidden')
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
        // console.log(this.response)
        // TODO : smth like this for every page
        // alert.innerHTML = this.response !== null ?  JSON.parse(this.response).success : 'Action successful';
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
    let tags = document.querySelector('#popup_show_group_create #group_create_tags').value

    if (name == '' || description == '' || visibility == null) {
        alert('Invalid input');
        return;
    }

    let res = confirm('Are you sure you want to create this group?');
    if (res) {
        sendAjaxRequest('post', '/api/group', { name: name, description: description, visibility: visibility, tags: tags }, addedHandler('#popup_show_group_create'));
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
    let tags = document.querySelector('#popup_show_group_edit #group_edit_tags').value
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
    formData.append('tags', tags);

    let res = confirm('Are you sure you want to edit this group?');
    if (res)
        sendFormData('post', '/api/group/' + oldName, formData, addedHandler('#popup_show_group_edit'));

}

function sendDeleteGroupRequest(e) {
    e.preventDefault();
    let oldName = document.querySelector('#popup_show_group_edit #group_description').dataset.name;
    let res = confirm('Are you sure you want to delete this group?');
    if (res) {
        sendAjaxRequest('delete', '/api/group/' + oldName, {}, () => { window.location = '/home' });
    }
}

function sendKickpMemberRequest(event) {

    let e = event.currentTarget

    let id_group = e.getAttribute('data-idGroup')
    let id_user = e.getAttribute('data-idUser')

    let res = confirm("Are you sure you want to kick this user?");

    if (!res)
        return;

    sendAjaxRequest('delete', `/api/group/${id_group}/member/${id_user}`, null, () => { location.reload(); });

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
    let tags = document.querySelector('#popup_show_profile_edit #profile_edit_tags').value

    let oldName = document.querySelector('#popup_show_profile_edit #user_name').dataset.name
    let idUser = document.querySelector('#popup_show_profile_edit #user_name').dataset.id
    let pho = document.querySelectorAll('#popup_show_profile_edit #profile_pic')[0].files[0];

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
    formData.append('tags', tags);


    let res = confirm('Are you sure you want to edit your profile?');
    if (res) {
        sendFormData('post', '/api/profile/' + oldName, formData, addedHandler('#popup_show_profile_edit'));
        //todo: fazer redirect para o perfil editado
    }
}


function sendDeleteProfileRequest() {
    let username = document.querySelector('#popup_show_profile_edit #user_name').dataset.name

    let res = prompt('Are you sure you want to delete your ' + username + ' account?\nPlease insert your username to confirm:');
    if (res === username)
        sendAjaxRequest('delete', '/api/profile/' + username, {}, console.log);
    else
        alert('Invalid input! Account not deleted!');

}



// ============================================ Likes ============================================



function sendLikePostRequest(event) {
    let e = event.currentTarget
    toggleLikeHTML(e)
    let id_user = e.dataset.uid
    let id_post = e.dataset.id
    sendAjaxRequest('post', '/api/like_post', { id_user: id_user, id_post: id_post }, () => { });
}

function sendLikeCommentRequest(event) {
    let e = event.currentTarget
    toggleLikeHTML(e)
    let id_user = e.dataset.uid
    let id_comment = e.dataset.id
    sendAjaxRequest('post', '/api/like_comment', { id_user: id_user, id_comment: id_comment }, () => { });
}

function toggleLikeHTML(event) {

    let like_a = event
    let hasLiked = like_a.dataset.liked === '1'
    let like_icon = like_a.lastElementChild.firstElementChild

    // console.log(like_a, like_icon, hasLiked)

    like_a.firstElementChild.innerHTML = parseInt(like_a.firstElementChild.innerHTML) + (hasLiked ? -1 : 1);
    like_a.setAttribute('data-liked', (hasLiked ? '0' : '1'));
    like_icon.innerHTML = !hasLiked ? '<i class="fa-solid fa-heart text-danger"></i>' : ' <i class="fa-regular fa-heart text-primary"></i>'

}


// ============================================ Comments ============================================

function sendCreateCommentRequest() {

    let text = document.querySelector('#comment_post_input').value
    let id_post = document.querySelector('#comment_post_input').dataset.pid
    let id_user = document.querySelector('#comment_post_input').dataset.uid

    if (text.trim() === '') {
        return;
    }

    let res = confirm('Are you sure you want to publish this comment?');
    if (res) {
        sendAjaxRequest('post', `/api/comment/${id_post}`, { id_user: id_user, id_post: id_post, text: text }, () => { });
        document.querySelector('#comment_post_input').value = ''
        // TODO falta adicionar comentario e a notificao de action success
    }

}

function sendEditCommentRequest() {
    let id_comment = document.querySelector('#edit_comment_button').dataset.id
    let text = document.querySelector('#comment_text_edit').value

    if (text.trim() === '') {
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

// ============================================ Reports ============================================

function popupControllReportPost() {
    document.querySelector('#popup_show_report_create').toggleAttribute('hidden');
    document.querySelector('#create_report_button').dataset.comment = 0
}

function sendCreateReportRequest(e) {
    e.preventDefault();
    let id_post = document.querySelector('#create_report_button').dataset.post
    let id_comment = document.querySelector('#create_report_button').dataset.comment
    let description = document.querySelector('#report_description').value

    if (description === '') {
        alert('Invalid input');
        return;
    }

    let res = confirm('Are you sure you want to submit this report?');
    if (res) {
        sendAjaxRequest('post', '/api/report/', { description: description, id_post: id_post, id_comment: id_comment }, () => { });
        document.querySelector('#popup_show_report_create').toggleAttribute('hidden');
    }
}

function sendCreateReportCommentRequest(event) {
    const popup = document.querySelector('#popup_show_report_create');
    document.querySelector('#create_report_button').dataset.comment = event.currentTarget.dataset.id
    popup.toggleAttribute('hidden');
}



// ============================================ Post ============================================


function sendCreatePostRequest(isProfile) {
    return function (event) {

        if (isProfile) {
            let textarea = document.querySelector('#popup_show_post textarea');
            let tags = document.querySelector('#post_create_tags');
            let photos = document.querySelector('#popup_show_post #post_photos').files;
            let res = confirm('Are you sure you want to profile post this?');

            let formData = new FormData();

            formData.append('text', textarea.value);
            formData.append('tags', tags.value);

            for (var x = 0; x < photos.length; x++) {
                formData.append("photos[]", photos[x]);
            }



            if (res && textarea != null)
                sendFormData('post', '/api/post/', formData, addedHandler('#popup_show_post'));
            textarea.value = ''
            tags.value = ''
        }

        else {
            let textarea = document.querySelector('#popup_show_group_post textarea');
            let tags = document.querySelector('#post_create_tags');
            let res = confirm('Are you sure you want to group post this?');
            let photos = document.querySelector('#popup_show_group_post #post_photos').files;

            let formData = new FormData();
            formData.append('text', textarea.value);
            formData.append('tags', tags.value);
            formData.append('group_name', textarea.dataset.group);
            for (var x = 0; x < photos.length; x++) {
                formData.append("photos[]", photos[x]);
            }

            if (res && textarea.value != null)
                sendFormData('post', '/api/post/', formData, addedHandler('#popup_show_group_post'));
            textarea.value = ''
            tags.value = ''
        }

        event.preventDefault();
    }
}


function sendEditPostRequest(event) {

    let res = confirm('Are you sure you want to edit this post?');

    let textarea = document.querySelector('#popup_show_post_edit textarea');
    let tags = document.querySelector('#post_edit_tags');
    let photos = document.querySelector('#popup_show_post_edit #edit_post_photos').files;
    let id = document.querySelector('#popup_show_post_edit #delete_post_button').dataset.id


    let formData = new FormData();
    formData.append('text', textarea.value);
    formData.append('tags', tags.value);
    for (var x = 0; x < photos.length; x++) {
        formData.append("photos[]", photos[x]);
    }


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
    if (text.trim() === '')
        return;

    let receiver = document.querySelector('#sms_rcv');

    sendAjaxRequest('post', "/api/message/" + receiver.dataset.id, { text: text }, uploadSms(true, text))
    document.querySelector('#sms_input').value = ''
    let scrollBody = document.querySelector("#message_body")
    scrollBody.scrollTop = scrollBody.scrollHeight
}

function sms_html(art, isSender, message, time) {
    if (isSender) {
        let photo = document.querySelector("#log_in_photo").src;
        let time_anchor = time !== null ? `<p class="small ms-3 mb-3 rounded-3 text-muted">${time}</p>` : '';
        let div = createElementFromHTML(`
        <div class="d-flex flex-row justify-content-end my_sms">
            <div>
                <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">${message}</p>
                ${time_anchor}
            </div>
            <img class="rounded-circle" src="${photo}"
              alt="Self Profile Image" style="width: 45px; height: 100%;">
        </div>`);
        art.appendChild(div);
    }
    else {

        let photo = document.querySelector("#sms_photo").src;
        let time_anchor = time !== null ? `<p class="small ms-3 mb-3 rounded-3 text-muted">${time}</p>` : '';
        let div = createElementFromHTML(`
            <div class="d-flex flex-row justify-content-start rcv_sms">
                <img class="rounded-circle" src="${photo}"
                  alt="Message Sender Profile Image" style="width: 45px; height: 100%;">
                <div>
                    <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">${message}</p>
                    ${time_anchor}
                </div>
            </div>`);
        art.appendChild(div);
    }
}

function uploadSms(isSender, message) { // NAO QUERO SABER SE DEU CORRETO, TALVEZ VER ISSO DPS
    return function () {
        const art = document.createElement("div");

        let date = new Date().toISOString().replace(',', '').replaceAll('/', '-');

        let date_formated = date.substring(0, 10);
        let time_formated = date.substring(11, 19);

        let date_division = document.querySelector('[data-date="' + date_formated + '"]'); // != null ? => se não acrescenta divider


        if (date_division == null) {
            let div = createElementFromHTML(`
            <div class="divider align-items-center mb-4">
                <p class="text-center mx-3 mb-0" data-date="${date_formated}" style="color: #a2aab7;">${date_formated}</p>
            </div>`);

            art.appendChild(div);

            sms_html(art, isSender, message, time_formated);

            document.querySelector('.message_body').appendChild(art.firstChild);
            document.querySelector('.message_body').appendChild(art.lastChild);
            var text = document.createTextNode('');
            document.querySelector('.message_body').append(text);
            return;
        }
        else {
            let last_messanger = document.querySelector('.message_body').lastChild.previousElementSibling
            if ((isSender && last_messanger.classList.contains('justify-content-end')))
            // IT was our last message or the other person last message
            {
                let last_time_anchor = last_messanger.lastElementChild.previousElementSibling.lastElementChild
                last_time_anchor.textContent = time_formated;
                let new_sms = createElementFromHTML(`
                <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">${message}</p>
                `);
                last_messanger.lastElementChild.previousElementSibling.insertBefore(new_sms, last_time_anchor)
            }
            else if ((!isSender && last_messanger.classList.contains('justify-content-start'))) {
                let last_time_anchor = last_messanger.lastElementChild.lastElementChild
                last_time_anchor.textContent = time_formated;
                let new_sms = createElementFromHTML(`
                <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">${message}</p>
                `);
                last_messanger.lastElementChild.insertBefore(new_sms, last_time_anchor);
            }
            else {
                sms_html(art, isSender, message, time_formated);
            }
        }

        if (art.firstElementChild !== null) {
            document.querySelector('.message_body').append(art.firstElementChild);
            var text = document.createTextNode('');
            document.querySelector('.message_body').append(text);
        }
    }
}


addEventListeners();


// =================================== Home ==========================================

let offset = 0;

function updateFeed(feed) {

    let pathname = window.location.pathname
    if (pathname !== '/home') return;

    let type_order = 'popularity';
    let orders = document.querySelectorAll('.feed-order');
    if (orders) {
        orders.forEach(function (order) {
            document.querySelector('.feed_order_dropdown_btn').setAttribute('hidden', 'hidden')
            if (order.checked) type_order = order.value
        })
    }

    if (!document.querySelector('#timeline')) {
        return;
    }

    sendAjaxRequest('get', '/api/post/feed/' + feed + '/order/' + type_order + '/offset/' + offset, {}, function () {

        let received = JSON.parse(this.responseText);
        let timeline = document.querySelector('#timeline');

        if (offset === 0) {
            timeline.innerHTML = '';
        }

        if (received.length === 0) {
            timeline.appendChild(createElementFromHTML(`<h3 class="text-center" style="margin-top:4em">No content to show</h3>`));
        }

        received.forEach(function (post) {
            timeline.appendChild(createPost(post))
        })

        offset += 5
    })


}

function updateFeedOnLoad() {
    let feed_filters = document.querySelector('#feed_radio_viral')
    if (feed_filters) {
        feed_filters.checked = true
    }
    offset = 0
    updateFeed('viral')
}

function updateFeedOnOrder() {

    let orders = document.querySelectorAll('.feed-order')

    if (!orders) return;

    orders.forEach(function (order) {
        order.addEventListener('click', function () {
            let filters = document.querySelectorAll('#feed_filter input')
            if (!filters) return;

            let checked_filter;
            filters.forEach(function (filter) {
                if (filter.checked) checked_filter = filter.value;
            })

            offset = 0
            updateFeed(checked_filter)
        })
    })

}

function updateFeedOnClick() {

    let filters = document.querySelectorAll('.feed-filter')

    if (!filters) return;

    filters.forEach(function (filter) {
        filter.addEventListener('click', function () {
            offset = 0
            updateFeed(filter.value)
        })
    })

}

function updateFeedOnScroll() {

    window.onscroll = function (ev) {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 2) {
            let filters = document.querySelectorAll('#feed_filter input')

            let checked_filter = 'viral';
            if (filters) {
                filters.forEach(function (filter) {
                    if (filter.checked) checked_filter = filter.value;
                })
            }

            updateFeed(checked_filter)
        }
    };
}

updateFeedOnLoad();
updateFeedOnOrder();
updateFeedOnScroll();
updateFeedOnClick();

function createPost(post) {

    let new_post = document.createElement('article');
    new_post.classList.add('post');

    let images = '', bottom = '', like = '', dropdown = '';

    imageControls = `
     <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev" style="filter: invert(100%);">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next" style="filter: invert(100%);">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </a>`

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
            <a class="dropdown-item" href="/messages/${post.owner}">Send Message</a>`
        }

        bottom = `
        <div class="d-flex">
            <p class="me-3">${post.likes_count}</p>

            <a href="#!" onclick="sendLikePostRequest(event)" class="like_btn_post text-decoration-none" data-uid=${post.auth} data-id=${post.id}>
               ${like}
            </a>
        </div>
        `

    } else {

        bottom = `
        <div class="d-flex">
            <p class="me-3">${post.likes_count}</p>
            <a href="#!" onclick="sendLikePostRequest(event)" class="like_btn_post text-decoration-none">
                <h2><strong>&#9825;</strong></h2>
            </a>
        </div>
        `
    }

    if (post.images.length !== 0) {
        let imageDiv = '';

        post.images.forEach(function (image, i) {
            imageDiv += `
                <div class="carousel-item ${i == 0 ? 'active' : ''}">
                    <img class="d-block w-100" src="/${image.path}" alt="Post Content Image">
                </div>
            `
        })

        images = `
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                ${imageDiv}
            </div>
            ${post.images.length > 1 ? imageControls : ''}
        </div>

        `
    }



    new_post.innerHTML = `

<div class="container mt-5 mb-5 post_item" style="width:50em">
    <div class="row d-flex align-items-center mw-50 justify-content-center ">
            <div class="card post_card p-0">
                <div>
                    <div class="card-header d-flex justify-content-between p-2 px-3">

                        <a href='/profile/${post.owner}' .
                            class="text-decoration-none d-flex flex-row align-items-center">
                            <img src="/${post.photo}" width="60" class="rounded-circle me-3" alt="Post Owner Profile Image">
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


                    <div>
                        <p class="text-justify p-3">${post.text}</p>


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

    query_string = pathname.replaceAll('%20', ' ').split('/')[2]

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

        if (received.length === 0) {
            timeline.innerHTML = `<h3 class="text-center mt-5">No results found</h3>`
        }

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


}



function createUserCard(user) {
    let new_card = document.createElement('article');

    let bio_short = user.bio;
    if (bio_short.length > 50)
        bio_short = user.bio.substring(0, 100) + '...'

    new_card.innerHTML = `
    <div class="card mt-4 me-3" style="width: 15em;height:29em">
        <img height="50%" src="/${user.photo}" class="card-img-top" alt="User Profile Image">
        <div class="card-body">
            <h5 class="card-title">${user.username}</h5>
            <p class="card-text">${bio_short}</p>

        </div>

        <div class="card-footer d-flex flex-wrap justify-content-center align-items-center bg-white">
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
    <div class="card mt-4 me-3" style="width: 15em;height:29em;justify-content:between">
        <img height="60%" src="/${group.photo}" class="card-img-top" alt="Group Profile Image">
        <div class="card-body">
            <h5 class="card-title">${group.name}</h5>
            <p class="card-text">${bio_short}</p>

        </div>

        <div class="card-footer d-flex flex-wrap justify-content-center align-items-center bg-white">
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
        <img class="me-3 rounded-circle" src="/${user.photo}" alt="User Report Profile Image" width="50" height="50">
        <a class="me-3" href='/profile/${user.username}'>${user.username}</a>
        <a>${user.report_count} reports</a>
        <a href="/admin/report/${user.username}" class="btn btn-outline-dark">View</a>
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
        <img class="me-3 rounded-circle" src="/${user.photo}" alt="User Report Profile Image" width="50" height="50">
        <a class="me-3" href='/profile/${user.username}'>${user.username}</a>
        <a class="text-muted text-decoration-none">${user.decision_date}</a>
    ` + button + `
        <a href="/admin/report/${user.username}" class="btn btn-outline-dark">Edit</a>
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

function updateNrNotfications() {
    let nr = document.querySelector('#notf_nr');
    let nr2 = document.querySelector('#notf_nr2');
    if (nr === null || nr2 === null)
        return;
    nr.innerHTML = _notifications.length;
    nr2.innerHTML = _notifications.length;

    if ((nr.hidden && _notifications.length > 0) || (!nr.hidden && _notifications.length === 0)) {
        document.querySelector('#notf_nr').toggleAttribute('hidden');
        document.querySelector('#notf_nr2').toggleAttribute('hidden');
    }


}

function getNotifications() {
    sendAjaxRequest('get', "/api/user/notifications", {}, function () {

        let received = JSON.parse(this.responseText);
        _notifications = _notifications.concat(received);
        updateNrNotfications();
    });
}

getNotifications();

/*
    this should mark a notification as seen when tapping in the notf.
*/
function markAsSeen($id, e) {
    return function () {
        sendAjaxRequest('put', "/api/user/notification/" + $id + "/seen", {}, function () {
            if (this.status == 200) {
                let _x = _notifications.findIndex(x => x.id == $id);
                _notifications.splice(_x, 1);
                e.remove();
                updateNrNotfications();
            }
        });
    }
}

function markAllAsSeen(e) {
    if (e !== null)
        e.preventDefault();
    createNotificationList(null)
    sendAjaxRequest('put', "/api/user/notifications/seen", {}, function () {
        if (this.status == 200) {
            _notifications = [];
            let nots = document.querySelectorAll('#notifications_container>div')
            nots.forEach((e) => e.remove());
            updateNrNotfications();
        }
    });
}

// taken from https://stackoverflow.com/questions/3177836/how-to-format-time-since-xxx-e-g-4-minutes-ago-similar-to-stack-exchange-site
function timeSince(date) {

    var seconds = Math.floor((new Date() - date) / 1000);

    var interval = seconds / 31536000;

    if (interval > 1) {
        return Math.floor(interval) + " years";
    }
    interval = seconds / 2592000;
    if (interval > 1) {
        return Math.floor(interval) + " months";
    }
    interval = seconds / 86400;
    if (interval > 1) {
        return Math.floor(interval) + " days";
    }
    interval = seconds / 3600;
    if (interval > 1) {
        return Math.floor(interval) + " hours";
    }
    interval = seconds / 60;
    if (interval > 1) {
        return Math.floor(interval) + " minutes";
    }
    return Math.floor(seconds) + " seconds";
}

function createCustomMessageBody(notf) {

    if (notf.tipo == "Comment") {
        if (notf.id_parent == null)
            return notf.sender.username + " made a comment in your <a href=/post/" + notf.id_post + ">Post</a>";
        else
            return notf.sender.username + " replied to your comment at <a href=/post/" + notf.id_post + ">Post</a>";
    }
    else if (notf.tipo == "FriendRequest") {
        return "<a href=/profile/" + notf.sender.username + ">" + notf.sender.username + "</a>" + " wants to connect"; // TODO. accept/reject
    }
    else if (notf.tipo == "Like") {
        if (notf.id_post != null)
            return notf.sender.username + " liked your <a href=/post/" + notf.id_post + "> Post</a>";
        else
            return notf.sender.username + " liked your comment in <a href=/post/" + notf.id_post + "> Post</a>"; // TODO: temos de ir buscar o post na mesma ... mudar bd
    }
    else if (notf.tipo == "UserMention") {
        return notf.sender.username + " mentioned you in <a href=/post/" + notf.id_post + "> Post</a>";
    }

}

side_bar_text = [];

function createNotificationList(event) {

    if (event !== null)
        event.preventDefault();

    let notifications = document.querySelector('#notifications_container');

    if (notifications.style.visibility == 'hidden' || notifications.style.visibility == '') {
        notifications.style.visibility = 'visible';

        let side_bar_elms = document.querySelectorAll('.enc');

        [].forEach.call(side_bar_elms, function (e, i) {
            if (e.textContent != "" && !e.textContent.includes("Post")) {
                side_bar_text[i] = " " + e.textContent
                console.log(side_bar_text)
                e.removeChild(e.lastChild);
                e.style.display = 'none';
            }
        })
        let bar = document.querySelector('#leftbar');
        bar.style.width = "100px";

        document.querySelector('#popup_btn_post span').style.display = 'none';
        document.querySelector('#popup_btn_post').style.width = '100%';

        // ISTO DEVIA SER MUDADO PARA SO MOSTRAR AS NOTIFICAÇÕES QUE NÃO ESTÃO VISTAS E DPS PODEMOS MARCAR COMO  --DONE
        // tb meter um numero limitado
        if (_notifications.length == 0) {
            notifications.appendChild(createElementFromHTML('<h4 class="mt-5 text-center">No pending notifications</h4>'))
            notifications.appendChild(createElementFromHTML('<button onClick="createNotificationList(null)" class="btn btn-secondary mt-5 text-center w-100">Close Window</button>'));
        } else {

            let clear_all = createElementFromHTML('<a href="#!" id="markAllAsSeen_notifications" class="btn btn-outline-secondary mt-3 mb-3 w-100">Clear all</a>')
            notifications.appendChild(clear_all);
            assignFunctionClick('#markAllAsSeen_notifications', markAllAsSeen)
        }

        for (let i = 0; i < _notifications.length; i++) {
            let notf = createElementFromHTML(`
        <div class="toast show mb-3" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
            <div class="toast-header">
              <img src="/${_notifications[i].sender.photo}" class="rounded me-2 img-fluid" alt="User Profile Image" style="max-width: 100%; height: auto; width: 3em">
              <strong class="me-auto">${_notifications[i].sender.username}</strong>
              <small class="text-muted">${timeSince(new Date(_notifications[i].notification_date))}</small>
              <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
              ${createCustomMessageBody(_notifications[i])}.
            </div>
        </div>`);
            notifications.appendChild(notf);
            notf.querySelector('.btn-close').addEventListener('click', markAsSeen(_notifications[i].id, notf));
        }
    } else {
        document.querySelector('#popup_btn_post span').style.display = '';
        notifications.style.visibility = 'hidden';
        notifications.innerHTML = '';

        let side_bar_elms = document.querySelectorAll('.enc');
        [].forEach.call(side_bar_elms, function (e, i) {
            console.log(side_bar_text)
            if (side_bar_text[i] != "" && side_bar_text[i] != undefined) {
                let textNode = document.createTextNode(side_bar_text[i]);
                e.appendChild(textNode);
                e.style.display = '';
            }
        })
        let bar = document.querySelector('#leftbar');
        bar.style.width = "20em";
    }
}

// ==================================== FRIENDS/GROUPS REQUESTS ============================================


function sendRequestResponse(isAFriendReq, accept) {
    return function () {
        let id = this.id.split("_")[1];
        let response = accept ? "accept" : "reject";

        let gname = window.location.pathname.split('/')[2];;

        let reqURI = isAFriendReq ? "/api/user/friend/request/" + id + "/" + response : '/api/group/' + gname + '/request/' + id + "/" + response;
        console.log(reqURI);
        let queryReqs = isAFriendReq ? "#friend_request_" + id : "#group_request_" + id
        sendAjaxRequest('put', reqURI, {}, function () {
            if (this.status == 200) {
                let friend_request = document.querySelector(queryReqs);
                friend_request.remove();
            }
            addedHandler(null).call(this);
        });
    }
}


function sendRequest() {
    let parent = this;
    let child = this.firstChild;
    sendAjaxRequest('post', "/api/user/friend/request/" + child.dataset.id + "/send", {}, function (e) {
        if (this.status == 200) {
            child.classList.remove('fa-user-plus');
            child.classList.remove('fa-user-plus');
            child.classList.remove('send_request');
            child.classList.add('fa-user-clock');
            parent.removeEventListener('click', sendRequest);
            parent.addEventListener('click', deleteFriendship);
            parent.classList.add('cancel_request');
            parent.classList.remove('send_request');
        }
        addedHandler(null).call(this);
    });
}

function sendGRequest() {
    let parent = this;
    let child = this.firstChild;
    sendAjaxRequest('post', "/api/group/request/" + child.dataset.id + "/send", {}, function (e) {
        if (this.status == 200) {
            child.classList.remove('fa-door-open');
            child.classList.remove('send_g_request');
            child.classList.add('fa-clock-rotate-left');
            parent.removeEventListener('click', sendGRequest);
            parent.addEventListener('click', deleteGRequest);
            parent.classList.add('cancel_g_request');
            parent.classList.remove('send_g_request');
        }
        addedHandler(null).call(this);
    });
}

function deleteGRequest() {
    let parent = this;
    let child = this.firstChild;
    sendAjaxRequest('delete', "/api/group/request/" + child.dataset.id, {},
        function (e) {
            if (this.status == 200) {
                child.classList.remove('fa-clock-rotate-left');
                child.classList.add('fa-door-open');
                child.classList.add('send_g_request');
                parent.removeEventListener('click', deleteGRequest);
                parent.addEventListener('click', sendGRequest);
                parent.classList.remove('cancel_g_request');
                parent.classList.add('send_g_request');
            }
            addedHandler(null).call(this);
        });
}

function deleteFriendship() {
    let parent = this;
    let child = this.firstChild;
    sendAjaxRequest('delete', "/api/user/friend/" + child.dataset.id, {},
        function (e) {
            if (this.status == 200) {
                child.classList.remove('fa-user-clock');
                child.classList.add('fa-user-plus');
                child.classList.add('send_request');
                parent.removeEventListener('click', deleteFriendship);
                parent.addEventListener('click', sendRequest);
                parent.classList.remove('cancel_request');
                parent.classList.add('send_request');
            }
            addedHandler(null).call(this);
        });
}

function deleteFriendFromFriendPage() {
    let card = this.parentNode.parentNode.parentNode
    sendAjaxRequest('delete', "/api/user/friend/" + this.dataset.id, {}, function (e) {
        if (this.status == 200) {
            card.style = "display: none";
        }
        addedHandler(null).call(this);
    });
}




function fillNotificationPage() {


    let notifications = document.querySelector('#notifications_list_container');


    if (_notifications.length == 0) {
        notifications.appendChild(createElementFromHTML('<h4 class="mt-5 text-center">No pending notifications</h4>'))
    } else {

        let clear_all = createElementFromHTML('<a href="#!" id="markAllAsSeen_notifications2" class="btn btn-outline-secondary mt-3 mb-3 w-100">Clear all</a>')
        notifications.appendChild(clear_all);
        assignFunctionClick('#markAllAsSeen_notifications2', (e) => {
            markAllAsSeen(e);

            let notifications_items = document.querySelectorAll('#notifications_list_container .toast');

            notifications_items.forEach(e => {
                e.remove();
            });

            let aux = document.querySelector('#markAllAsSeen_notifications2');
            aux.remove();
            notifications.appendChild(createElementFromHTML('<h4 class="mt-5 text-center">No pending notifications</h4>'))

        })
    }

    for (let i = 0; i < _notifications.length; i++) {
        let notf = createElementFromHTML(`
            <div class="toast show mb-3" style="width:100%;height:8em;">
                <div class="toast-header">
                  <img src="/${_notifications[i].sender.photo}" class="rounded me-2 img-fluid" alt="User Profile Image" style="max-width: 100%; height: auto; width: 3em">
                  <strong class="me-auto">${_notifications[i].sender.username}</strong>
                  <small class="text-muted">${timeSince(new Date(_notifications[i].notification_date))}</small>
                  <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                  ${createCustomMessageBody(_notifications[i])}.
                </div>
            </div>`);
        notifications.appendChild(notf);
        notf.querySelector('.btn-close').addEventListener('click', markAsSeen(_notifications[i].id, notf));
    }
}


if (window.location.pathname == "/notifications") {
    setTimeout(() => {
        fillNotificationPage();
    }, 1000);
}


if (window.location.pathname.substring(0, 10) == "/messages/") {

    const input = document.getElementById("sms_input");

    let scrollBody = document.querySelector("#message_body")
    scrollBody.scrollTop = scrollBody.scrollHeight

    input.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            document.getElementById("sms_send_btn").click();
        }
    });
}


if (window.location.pathname.substring(0, 6) == "/post/") {

    const input = document.getElementById("comment_post_input");

    input.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            document.getElementById("comment_post_send").click();
        }
    });
}

function groupPostResponsiveUI() {
    let width = (window.innerWidth > 0) ? window.innerWidth / 16 : screen.width / 16;

    if (width <= 80)
        document.querySelector('#list_toggle_btn').click()
}


function checkResponsiveUI() {
    let width = (window.innerWidth > 0) ? window.innerWidth / 16 : screen.width / 16;

    let page_a = document.getElementById("toggle_list_A")
    let page_b = document.getElementById("toggle_list_B")
    let btn = document.getElementById("list_toggle_btn_div")
    let title = document.querySelector("h1")
    let toggle = document.getElementById("list_toggle_btn")

    if (width <= 80) {

        page_a.hidden = ""
        btn.style.visibility = "visible"

        if (!toggle.checked) {
            if (title)
                title.hidden = ""
            page_b.hidden = "hidden"
        }
        else {
            if (title)
                title.hidden = "hidden"
            page_a.hidden = "hidden"
        }

    } else {
        page_b.hidden = ""
        page_a.hidden = ""
        if (title)
            title.hidden = ""
        btn.style.visibility = "hidden"
    }
}

let curr_path = window.location.pathname
if (curr_path.substring(0, 7) == "/group/" || curr_path.substring(0, 10) == "/messages/") {
    setInterval(() => { checkResponsiveUI() }, 500);
}


// Enable pusher logging - don't include this in production
Pusher.logToConsole = false;

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
            id_post: data.obj.id_post ?? (data.obj.comment !== undefined ? data.obj.comment.id_post : null),
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
                let aux = `<a href="/messages/${data.sender.username}">${data.sender.username}</a>`
                addNotification(aux + ' messaged you: ' + data.obj.text, data.sender);
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
            addNoticomment_reply_btnfication(createCustomMessageBody(notfiableJsonPrototype), data.sender);
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
        //console.log("GetRTCSessionDescription called");
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
                    urls: "stun:relay.metered.ca:80",
                },
                {
                    urls: "turn:relay.metered.ca:80",
                    username: "efb717a8ddfc21d15bb72a5d",
                    credential: "/8ZyEZ2tVt/zMXVW",
                },
                {
                    urls: "turn:relay.metered.ca:443",
                    username: "efb717a8ddfc21d15bb72a5d",
                    credential: "/8ZyEZ2tVt/zMXVW",
                },
                {
                    urls: "turn:relay.metered.ca:443?transport=tcp",
                    username: "efb717a8ddfc21d15bb72a5d",
                    credential: "/8ZyEZ2tVt/zMXVW",
                },
            ],
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

    channel.bind("client-candidate", function (msg) {
        if (msg.room == room) {
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
    var url_atual = window.location.pathname;
    if (url_atual == "/home") {
        introJs().setOptions({
            steps: [{
                intro: "This is our Home page"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
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
    else if (url_atual == "/user/friends/requests") {
        introJs().setOptions({
            steps: [{
                intro: "This is our my friends request page"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#popup_btn_post"),
                intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#timeline"),
                intro: "Here you can see all your friend request.You can accept ou declined the request"
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

    else if (url_atual.match(/group_list\/.*/)) {
        introJs().setOptions({
            steps: [{
                intro: "This is our my groups list page"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#popup_btn_post"),
                intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
            },
            {
                element: document.querySelector("#popup_btn_group_create"),
                intro: "Here you can create a new Group"
            },

            {
                element: document.querySelector("#timeline"),
                intro: "Hero you can see all your groups "
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

    else if (url_atual == "/messages" || url_atual.match(/messages\/.*/)) {
        introJs().setOptions({
            steps: [{
                intro: "This is our messages page"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#popup_btn_post"),
                intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
            },
            {
                element: document.querySelector("#message_body"),
                intro: "You can see all the messages with your friend and you can write another message. Isn't that amazing???😮"
            },
            {
                element: document.querySelector("#toggle_list_B"),
                intro: "You can see all the users you send messages recently"
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
    else if (url_atual.match(/profile\/.*/) && !url_atual.match(/edit_profile\/.*/)) {
        introJs().setOptions({
            steps: [{
                intro: "This is a profile page"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#popup_btn_post"),
                intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
            },
            {
                element: document.querySelector("#user_info"),
                intro: "Here you can see all the information about the profile"
            },

            {
                element: document.querySelector("#user_statistics"),
                intro: "Here you can see how many friends,groups,likes and comments the profile have "
            },
            {
                element: document.querySelector("#timeline"),
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

    else if (url_atual.match(/edit_profile\/.*/)) {
        introJs().setOptions({
            steps: [{
                intro: "Here you can change all the information about your profile"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#popup_btn_post"),
                intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
            },

            {
                element: document.querySelector("#edit_profile_button"),
                intro: "Save all the changes of your profile"
            },
            {
                element: document.querySelector("#delete_profile_button"),
                intro: "Delete your account"
            },


            {
                element: document.querySelector("#timeline"),
                intro: "Make all the changes you want"
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
    else if (url_atual.match(/user\/.*/)) {
        introJs().setOptions({
            steps: [{
                intro: "This is our my friends page"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#popup_btn_post"),
                intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
            },
            {
                element: document.querySelector("#timeline"),
                intro: "Here you can see all your friends,if you want you can remove a friend or just watch the friend´s profile"
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

    else if (url_atual.match(/like_list\/.*/)) {
        introJs().setOptions({
            steps: [{
                intro: "This is our like_list page"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#popup_btn_post"),
                intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
            },
            {
                element: document.querySelector("#timeline"),
                intro: "Here you can see a list of your entire likes"
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
    else if (url_atual.match(/group\/.*/)) {
        introJs().setOptions({
            steps: [{
                intro: "This is our group page"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#timeline"),
                intro: "We allow you to see all the posts of the group. Isn't that amazing???😮"
            },
            {
                element: document.querySelector("#rightbar"),
                intro: "Here you can see all the information about the group"
            },

            {
                element: document.querySelector("#popup_btn_group_post"),
                intro: "Here you can post in group"
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
    else if (url_atual.match(/comment_list\/.*/)) {
        introJs().setOptions({
            steps: [{
                intro: "This is our comment list page "
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#popup_btn_post"),
                intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
            },
            {
                element: document.querySelector("#timeline"),
                intro: "We allow you to see all your comments in other posts"
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

    else if (url_atual.match(/search\/.*/)) {
        introJs().setOptions({
            steps: [{
                intro: "This is our search page.Here you can all the information you search"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#feed_filter"),
                intro: "We allow you to see different timelines of your search. Isn't that amazing???😮"
            },
            {
                element: document.querySelector("#popup_btn_post"),
                intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
            },
            {
                element: document.querySelector("#timeline"),
                intro: "The result of your search"
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

    else if (url_atual == "/about") {
        introJs().setOptions({
            steps: [{
                intro: "This is our about page"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#popup_btn_post"),
                intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
            },
            {
                element: document.querySelector("#timeline"),
                intro: "Here you can see who we are or what is nexus and How did Nexus come about"
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

    else if (url_atual == "/contacts") {
        introJs().setOptions({
            steps: [{
                intro: "This is our contacts page"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#popup_btn_post"),
                intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
            },
            {
                element: document.querySelector("#timeline"),
                intro: "Here you can see who we are and a small description about us"
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
    else if (url_atual == "/features") {
        introJs().setOptions({
            steps: [{
                intro: "This is our features page"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#popup_btn_post"),
                intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
            },
            {
                element: document.querySelector("#timeline"),
                intro: "Here you can see all the features we have"
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
    else if (url_atual == "/admin") {
        introJs().setOptions({
            steps: [{
                intro: "This is our admin page"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#popup_btn_post"),
                intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
            },
            {
                element: document.querySelector("#toggle_list_A"),
                intro: "Here you can see all the reports"
            },
            {
                element: document.querySelector("#searchBarPendent"),
                intro: "Here you can search  for all the reports"
            },
            {
                element: document.querySelector("#list_toggle_btn"),
                intro: "Here you can choose if you want only past reports or pendent reports"
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

    else if ((url_atual.match(/admin\/.*/)) && !url_atual.match(/report\/.*/)) {
        introJs().setOptions({
            steps: [{
                intro: "This is our admin statistics page"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#popup_btn_post"),
                intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
            },
            {
                element: document.querySelector("#list-group align-items-center mb-5 mx-5"),
                intro: "Here you can see all the admin statistics"
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
    else if ((url_atual.match(/report\/.*/))) {
        introJs().setOptions({
            steps: [{
                intro: "This is our report page"
            }, {
                element: document.querySelector('#leftbar').firstElementChild,
                intro: "Here you can quickly access the main features of our app"
            },
            {
                element: document.querySelector("#search_bar"),
                intro: "Here you can search for any topic/group or user "
            },
            {
                element: document.querySelector("#popup_btn_post"),
                intro: "You can make a post here ... <img src=\"http://www.quickmeme.com/img/8d/8d758a58bdccfedcec9d16d4a028b664cbaa9ceb4c1e14f5d160aa200da60bd2.jpg\" class=\"help_photo\"/>"
            },
            {
                element: document.querySelector("#ban_time_select"),
                intro: "Here you can decide what time the user is baned"
            },
            {
                element: document.querySelector("#list_toggle_btn"),
                intro: "Here you can choose if you want only past reports or pendent reports"
            },
            {
                element: document.querySelector("#reports_list"),
                intro: "Here you can see all the reports about the user and all the information about the user"
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

    let listener_list = [
        ['#popup_btn_post', () => { logItem('#popup_show_post')(); bubble_tags_array = []; }],
        ['#popup_btn_group_post', logItem('#popup_show_group_post')],
        ['#popup_btn_group_create', logItem('#popup_show_group_create')],
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
    assignFunctionClickAll('.promoteToOwner', sendNewOwnerRequest)


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
    if (res) {
        sendAjaxRequest('put', `/api/report/reject_all/${userID}`, {}, () => { });
        document.querySelector('#reports_list').innerHTML = ''
        document.querySelector('#reports_list').innerHTML = '<h3 class="text-center mt-4">No reports to show</h3>'
        document.querySelector('#reports_list_count').innerHTML = '0'
        document.querySelector('#reject_all_reports').remove()
    }
}

function sendRejectReportRequest(event) {
    id = event.currentTarget.dataset.reportid

    let res = confirm('Are you sure you want to reject this report?');
    if (res) {
        sendAjaxRequest('put', '/api/report', { decision: 'Rejected', id: id }, function () {
            let report_div = document.createElement('div')
            report_div.innerHTML = this.responseText
            document.querySelector('#reports_decided_list').appendChild(report_div)
            let count = document.querySelector(`#reports_decided_list_count`)
            count.innerHTML = parseInt(count.innerHTML) + 1;
            let aux = document.querySelector('#no_past_reports_sms');
            if (aux)
                aux.remove()

            addedHandler(null).call(this)
        });
        document.querySelector(`#reports_list_item_${id}`).remove()
        document.querySelector('#reports_list_count').innerHTML = parseInt(document.querySelector('#reports_list_count').innerHTML) - 1;
        if (document.querySelector('#reports_list_count').innerHTML == 0) {
            document.querySelector('#reports_list').innerHTML = '<h3 class="text-center mt-4">No reports to show</h3>'
            document.querySelector('#reject_all_reports').remove()
        }
    }

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
    username = elem.dataset.name
    time_selected = elem.value

    let res = confirm('Are you sure you want to ban this user?');
    if (res) {
        sendAjaxRequest('put', `/api/user/ban/${userID}/${time_selected}`, {}, () => { });
    }

}

function sendUnbanUserRequest(event) {
    userID = document.querySelector('#unban_user_btn').dataset.userid

    let res = confirm('Are you sure you want to unban this user?');
    if (res) {
        sendAjaxRequest('put', `/api/user/ban/${userID}/8`, {}, () => { });
        location.reload()
    }

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

        let drop = document.querySelector('.dropdown_menu')
        if (drop)
            drop.hidden = true;
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



    giveBackBubbleTags()
}


// ============================================ GROUPS ============================================

function sendCreateGroupRequest(event) {

    event.preventDefault()
    let name = document.querySelector('#popup_show_group_create #group_name').value
    let description = document.querySelector('#popup_show_group_create #group_description').value
    let visibility = document.querySelector('#popup_show_group_create #group_visibility').checked
    let tags = bubble_tags_array.join(' ');

    name = name.split(' ').join('_');

    if (name == '' || description == '' || visibility == null) {
        alert('Invalid input');
        return;
    }

    let res = confirm('Are you sure you want to create this group?');
    if (res) {
        sendAjaxRequest('post', '/api/group', { name: name, description: description, visibility: visibility, tags: tags }, function () {
            addedHandler('#popup_show_group_create')
            if (this.status == 200) {
                location.pathname = '/group/' + name;
            }
        })

        // TODO: Fazer redirect para grupo criado
    }

}


function sendEditGroupRequest(event) {

    event.preventDefault();
    let name = document.querySelector('#group_edit_page #group_name').value
    let description = document.querySelector('#group_edit_page #group_description').value
    let visibility = document.querySelector('#group_edit_page #group_visibility').checked
    let oldName = document.querySelector('#group_edit_page #group_description').dataset.name
    let id_group = document.querySelector('#group_edit_page #group_description').dataset.id
    let tags = bubble_tags_array.join(' ');
    let pho = document.querySelector('#group_edit_page #group_photo').files[0]

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
    if (!res)
        return;
    sendFormData('post', '/api/group/' + oldName, formData,
        function () {

            if (this.status >= 200 && this.status < 300) {
                location.hash = 'success'
                location = '/group/' + name;
            }
            else {
                location.hash = 'error'
                location.pathname = '/group/' + oldName;
            }
        });
}

function sendDeleteGroupRequest(e) {
    e.preventDefault();
    let oldName = document.querySelector('#group_edit_page #group_description').dataset.name;
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

function sendNewOwnerRequest(event) {
    let e = event.currentTarget

    let id_group = e.getAttribute('data-idGroup')
    let id_user = e.getAttribute('data-idUser')

    let res = confirm("Are you sure you want to promote this user?");

    if (!res)
        return;

    sendAjaxRequest('post', `/api/group/${id_group}/owner/${id_user}`, null, () => { location.reload() });

}





function sendDeleteGroupMemberRequest(e) {

    e.preventDefault()
    let id_group = document.querySelector('#leave_group_button').getAttribute('data-idGroup');
    let id_user = document.querySelector('#leave_group_button').getAttribute('data-idUser');

    let res = confirm("Are you sure you want to leave this group?");

    if (!res)
        return;

    sendAjaxRequest('delete', `/api/group/${id_group}/member/${id_user}`, null, function () {
        if (this.status >= 200 && this.status < 300) {
            location.hash = 'success'
            location.reload();
        }
        else {
            addedHandler(null).call(this)
        }

    });
}


// ============================================ Profile ============================================


function sendEditProfileRequest(event) {

    event.preventDefault();
    let username = document.querySelector('#profile_edit_page #user_name').value
    let email = document.querySelector('#profile_edit_page #user_email').value
    let bdate = document.querySelector('#profile_edit_page #user_bdate').value
    let bio = document.querySelector('#profile_edit_page #user_bio').value
    let visibility = document.querySelector('#profile_edit_page #profile_visibility').checked
    let tags = bubble_tags_array.join(' ');

    let oldName = document.querySelector('#profile_edit_page #user_name').dataset.name
    let idUser = document.querySelector('#profile_edit_page #user_name').dataset.id

    let pho = document.querySelectorAll('#profile_edit_page #profile_pic')[0].files[0];

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
    formData.append('idUser', idUser);


    let res = confirm('Are you sure you want to edit your profile?');
    if (res) {
        sendFormData('post', '/api/profile/' + oldName, formData, function () {

            if (this.status >= 200 && this.status < 300) {
                location.hash = 'success'
                location = '/profile/' + username;
            }
            else {
                location.hash = 'error'
                location.pathname = '/profile/' + oldName;
            }

        });

    }
}

if (window.location.hash === "#success") {
    let r = { status: 201 };
    setTimeout(function () {
        addedHandler(null).call(r)
    }, 500);
}
else if (window.location.hash === "#error") {
    let r = { status: 400 };
    setTimeout(function () {
        addedHandler(null).call(r)
    }, 500);
}


function sendDeleteProfileRequest(e) {
    e.preventDefault();
    let username = document.querySelector('#profile_edit_page #user_name').dataset.name

    let res = prompt('Are you sure you want to delete your "' + username + '" account?\nPlease insert your username to confirm:');
    if (res === username) {
        sendAjaxRequest('delete', '/api/profile/' + username, {}, () => { window.location = '/home' });
    }
    else {
        alert('Account not deleted!');
    }
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

    //console.log(like_a, like_icon, hasLiked)

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
        sendAjaxRequest('post', `/api/comment/${id_post}`, { id_user: id_user, id_post: id_post, text: text }, function () {
            let comment_div = document.createElement('div')
            comment_div.innerHTML = this.responseText
            let e = comment_div.querySelector('.popup_btn_comment_edit')

            e.addEventListener('click', () => {
                let id = e.dataset.id
                let elem = document.querySelector('#popup_show_comment_edit')
                elem.toggleAttribute('hidden')
                document.querySelector('#comment_text_edit').value = e.dataset.text
                document.querySelector('#edit_comment_button').setAttribute('data-id', id)
                document.querySelector('#delete_comment_button').setAttribute('data-id', id)
            });

            document.querySelector('#post_comment_section').appendChild(comment_div)
            addedHandler(null).call(this)
        });
        document.querySelector('#comment_post_input').value = ''
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
        sendAjaxRequest('put', `/api/comment`, { id_comment: id_comment, text: text }, function () {
            addedHandler(null).call(this)
        });
        document.querySelector(`#comment_item_${id_comment} p.card-text`).innerHTML = text
        logItem('#popup_show_comment_edit')(0);
    }

}


function sendDeleteCommentRequest() {
    let id_comment = document.querySelector('#delete_comment_button').dataset.id

    let res = confirm('Are you sure you want delete this comment?');
    if (res) {
        sendAjaxRequest('delete', `/api/comment/${id_comment}`, {}, function () {
            addedHandler(null).call(this)
        });
        document.querySelector(`#comment_item_${id_comment}`).remove()
        logItem('#popup_show_comment_edit')(0);
    }
}

// ============================================ Reports ============================================

function popupControllReportPost() {
    document.querySelector('.dropdown_menu').toggleAttribute('hidden');
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
        sendAjaxRequest('post', '/api/report/', { description: description, id_post: id_post, id_comment: id_comment }, function () {
            addedHandler(null).call(this)
        });
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
            let tags = bubble_tags_array.join(' ');
            let photos = document.querySelector('#popup_show_post #post_photos').files;
            let res = confirm('Are you sure you want to profile post this?');

            let formData = new FormData();

            formData.append('text', textarea.value);
            formData.append('tags', tags);

            for (var x = 0; x < photos.length; x++) {
                formData.append("photos[]", photos[x]);
            }

            if (res && textarea != null)
                sendFormData('post', '/api/post/', formData, addedHandler('#popup_show_post'));
            textarea.value = ''
        }

        else {
            let textarea = document.querySelector('#popup_show_group_post textarea');
            let tags = bubble_tags_array.join(' ');
            let res = confirm('Are you sure you want to group post this?');
            let photos = document.querySelector('#popup_show_group_post #post_photos').files;

            let formData = new FormData();
            formData.append('text', textarea.value);
            formData.append('tags', tags);
            formData.append('group_name', textarea.dataset.group);
            for (var x = 0; x < photos.length; x++) {
                formData.append("photos[]", photos[x]);
            }

            if (res && textarea.value != null)
                sendFormData('post', '/api/post/', formData, addedHandler('#popup_show_group_post'));
            textarea.value = ''
        }

        event.preventDefault();
        giveBackBubbleTags();
    }
}


function sendEditPostRequest(event) {

    let res = confirm('Are you sure you want to edit this post?');

    let textarea = document.querySelector('#popup_show_post_edit textarea');
    let tags = bubble_tags_array.join(' ');
    let photos = document.querySelector('#popup_show_post_edit #edit_post_photos').files;
    let id = document.querySelector('#popup_show_post_edit #delete_post_button').dataset.id


    let formData = new FormData();
    formData.append('text', textarea.value);
    formData.append('tags', tags);
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
        sendAjaxRequest('delete', '/api/post/' + id, {}, function () {
            location.reload();
        });


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

            const recipientUser = document.location.pathname.substring(10);
            document.querySelector('#compact_message_text_' + recipientUser).innerHTML = message;
            document.querySelector('#compact_message_time_' + recipientUser).innerHTML = 'now';
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
                document.querySelector('.message_body').appendChild(art);

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

        const recipientUser = document.location.pathname.substring(10);
        document.querySelector('#compact_message_text_' + recipientUser).innerHTML = message;
        document.querySelector('#compact_message_time_' + recipientUser).innerHTML = 'now';
    }
}


addEventListeners();


// =================================== Home ==========================================

let offset = 0;
let scroll_end;
let scroll_updating;

function updateFeed(feed) {

    sendAjaxRequest('get', '/api/post/feed/' + feed + '/offset/' + offset, {}, function () {

        let received = JSON.parse(this.responseText);
        let timeline = document.querySelector('#timeline');
        if (!timeline) return;


        if (offset === 0) {
            timeline.innerHTML = '';
        } else {
            let spinners = timeline.querySelectorAll('.spinner');

            spinners.forEach(function (spinner) {
                spinner.remove();
            })
        }

        if (received.length === 0 && !scroll_end) {

            timeline.appendChild(createElementFromHTML(`<h3 class="text-center" style="margin-top:4em">No content to show</h3>`));
            scroll_end = true;
        }

        received.forEach(function (post) {
            timeline.appendChild(createPost(post))
        })

        timeline.innerHTML += createSpinner()

        offset += received.length;
        scroll_updating = false
    })


}

function updateFeedOnLoad() {
    let pathname = window.location.pathname
    if (pathname !== '/home') return;

    let timeline = document.querySelector('#timeline')
    if (!timeline) return;
    timeline.innerHTML = createSpinner();

    let feed_filters = document.querySelector('#feed_radio_viral')
    if (feed_filters) {
        feed_filters.checked = true
    }
    offset = 0
    scroll_end = false
    scroll_updating = false;
    updateFeed('viral')
}



function updateFeedOnClick() {
    let pathname = window.location.pathname
    if (pathname !== '/home') return;

    let filters = document.querySelectorAll('.feed-filter')

    if (!filters) return;

    filters.forEach(function (filter) {
        filter.addEventListener('click', function () {
            let timeline = document.querySelector('#timeline')
            if (!timeline) return;
            timeline.innerHTML = createSpinner();

            offset = 0
            scroll_end = false
            updateFeed(filter.value)
        })
    })

}

function updateFeedOnScroll() {
    let pathname = window.location.pathname
    if (pathname !== '/home') return;

    window.onscroll = function (ev) {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
            if (scroll_updating) return;
            scroll_updating = true;

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
updateFeedOnScroll();
updateFeedOnClick();



function createPost(post) {

    let new_post = document.createElement('article');
    new_post.classList.add('post');

    let images = '', bottom = '', like = '', dropdown = '', topics = '';

    imageControls = `
    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-Controls-${post.id}" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(100%);"></span>
    <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carousel-Controls-${post.id}" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"  style="filter: invert(100%);"></span>
        <span class="visually-hidden">Next</span>
    </button>`

    if (post.hasLiked) {
        like = '<i class="fa-solid fa-heart text-danger"></i>'
    } else {
        like = '<i class="fa-regular fa-heart"></i>'
    }

    if (post.auth !== 0) {

        if (post.isOwner) {
            dropdown = `<a class="dropdown-item" href="/post/${post.id}">See Post</a>`
        } else {
            dropdown = `
            <a class="dropdown-item" href="/messages/${post.owner}">Send Message</a>`
        }

        bottom = `

        <a class="text-decoration-none " data-uid=${post.auth} onclick="sendLikePostRequest(event)"
        data-id=${post.id} data-liked="${post.hasLiked ? '1' : '0'}" href="#!">

        <span class="me-3 text-dark" style="font-size:1.2em;">${post.likes_count}</span>
            <span style="font-size: 1.3em">
                 ${like}
            </span>
            </a>



        <a class="text-decoration-none" href="/post/${post.id}">
        <span class="mt-1 me-3 text-dark" style="font-size:1.2em">${post.comments_count}</span>
        <span style="font-size: 1.3em">
            <i class="ms-3 fa-regular fa-comment-dots"></i>
        </span>
    </a>
        `

    } else {

        bottom = `
        <a class="text-decoration-none">
            <span class="me-3 text-dark" style="font-size:1.2em;">${post.likes_count}</span>
                <span style="font-size: 1.3em">
                   ${like}
                </span>
          </a>


          <a class="text-decoration-none" href="/post/${post.id}">
          <span class="mt-1 me-3 text-dark" style="font-size:1.2em">${post.comments_count}</span>
          <span style="font-size: 1.3em">
              <i class="ms-3 fa-regular fa-comment-dots"></i>
          </span>
      </a>
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
        <div id="carousel-Controls-${post.id}" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                ${imageDiv}
            </div>
            ${post.images.length > 1 ? imageControls : ''}
        </div>

        `
    }


    if (post.topics.length > 0) {
        topics = '<div>';

        post.topics.forEach(topicItem => {
            topics += `
                <a href="/search/%23${topicItem.topic}"
                class="btn btn-primary me-2 mb-3 ms-2">#${topicItem.topic}</a>
            `
        });

        topics += '</div>'
    }


    new_post.innerHTML = `

<div class="container mt-5 mb-5 post_item" style="width:90%;">
    <div class="row d-flex align-items-center  justify-content-center ">
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


                    <a class="text-decoration-none" href="/post/${post.id}" style="color: black">
                         ${images}
                    </a>



                    <div>
                    <a class="text-decoration-none" href="/post/${post.id}" style="color: black">
                        <p class="text-justify p-3">${post.text}</p>
                    </a>

                        ${topics}

                        <div class="card-footer d-flex justify-content-evenly">

                            <!-- TODO: Aqui devia se passar a contagem da database e n o array completo -->

                            ${bottom}






                        </div>

                    </div>
                </div>
            </div>
    </div>
</div>

  `
    return new_post;
}


function createSpinner() {
    return `
    <div class="spinner text-center">
        <div class="spinner-border m-5" style="width: 5rem; height: 5rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    `
}


//  ======================================= Search ======================================

let selected_filter;

function updateSearchOnLoad() {

    let pathname = window.location.pathname
    if (!/\/search\/[#\*?!.%@_\w ]*/.test(pathname)) return;

    let timeline = document.querySelector('#timeline')
    if (!timeline) return;
    timeline.innerHTML = createSpinner();

    scroll_end = false;
    offset = 0;

    // Search if there is a query_string in the route (and add it to the search bar)
    const searchBar = document.querySelector('#search_bar')

    query_string = pathname.replaceAll('%20', ' ').replaceAll('%23', '#').split('/')[2]

    if (query_string) {
        if (query_string !== '*') searchBar.value = query_string


        updateSearch()
    }

    updateSearchOnInput()
    updateSearchOnOrderChange()
    updateSearchOnScroll()
    updateSearchOnClick()

}

function updateSearchOnInput() {
    const searchBar = document.querySelector('#search_bar')

    if (searchBar) {
        searchBar.addEventListener('keypress', function (event) {

            if (event.key !== "Enter") return;

            let timeline = document.querySelector('#timeline')
            if (!timeline) return;
            timeline.innerHTML = createSpinner();

            scroll_end = false;
            offset = 0;

            updateSearch()

            let searchBarString = searchBar.value.trim().replaceAll('#', '%23')

            // Update the path on top
            if (searchBarString !== '') {
                window.history.replaceState('', '', '/search/' + searchBarString.replaceAll(' ', '%20'))
            } else {
                window.history.replaceState('', '', '/search/*')
            }

        })
    }
}


function updateSearchOnClick() {
    const filters = document.querySelectorAll('#search_filter .type-search')

    if (filters) {
        filters.forEach(function (filter) {
            filter.addEventListener('click', function(event) {
                let timeline = document.querySelector('#timeline')
                if (!timeline) return;
                timeline.innerHTML = createSpinner();

                updateSearch()
            })
        })
    }
}


function updateSearchOnScroll() {

    selected_filter = document.querySelector('#search_filter input[checked]').value;

    window.onscroll = function (ev) {

        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 2) {

            let timeline = document.querySelector('#timeline')
            if (!timeline) return;
            
            if (selected_filter === 'posts') {
                timeline.innerHTML += createSpinner();
                updateSearch()
                timeline.querySelector('.spinner').remove()
            }
        }
    };
}

function updateSearchOnOrderChange() {

    let orders = document.querySelectorAll('.search-order')

    if (!orders) return;

    orders.forEach(function (order) {
        order.addEventListener('click', function () {
            let timeline = document.querySelector('#timeline')
            if (!timeline) return;

            timeline.innerHTML = createSpinner();

            offset = 0
            scroll_end = false
            scroll_updating = false
            updateSearch()
        })
    })

}

function updateSearch() {
    let type_search = '', query_string = '', order_search = 'default';

    // Get the type_search from the radio input
    const filters = document.querySelectorAll('#search_filter .type-search')
    if (!filters) return;

    filters.forEach(filter => {
        if (filter.checked) { type_search = filter.value }
    })

    // Get the query string from the search bar
    const searchBar = document.querySelector('#search_bar')
    if (!searchBar) return;


    // Get the order on which it should be sorted
    const orders = document.querySelectorAll('.search-order')
    if (!orders) return;

    orders.forEach(order => {
        if (order.checked) { order_search = order.value }
    })

    query_string = searchBar.value.replaceAll('#', '%23');

    if (query_string === '') {
        query_string = '*';
    }

    if (type_search !== selected_filter) {
        offset = 0;
        selected_filter = type_search;
        scroll_end = false;
    }

    console.log('/api/search/' + query_string + 
    '/type/' + type_search + 
    '/order/' + order_search +
    '/offset/' + offset)

    sendAjaxRequest('get', '/api/search/' + query_string + 
        '/type/' + type_search + 
        '/order/' + order_search +
        '/offset/' + offset
        , {}, function () {
        
        

        let timeline = document.querySelector('#timeline');
        if (!timeline) return;

        if (offset === 0) {
            timeline.innerHTML = '';
        }

        let received;
        try {
            received = JSON.parse(this.responseText);
        } catch (error) {
            console.log('Erro')
        }

        if (received == null) return;

        if (received.length === 0 && scroll_end === false) {
            timeline.innerHTML += `<h3 class="text-center mt-5">No results found</h3>`
            scroll_end = true;
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

        offset += received.length;

    })

}





function createUserCard(user) {
    let new_card = document.createElement('article');
    let privacy;

    let bio_short = user.bio;
    if (bio_short.length > 50)
        bio_short = user.bio.substring(0, 75) + '...'

    if (user.visibility === true) {
        privacy = 'Public'
    } else {
        privacy = 'Private'
    }

    new_card.innerHTML = `
    <div class="card mt-4 me-3" style="width: 15em;height:31em">
        <img height="50%" style="max-height:20em" src="/${user.photo}" class="card-img-top" alt="User Profile Image">
        <div class="card-body">
            <h5 class="card-title">${user.username}</h5>
            <p class="card-text">${bio_short}</p>

            <p class="card-text"><b>Visibility: </b>
                <span class="card-text">
                    ${privacy}
                </span>
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
        bio_short = bio_short.substring(0, 75) + '...'

    if (group.visibility === true) {
        privacy = 'Public'
    } else {
        privacy = 'Private'
    }


    new_card.innerHTML = `
    <div class="card mt-4 me-3" style="width: 15em;height:28em;justify-content:between">
        <img height="50%" style="max-height:20em" src="/${group.photo}" class="card-img-top" alt="Group Profile Image">
        <div class="card-body">
            <h5 class="card-title">${group.name}</h5>
            <p class="card-text">${bio_short}</p>

            <p class="card-text"><b>Visibility: </b>
            <span class="card-text">
                ${privacy}
            </span>

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

        if (event.key !== "Enter") return;

        this.value = this.value.trim()

        if (this.value !== '') {
            window.location.href = '/search/' + this.value.replaceAll(' ', '%20').replaceAll('#', '%23')
        } else {
            window.location.href = '/search/*';
        }

    })

}

searchRedirect();
updateSearchOnLoad();




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
        <a class="me-3" style="width:10em" href='/profile/${user.username}'>${user.username}</a>
        <a style="width:5em">${user.report_count} reports</a>
        <a href="/admin/report/${user.username}" class="btn btn-outline-dark">View</a>
    `

    return new_card;
}


function createUserReportCardPast(user) {
    let button, new_card = document.createElement('div')
    new_card.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center', 'mb-2')

    if (user.decision === 'Rejected') {
        button = `<a href="#" class=" btn btn-success" style="width:7em">REJ</a>`
    } else if (user.decision === 'Accepted') {
        let banDate = new Date(user.ban_date).getTime();
        let today = new Date().getTime();

        if (user.ban_date != null) {
            time = Math.ceil((banDate - today) / (1000 * 3600 * 24));
        } else {
            time = -1;
        }

        if (time > 0) {
            button = `<a href="#" class=" btn btn-warning" style="width:7em">${time}d</a>`
        } else {
            button = `<a href="#" class=" btn btn-info" style="width:7em">Finished</a>`
        }

    }

    new_card.innerHTML = `
        <img class="me-3 rounded-circle" src="/${user.photo}" alt="User Report Profile Image" width="50" height="50">
        <a class="me-3" style="width:10em" href='/profile/${user.username}'>${user.username}</a>
        <a class="text-muted text-decoration-none" style="width:7em">${user.decision_date}</a>
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
        return `<a href=/profile/${notf.sender.username}>${notf.sender.username}</a> wants to connect with you.`;
    }
    else if (notf.tipo == "Like") {
        if (notf.id_post != null)
            return notf.sender.username + " liked your <a href=/post/" + notf.id_post + "> Post</a>";
        else
            return notf.sender.username + " liked your comment in <a href=/post/" + notf.comment.id_post + "> Post</a>"; // TODO: temos de ir buscar o post na mesma ... mudar bd
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

        let res = confirm("You sure you want to " + response + " this request?");
        if (!res) return;

        let gname = window.location.pathname.split('/')[2];;

        let reqURI = isAFriendReq ? "/api/user/friend/request/" + id + "/" + response : '/api/group/' + gname + '/request/' + id + "/" + response;

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
            parent.lastChild.innerHTML = 'Cancel Friend Request'
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
            parent.lastChild.innerHTML = 'Cancel Request'
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
                parent.lastChild.innerHTML = 'Request to Join'
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
                parent.lastChild.innerHTML = 'Send Friend Request'
            }
            addedHandler(null).call(this);
        });
}

function deleteFriendFromFriendPage() {
    let card = this.parentNode.parentNode.parentNode

    let res = confirm("You sure you want to remove this friend?");
    if (!res) return;

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

    if (input) {
        input.addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                event.preventDefault();
                document.getElementById("comment_post_send").click();
            }
        });
    }
}


function checkResponsiveUI() {
    let width = (window.innerWidth > 0) ? window.innerWidth / 16 : screen.width / 16;

    let page_a = document.getElementById("toggle_list_A")
    let page_b = document.getElementById("toggle_list_B")
    let btn = document.getElementById("list_toggle_btn_div")
    let title = document.querySelector("h1")
    let toggle = document.getElementById("list_toggle_btn")

    if (page_a == null || page_b == null || btn == null || toggle == null)
        return;

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

// ================================== BUBBLE TAGS ==================================

function addBubbleTagBehavior(inputID, tagsID, maxSize) {

    const input = document.querySelector(inputID);
    const tagsContainer = document.querySelector(tagsID);

    if (input == null || tagsContainer == null) return;

    let taglist = tagsContainer.querySelectorAll('span')

    if (taglist.length > 0)
        taglist.forEach(e => {
            let text = e.innerText.trim();
            bubble_tags_array.push(text);
        });

    input.addEventListener('keypress', function (event) {

        if (event.key !== " ") return;
        let text = input.value.trim();
        if (text.length == 0) return;
        input.value = "";
        if (bubble_tags_array.includes(text)) return;
        bubble_tags_array.push(text);

        let tag = createElementFromHTML(`<span id="bubble_tag_item_${text}"
    class="badge bg-light me-2 p-2 mb-2 text-dark" style="font-size:1.25em">${text}
     <a href="#" onclick="removeBubbleTag('${inputID}','${text}')">
     <i class="fa-solid fa-circle-xmark ms-2 text-danger"></i></a>   </span>`);

        tagsContainer.appendChild(tag);

        if (bubble_tags_array.length >= maxSize) {
            input.value = `Max ${maxSize} tags`;
            input.disabled = true;
            return;
        }
    });
}

let bubble_tags_array = [];
function removeBubbleTag(inputID, text) {
    let input = document.querySelector(inputID);
    let tag = document.querySelector(`#bubble_tag_item_${text}`)
    tag.remove()
    bubble_tags_array = bubble_tags_array.filter(e => e != tag.innerText.trim())
    if (input.disabled) {
        input.disabled = false;
        input.value = "";
    }
}

function giveBackBubbleTags() {

    let tagCont = document.querySelector('#post_create_tags_container')
    let input = document.querySelector('#post_create_tags')
    if (tagCont && input) {
        tagCont.innerHTML = ""
        input.value = ""
        input.disabled = false
    }

    bubble_tags_array = [];
    const arr = ['#group_create_tags', '#post_edit_tags', '#group_edit_tags', '#profile_edit_tags']

    arr.forEach(e => {
        let elem = document.querySelector(e)
        if (elem) {
            let new_element = elem.cloneNode(true);
            elem.parentNode.replaceChild(new_element, elem);
        }
    })

    addBubbleTagBehavior('#group_create_tags', '#group_create_tags_container', 3)
    addBubbleTagBehavior('#post_edit_tags', '#post_edit_tags_container', 3)
    addBubbleTagBehavior('#group_edit_tags', '#group_edit_tags_container', 3)
    addBubbleTagBehavior('#profile_edit_tags', '#profile_edit_tags_container', 3)
}

addBubbleTagBehavior('#post_create_tags', '#post_create_tags_container', 3)
addBubbleTagBehavior('#group_create_tags', '#group_create_tags_container', 3)
addBubbleTagBehavior('#post_edit_tags', '#post_edit_tags_container', 3)
addBubbleTagBehavior('#group_edit_tags', '#group_edit_tags_container', 3)
addBubbleTagBehavior('#profile_edit_tags', '#profile_edit_tags_container', 3)

//setInterval(() => { console.log(bubble_tags_array) }, 500);

// ================================== END OF BUBBLE TAGS ==================================


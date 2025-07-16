@extends('layouts.app')

@section('page_title', 'Features')

@section('content')
    <div class="list-group">
        <div class="container px-2 py-3" id="hanging-icons">
            <h2 class="pb-2 border-bottom">Main Features</h2>
            <div class="row g-2 py-3 mx-2 my-2">


                <div class=" d-flex align-items-start m-2  border border-secondary p-2">
                    <div>
                        <h4 class="fs-2">Group</h4>
                        <hr>
                        <p>In <span style="font-weight: bold">Nexus</span> you have a possibility to create a public group or
                            a private and make posts in group.
                        </p>

                    </div>
                </div>

                <div class=" d-flex align-items-start  m-2 border border-secondary p-2">
                    <div>
                        <h4 class="fs-2">Message</h4>
                        <hr>
                        <p>You can send some menssage to your friends or also every single public profile.</p>

                    </div>
                </div>

                <div class=" d-flex align-items-start  m-2 border border-secondary p-2">
                    <div>
                        <h4 class="fs-2">Friends</h4>
                        <hr>
                        <p>In <span style="font-weight: bold">Nexus</span> you can send request to be your friend to have a
                            possibility to check every posts and
                            send a mensagens to your friend.That is the only way you can see a privale profile</p>

                    </div>
                </div>

                <div class=" d-flex align-items-start  m-2 border border-secondary p-2">
                    <div>
                        <h4 class="fs-2">Posts</h4>
                        <hr>
                        <p>The user of <span style="font-weight: bold">Nexus</span> can do posts in profile our in groups
                            with a photo, a simple text or both of
                            them.</p>

                    </div>
                </div>

                <div class=" d-flex align-items-start  m-2 border border-secondary p-2">
                    <div>
                        <h4 class="fs-2">Search</h4>
                        <hr>
                        <p><span style="font-weight: bold">Nexus</span> allows you to search every group,profile or a
                            topic.The user click in search bar and writes
                            what him likes to search.</p>

                    </div>
                </div>

                <div class=" d-flex align-items-start  m-2 border border-secondary p-2">
                    <div>
                        <h4 class="fs-2">Login/Logout</h4>
                        <hr>
                        <p>To have access all funcionalities you have to take account for this you have to login with you
                            email and password associated at your account</p>

                    </div>
                </div>

                <div class=" d-flex align-items-start  m-2 border border-secondary p-2">
                    <div>
                        <h4 class="fs-2">Owners</h4>
                        <hr>
                        <p>In every group you have at least one owner and the owner can delete members and delete posts of
                            the group.</p>

                    </div>
                </div>


                <div class=" d-flex align-items-start  m-2 border border-secondary p-2">
                    <div>
                        <h4 class="fs-2">Private/Public Profiles or Groups</h4>
                        <hr>
                        <p>On <span style="font-weight: bold">Nexus</span> we have visibility restrictions on both groups
                            and profiles. If the visibility is
                            private then only friends of that profile can see the content of that profile if it is a group
                            only members of the group can see posts of that group.</p>

                    </div>
                </div>
                <div class=" d-flex align-items-start  m-2 border border-secondary p-2">
                    <div>
                        <h4 class="fs-2">Register</h4>
                        <hr>
                        <p>To be a new member of the <span style="font-weight: bold">Nexus</span> family, you must first
                            register with your name, email, password
                            and date of birth.</p>

                    </div>
                </div>

                <div class=" d-flex align-items-start  m-2 border border-secondary p-2">
                    <div>
                        <h4 class="fs-2">Likes/comments</h4>
                        <hr>
                        <p><span style="font-weight: bold">Nexus</span> allows you to like or comment on posts from your
                            friends or groups. If the profile of the
                            person posting the post is public or the group is public, you can also like or comment on these
                            posts</p>

                    </div>
                </div>

                <div class=" d-flex align-items-start  m-2 border border-secondary p-2">
                    <div>
                        <h4 class="fs-2">Video-Call</h4>
                        <hr>
                        <p><span style="font-weight: bold">Nexus</span> allows you to do Video-call  with your friends</p>

                    </div>
                </div>


            </div>
        </div>

        <a href="{{ route('login') }}" class="w-100 btn btn-primary mb-5">Join Nexus network</a>


    </div>
@endsection

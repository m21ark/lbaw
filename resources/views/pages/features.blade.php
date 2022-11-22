@extends('layouts.app')

@section('content')
    <div class="list-group">
        <div class="container px-4 py-5" id="hanging-icons">
            <h2 class="pb-2 border-bottom">Main Features</h2>
            <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">

                
                    <div class="col d-flex align-items-start">
                        <div>
                            <h3 class="fs-2">Group</h3>
                            <p>In nexus you have a possibility to create a public group or a private and make posts in group.</p>
                            <a href="#" class="btn btn-primary">
                                Group
                            </a>
                        </div>
                    </div>

                    <div class="col d-flex align-items-start">
                        <div>
                            <h3 class="fs-2">Message</h3>
                            <p>You can send some menssage to your friends or also every single public profile.</p>
                            <a href="#" class="btn btn-primary">
                                Message
                            </a>
                        </div>
                    </div>
                    
                    <div class="col d-flex align-items-start">
                        <div>
                            <h3 class="fs-2">Friends</h3>
                            <p>In nexus you can send request to be your friend to have a possibility to check every posts and send a mensagens to your friend.That is the only way you can see a privale profile</p>
                            <a href="#" class="btn btn-primary">
                               Friends
                            </a>
                        </div>
                    </div>

                    <div class="col d-flex align-items-start">
                        <div>
                            <h3 class="fs-2">Posts</h3>
                            <p>The user of nexus can do posts in profile our in groups with a photo, a simple text or both of them.</p>
                            <a href="#" class="btn btn-primary">
                                Posts
                            </a>
                        </div>
                    </div>

                    <div class="col d-flex align-items-start">
                        <div>
                            <h3 class="fs-2">Search</h3>
                            <p>Nexus allows you to search every group,profile or a topic.The user click in search bar and writes what him likes to search.</p>
                            <a href="#" class="btn btn-primary">
                                Search
                            </a>
                        </div>
                    </div>

                    <div class="col d-flex align-items-start">
                        <div>
                            <h3 class="fs-2">Login/Logout</h3>
                            <p>To have access all funcionalities you have to take account for this you have to login with you email and password associated at your account</p>
                            <a href="#" class="btn btn-primary">
                                Login/Logout
                            </a>
                        </div>
                    </div>

                    <div class="col d-flex align-items-start">
                        <div>
                            <h3 class="fs-2">Owners</h3>
                            <p>In every group you have at least one owner and the owner can delete members and delete posts of the group.</p>
                            <a href="#" class="btn btn-primary">
                               Owners
                            </a>
                        </div>
                    </div>

                    
                    <div class="col d-flex align-items-start">
                        <div>
                            <h3 class="fs-2">Private/Public Profiles or Groups</h3>
                            <p>On nexus we have visibility restrictions on both groups and profiles. If the visibility is private then only friends of that profile can see the content of that profile if it is a group only members of the group can see posts of that group.</p>
                            <a href="#" class="btn btn-primary">
                               Profile/Groups
                            </a>
                        </div>
                    </div>
                    <div class="col d-flex align-items-start">
                        <div>
                            <h3 class="fs-2">Register</h3>
                            <p>To be a new member of the nexus family, you must first register with your name, email, password and date of birth.</p>
                            <a href="#" class="btn btn-primary">
                               Register
                            </a>
                        </div>
                    </div>

                    <div class="col d-flex align-items-start">
                        <div>
                            <h3 class="fs-2">Likes/comments</h3>
                            <p>Nexus allows you to like or comment on posts from your friends or groups. If the profile of the person posting the post is public or the group is public, you can also like or comment on these posts</p>
                            <a href="#" class="btn btn-primary">
                               Likes/comments
                            </a>
                        </div>
                    </div>


            </div>
        </div>


    </div>
@endsection

@if (Auth::check())
    <div class="pop_up" hidden id="popup_show_profile_edit">
        <form style="width:100%;" method='post' action={{ route('editProfile', $user->username) }}
            enctype="multipart/form-data">

            <!-- Start popup body -->
            {{ csrf_field() }}
            <div class="d-flex justify-content-between align-items-top">
                <h3 class="h3 mb-3 font-weight-normal">Edit Profile</h3>
                <a href="#" class="close_popup_btn" style="font-size: 2.5em">
                    <i class="fa-solid fa-rectangle-xmark text-danger"></i>
                </a>
            </div>

            <label for="user_name" class="" data-toggle="tooltip" data-placement="top"
                title="The username should be unique and serve as an identifier for each user. We don't recommend you change it very often.">Username</label>
            <input type="text" id="user_name" value="{{ $user->username }}" data-name="{{ $user->username }}"
                data-id="{{ $user->id }}" class="form-control mb-3" placeholder="Username">

            <label for="user_email" class=" mt-2">Email address</label>
            <input type="email" id="user_email" class="form-control mb-3" value="{{ $user->email }}"
                placeholder="Email" required>

            <label for="user_bdate" class=" mt-2" data-toggle="tooltip" data-placement="top"
                title="Your birthdate shouldn't be changed unless an error ocurred.">Birthdate</label>
            <input type="date" id="user_bdate" class="form-control mb-3" value="{{ $user->birthdate }}" required>

            <label for="user_bio" class="">Bio</label>
            <textarea rows="4" id="user_bio" class="form-control mb-3" placeholder="Bio" style="resize: none;">{{ $user->bio }}</textarea>

            <?php
            $topics = [];
            for ($i = 0; $i < sizeof($user->interests); $i++) {
                array_push($topics, $user->interests[$i]->topic->topic);
            }
            $topics = implode(' ', $topics);
            ?>

            <label for="profile_edit_tags" class="" data-toggle="tooltip" data-placement="top"
                title="You can have up to 3 topics of interest to share your passions with the world. Your interests can be seen by everyone on your profile">Topics
                of Interest</label>
            <input type="text" id="profile_edit_tags" class="form-control mb-3" placeholder="Space separeted tags"
                name="tags" value="{{ $topics }}">

            <label for="profile_pic" class="" data-toggle="tooltip" data-placement="top"
                title="Your profile picture can be publicly seen by every user.">Profile Picture</label>
            <input type="file" class="form-control" id="profile_pic" name="photo" />

            <div class="mt-3">
                <label for="profile_visibility" class="me-3" data-toggle="tooltip" data-placement="top"
                    title="Visibility influences who can view your content. If unchecked, the profile is considered private and it's posts can only be seen by members and you can only recieve messages from friends. Otherwise, the content is available to everyone. Even if the profile is marked as private, it can still be discovered in search and receive new friend requests.">Profile
                    Public Visibility</label>
                <input class="form-check-input" id="profile_visibility" type="checkbox" role="switch"
                    id="flexSwitchCheckChecked" @if ($user->visibility) checked @endif>
            </div>

            <button class="btn btn-lg btn-primary mt-4 w-100" id="edit_profile_button" type="submit">Save
                Changes</button>

            <a href="/resetAuthPassword" class="w-100 btn btn-outline-dark mt-4 p-2">Change Password</a>

            <button class="btn btn-lg btn-outline-danger mt-4 w-100" id="delete_profile_button" type="submit">Delete
                Account</button>
            <!-- End popup body -->

        </form>
    </div>
@endif

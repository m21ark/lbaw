<div id="timeline">

    <h1>Posts</h1>

    @if ($friends || $user->visibility || (Auth::check() && $user->id === Auth::user()->id))
        @if ($user->posts->isEmpty())
            <h2 class="text-center mt-5">No posts yet</h2>
        @endif
        @foreach ($user->posts as $post)
            @include('partials.post_item', ['post' => $post])
        @endforeach
    @else
        <h2 class="mb-5"> <i class="fa-solid fa-lock fa-2x me-4 mt-5"></i>This account is private</h2>
        <p style="font-size: 1.3em">Make a friend request to access this profile content</p>
        <img src="/NotFriends.jpg" class="img-fluid mt-4" alt="You are not friends. Please make a friends request">
    @endif
</div>

<!-- Edit Profile Popup -->
@include('partials.popup.edit_profile_popup', ['user' => $user])

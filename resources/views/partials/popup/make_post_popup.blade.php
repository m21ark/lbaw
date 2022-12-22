@if (Auth::check())
    <div class="pop_up" hidden id="{{ $popup_id }}">
        <form style="width:100%;">
            {{ csrf_field() }}


            <!-- Start popup body -->
            <div class="d-flex justify-content-between align-items-top">
                @if ($popup_id === 'popup_show_post')
                    <h3 class="h3 mb-3 font-weight-normal">Make a profile post</h3>
                @else
                    <h3 class="h3 mb-3 font-weight-normal">Make a group post</h3>
                @endif

                <a href="#" class="close_popup_btn" style="font-size: 2.5em">
                    <i class="fa-solid fa-rectangle-xmark text-danger"></i>
                </a>
            </div>


            <label for="inputText" class="">Text</label>
            <textarea rows="8" id="inputText" class="form-control mb-3" placeholder="Content" name="text" required
                autofocus style="resize: none;" @if ($popup_id === 'popup_show_group_post') data-group='{{ $group_name }}' @endif></textarea>

            <label for="post_create_tags" class="">Hashtags</label>
            <input type="text" id="post_create_tags" class="form-control mb-3" placeholder="Space separeted tags"
                name="tags">

            <label for="post_photos" class="">Photos</label>
            <input type="file" class="form-control" id="post_photos" name="photos" multiple>

            <button
                @if ($popup_id == 'popup_show_post') id={{ 'profile_post_button_action' }}
            @else
            id={{ 'group_post_button_action' }} @endif
                class="btn btn-lg btn-primary mt-3 w-100" type="submit">Post</button>
            <!-- End popup body -->
        </form>
    </div>
@endif

@if (Auth::check())
    <div class="pop_up" hidden id="popup_show_post_edit">
        <form style="width:100%;" method='post' action={{ route('editPost', $post->id) }} enctype="multipart/form-data">
            {{ csrf_field() }}

            <!-- Start popup body -->
            <div class="d-flex justify-content-between align-items-top">
                <h3 class="h3 mb-3 font-weight-normal">Edit Post</h3>
                <a href="#" class="close_popup_btn" style="font-size: 2.5em">
                    <i class="fa-solid fa-rectangle-xmark text-danger"></i>
                </a>
            </div>


            <label for="post_text" class="">Description</label>
            <textarea rows="8" id="post_text" class="form-control mb-3" placeholder="Post Text" style="resize: none;">{{ $post->text }}</textarea>

            <?php
            $topics = [];
            for ($i = 0; $i < sizeof($post->topics); $i++) {
                array_push($topics, $post->topics[$i]->topic->topic);
            }
            $topics = implode(' ', $topics);
            ?>

            <label for="post_edit_tags" class="">Hashtags</label>
            <input type="text" id="post_edit_tags" class="form-control mb-3" placeholder="Space separeted tags"
                name="tags" value="{{ $topics }}">

            <label for="edit_post_photos" class="">Photos</label>
            <input type="file" class="form-control" id="edit_post_photos" name="photos" multiple>

            <button class="btn btn-lg btn-primary mt-4 w-100" id="edit_post_button" type="submit">Save
                <!--  -->
                Changes
            </button>


        </form>

        <button class="btn btn-lg btn-outline-danger mt-4 w-100" id="delete_post_button"
            data-id="{{ $post->id }}">Delete
            Post</button>
    </div>
@endif

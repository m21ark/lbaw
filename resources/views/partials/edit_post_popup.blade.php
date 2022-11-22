@if (Auth::check())
    <div class="pop_up" hidden id="popup_show_post_edit">
        <form style="width:100%;" method='put' action={{ route('editPost', $post->id) }} enctype="multipart/form-data">


            <!-- Start popup body -->
            <div class="d-flex justify-content-between align-items-top">
                <h3 class="h3 mb-3 font-weight-normal">Edit Post</h3>
                <a href="#" class="btn btn-danger close_popup_btn"><strong>X</strong></a>
            </div>


            <label for="post_text" class="sr-only">Description</label>
            <textarea rows="8" id="post_text" class="form-control mb-3" placeholder="Post Text" style="resize: none;">{{ $post->text }}</textarea>

            <label for="group_photo" class="sr-only">Images</label>
            <input type="file" class="form-control" id="group_photo" name="photo" />

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

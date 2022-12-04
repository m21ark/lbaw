@if (Auth::check())
    <div class="pop_up" hidden id="popup_show_comment_edit">

        <div class="d-flex justify-content-between align-items-top">
            <h3 class="h3 mb-3 font-weight-normal">Edit Comment</h3>
            <a href="#" class="btn btn-danger close_popup_btn"><strong>X</strong></a>
        </div>

        <label for="comment_text" class="sr-only">Text</label>
        <textarea rows="8" id="comment_text_edit" class="form-control mb-3" placeholder="Comment Text"
            style="resize: none;">TEXT TO ADD</textarea>

        <button class="btn btn-lg btn-primary mt-4 w-100" id="edit_comment_button" data-id="0">Save Changes</button>

        <button class="btn btn-lg btn-outline-danger mt-4 w-100" id="delete_comment_button" data-id="0">Delete
            Comment</button>

    </div>
@endif

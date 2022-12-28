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
            ?>

            <label for="post_edit_tags" class="">Tags</label>
            <input type="text" id="post_edit_tags" class="form-control mb-3" name="tags"
                @if (sizeof($topics) == 3) disabled value="Max of 3 tags" @else placeholder="Add up to 3 post tags" @endif>

            <div id="post_edit_tags_container" class="mb-2">
                <!-- TAGS INSERTED HERE WITH JS -->
                @foreach ($topics as $topic)
                    <span id="bubble_tag_item_{{ $topic }}" class="badge bg-light me-2 p-2 mb-2 text-dark"
                        style="font-size:1.25em">{{ $topic }}
                        <a href="#" onclick="removeBubbleTag('#post_edit_tags','{{ $topic }}')">
                            <i class="fa-solid fa-circle-xmark ms-2 text-danger"></i>
                        </a>
                    </span>
                @endforeach
            </div>


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

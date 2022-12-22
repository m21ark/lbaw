@if (Auth::check())
    <div class="pop_up" hidden id="popup_show_group_edit">
        <form style="width:100%;" method='post' action={{ route('editGroup', $group->name) }}
            enctype="multipart/form-data">

            <!-- Start popup body -->
            <div class="d-flex justify-content-between align-items-top">
                <h3 class="h3 mb-3 font-weight-normal">Edit Group</h3>
                <a href="#" class="close_popup_btn" style="font-size: 2.5em">
                    <i class="fa-solid fa-rectangle-xmark text-danger"></i>
                </a>
            </div>

            <label for="group_name" class="">Group Name</label>
            <input type="text" id="group_name" value="{{ $group->name }}" class="form-control mb-3"
                placeholder="Group Name">

            <label for="group_description" class="">Description</label>
            <textarea rows="8" id="group_description" class="form-control mb-3" placeholder="Group description"
                data-name="{{ $group->name }}" data-id="{{ $group->id }}" style="resize: none;">{{ $group->description }}</textarea>

            <?php
            $topics = [];
            for ($i = 0; $i < sizeof($group->topics); $i++) {
                array_push($topics, $group->topics[$i]->topic->topic);
            }
            $topics = implode(' ', $topics);
            ?>

            <label for="group_edit_tags" class="">Topics</label>
            <input type="text" id="group_edit_tags" class="form-control mb-3" placeholder="Space separeted tags"
                name="tags" value="{{ $topics }}">

            <label for="group_photo" class="">Profile Picture</label>
            <input type="file" class="form-control" id="group_photo" name="photo">

            <label for="group_visibility" class="">Group Public Visibility</label>
            <input class="form-check-input" id="group_visibility" type="checkbox" role="switch"
                id="flexSwitchCheckChecked" @if ($group->visibility) checked @endif>

            <button class="btn btn-lg btn-primary mt-4 w-100" id="edit_group_button" type="submit">Save
                Changes</button>

            <button class="btn btn-lg btn-outline-danger mt-4 w-100" id="delete_group_button" type="submit">Delete
                Group</button>
            <!-- End popup body -->

        </form>
    </div>
@endif

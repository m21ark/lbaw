@if (Auth::check())
    <div class="pop_up" hidden id="popup_show_group_edit">
        <form style="width:100%;" method='post' action={{ route('editGroup', $group->name) }} enctype="multipart/form-data">

            <!-- Start popup body -->
            <div class="d-flex justify-content-between align-items-top">
                <h3 class="h3 mb-3 font-weight-normal">Edit Group</h3>
                <a href="#" class="btn btn-danger close_popup_btn"><strong>X</strong></a>
            </div>

            <label for="group_name" class="sr-only">Group Name</label>
            <input type="text" id="group_name" value="{{ $group->name }}" class="form-control mb-3"
                placeholder="Group Name">

            <label for="group_description" class="sr-only">Description</label>
            <textarea rows="8" id="group_description" class="form-control mb-3" placeholder="Group description"
                data-name="{{ $group->name }}" data-id="{{ $group->id }}" style="resize: none;">{{ $group->description }}</textarea>

            <label for="group_photo" class="sr-only">Profile Picture</label>
            <input type="file" class="form-control" id="group_photo" name="photo">

            <label for="group_visibility" class="sr-only">Group Public Visibility</label>
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

@if (Auth::check())
    <div class="pop_up" hidden id="popup_show_group_create">
        <form style="width:100%;">

            <!-- Start popup body -->
            <div class="d-flex justify-content-between align-items-top">
                <h3 class="h3 mb-3 font-weight-normal">Create your Group</h3>
                <a href="#" class="close_popup_btn" style="font-size: 2.5em">
                    <i class="fa-solid fa-rectangle-xmark text-danger"></i>
                </a>
            </div>


            <label for="group_name" class="" data-toggle="tooltip" data-placement="top"
                title="The group name should be unique and serve as an identifier for each group. We don't recommend you change it very often">Group
                Name</label>
            <input type="text" id="group_name" class="form-control mb-3" placeholder="Group Name">

            <label for="group_description" class="">Description</label>
            <textarea rows="8" id="group_description" class="form-control mb-3" placeholder="Group description"
                style="resize: none;"></textarea>

            <label for="group_create_tags" class="" data-toggle="tooltip" data-placement="top"
                title="You can have up to 3 topics of interest to share what the group is about. This topics are visible in the group page">Topics</label>
            <input type="text" id="group_create_tags" class="form-control mb-3" placeholder="Space separeted tags"
                name="tags">

            <label for="group_visibility" class=" me-3" data-toggle="tooltip" data-placement="top"
                title="Visibility influences who can view your content. If unchecked, the group is considered private and it's posts can only be seen by members. Otherwise, the content is available to everyone. Even if the group is marked as private, it can still be discovered in search and receive new join requests">Group
                Public Visibility</label>
            <input class="form-check-input" id="group_visibility" type="checkbox" role="switch"
                id="flexSwitchCheckChecked" checked>


            <button class="btn btn-lg btn-primary mt-4 w-100" id="create_group_button" type="submit">Create
                Group</button>
            <!-- End popup body -->

        </form>
    </div>
@endif

@if (Auth::check())
    <div class="pop_up" hidden id="popup_show_group_create">
        <form style="width:100%;">

            <!-- Start popup body -->
            <div class="d-flex justify-content-between align-items-top">
                <h3 class="h3 mb-3 font-weight-normal">Create your Group</h3>
                <a href="#" class="btn btn-danger close_popup_btn"><strong>X</strong></a>
            </div>


            <label for="group_name" class="">Group Name</label>
            <input type="text" id="group_name" class="form-control mb-3" placeholder="Group Name">

            <label for="group_description" class="">Description</label>
            <textarea rows="8" id="group_description" class="form-control mb-3" placeholder="Group description"
                style="resize: none;"></textarea>

            <label for="group_visibility" class=" me-3">Group Public Visibility</label>
            <input class="form-check-input" id="group_visibility" type="checkbox" role="switch"
                id="flexSwitchCheckChecked" checked>


            <button class="btn btn-lg btn-primary mt-4 w-100" id="create_group_button" type="submit">Create
                Group</button>
            <!-- End popup body -->

        </form>
    </div>
@endif

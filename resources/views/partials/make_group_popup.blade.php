@if (Auth::check())
    <div class="post_body make_group pop_up" hidden="">
        <form>
            <label>
                <div class="form-group">

                    <label for="group_name">Group Name</label>
                    <input  type="text" id="group_name">

                    <label for="group_description">Group Description</label>
                    <textarea class="form-control" id="group_description" rows="3"></textarea>

                    <!-- FALTA A FOTO DO GRUPO -->
                </div>
            </label>
            <button class="form_button" type="submit">
                Make Group
            </button>
        </form>
    </div>

    <!-- TODO add images  -->
@endif

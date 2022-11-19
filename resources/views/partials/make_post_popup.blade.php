@if (Auth::check())
    <div class="pop_up" hidden id="{{ $popup_id }}">
        <form style="width:100%;">
            {{ csrf_field() }}


            <!-- Start popup body -->
            <div class="d-flex justify-content-between align-items-top">
                <h3 class="h3 mb-3 font-weight-normal">Make a post</h3>
                <a href="#" class="btn btn-danger">X</a>
            </div>


            <label for="inputText" class="sr-only">Text</label>
            <textarea rows="8" id="inputText" value="{{ old('email') }}" class="form-control mb-3" placeholder="Content"
                name="text" required autofocus style="resize: none;"></textarea>

            <button id="post_button_action" class="btn btn-lg btn-primary mt-3 w-100" type="submit">Post</button>
            <!-- End popup body -->


        </form>
    </div>
@endif

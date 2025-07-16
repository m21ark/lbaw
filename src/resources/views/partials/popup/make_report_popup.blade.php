@if (Auth::check())
    <div class="pop_up" hidden id="popup_show_report_create">
        <form style="width:100%;">


            <!-- Start popup body -->
            <div class="d-flex justify-content-between align-items-top">
                <h3 class="h3 mb-3 font-weight-normal">Make Report</h3>
                <a href="#" class="close_popup_btn" style="font-size: 2.5em">
                    <i class="fa-solid fa-rectangle-xmark text-danger"></i>
                </a>
            </div>

            <label for="report_description" class="" data-toggle="tooltip" data-placement="top"
            title="Describe in the best way you can why the content is being reported.">Motive</label>
            <textarea rows="8" id="report_description" class="form-control mb-3"
                placeholder="Indicate your motive for this report" style="resize: none;"></textarea>


            <button class="btn btn-lg btn-primary mt-4 w-100" id="create_report_button" data-post="{{ $post->id }}"
                data-comment="0" type="submit">Submit report</button>
            <!-- End popup body -->

        </form>
    </div>
@endif

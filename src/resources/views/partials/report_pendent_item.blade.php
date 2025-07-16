<div class="card p-3 mb-4" id="reports_list_item_{{ $report->id }}">
    <h5 class="mb-4">Report description:</h5>
    <p>{{ $report->description }}</p>
    <a href="#!" class="position-absolute btn btn-outline-danger reject_user_report_btn" style="width: 20%;right:2em"
        data-reportid="{{ $report->id }}">Reject</a>


    <p><b>Made by: </b> <a class="text-decoration-none"
            href="/profile/{{ $report->reporter->username }}">{{ $report->reporter->username }}</a> on
        {{ $report->report_date }}</p>


    <h5 class="mt-3">Reported content:</h5>

    @if ($report->id_post === null)
        @include('partials.comment_item', ['comment' => $report->comment])
    @else
        @include('partials.post_item', ['post' => $report->post])
    @endif

</div>

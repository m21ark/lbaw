<div class="card p-3 mb-4">
    <h5 class="mb-4">Report description:</h5>
    <p>{{ $report->description }}</p>


    <p><b>Made by: </b> <a class="text-decoration-none"
            href="/profile/{{ $report->reporter->username }}">{{ $report->reporter->username }}</a> on
        {{ $report->report_date }}</p>

    <h5>Decision information:</h5>
    <ul>
        <li>Decision: {{ $report->decision }}</li>
        <li>Decision date: {{ $report->decision_date ?? 'N/A' }}</li>
        <li>Decision made by: {{ $report->avaliator->username ?? 'N/A' }}</li>
    </ul>

    <h5 class="mt-3">Reported content:</h5>

    @if ($report->id_post === null)
        @include('partials.comment_item', ['comment' => $report->comment])
    @else
        @include('partials.post_item', ['post' => $report->post])
    @endif

</div>

<div class="pop_up reveal-modal" id="video_call">
    <div class="d-flex justify-content-between mb-2">
        <h3>Video Call</h3>
        <h3 id="endCall" onclick="endCall()" data-toggle="tooltip" data-placement="left" title="End current call">
            <span class="p-1 text-danger">
                <i class="fa-solid fa-phone-slash"></i>
            </span>End Call
        </h3>
    </div>

    <video id="remoteview" controls autoplay></video>
    <video id="selfview" class="position-absolute bottom-0 end-0 mb-2"
        style="width: 30%;transform: translate(-5%, -2%);" controls autoplay muted></video>
</div>

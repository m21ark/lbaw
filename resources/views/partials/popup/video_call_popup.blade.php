<div class="pop_up reveal-modal" id="video_call" style="position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);width: 50%; display:none;" >
    <video id="remoteview" style="width: 100%;left-margin:1em;" controls autoplay></video>
    <video id="selfview" class="position-absolute bottom-0 end-0" style="width: 30%;" controls autoplay></video>
    <span id="endCall" class="position-absolute bottom-0 start-0 fa-2x" style="color:red;" onclick="endCall()">
        <i class="fa-solid fa-phone-slash"></i>
    </span>
</div>
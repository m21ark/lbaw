<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        @foreach ( $post->images as $image)
            <div class="carousel-item active">
                <img class="d-block w-100" src="/{{$image->path}}" alt="Primeiro Slide">
            </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev" style="filter: invert(100%);">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next" style="filter: invert(100%);">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </a>
</div>

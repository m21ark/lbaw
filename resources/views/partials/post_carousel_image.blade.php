<div id="carouselExampleControls" class="carousel slide" data-interval="false">
    <div class="carousel-inner">
        @for ($i = 0; $i < count($post->images); $i++)
            <div class="carousel-item <?php if ($i == 0) {
                echo 'active';
            } ?>">
                <img class="d-block" src="/{{ $post->images[$i]->path }}" alt="Post Content Image">
            </div>
        @endfor
    </div>

    @if (count($post->images) > 1)
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev"
            style="filter: invert(100%);">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next"
            style="filter: invert(100%);">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </a>
    @endif
</div>

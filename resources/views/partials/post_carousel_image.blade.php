<div id="carousel-Controls-{{$post->id }}" class="carousel slide" data-interval="false">
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
        <button class="carousel-control-prev" type="button" data-bs-target="#carousel-Controls-{{$post->id }}" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(100%);"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carousel-Controls-{{$post->id }}" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"  style="filter: invert(100%);"></span>
        <span class="visually-hidden">Next</span>
      </button>
    @endif
    
</div>

<!--Carousel Wrapper-->
<div id="carousel-{{ $article->id }}" class="carousel slide carousel-fade mt-2" data-ride="carousel" data-interval="false">
  <ol class="carousel-indicators">
    @foreach($article->photos as $photo)
      @if($loop->first)
        <li data-target="#carousel-{{ $article->id }}" data-slide-to="{{$loop->index}}" class="active"></li>
      @else
        <li data-target="#carousel-{{ $article->id }}" data-slide-to="{{$loop->index}}"></li>
      @endif
    @endforeach
  </ol>
  <div class="carousel-inner" role="listbox">
    @foreach($article->photos as $photo)
    @if ($loop->first)
      <div class="carousel-item active">
    @else
      <div class="carousel-item">
    @endif
        <img class="d-block w-100" src="{{ $photo->storage_key }}">
      </div>
    @endforeach
  </div>
  <a class="carousel-control-prev" href="#carousel-{{ $article->id }}" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true">
      <i class="fas fa-chevron-left text-black-50 fa-3x"></i>
    </span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carousel-{{ $article->id }}" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true">
      <i class="fas fa-chevron-right text-black-50 fa-3x"></i>
    </span>
    <span class="sr-only">Next</span>
  </a>
</div> 
<!--/.Carousel Wrapper-->
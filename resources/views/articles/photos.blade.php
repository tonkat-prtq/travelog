<!--Carousel Wrapper-->
<div id="carousel-example-1z" class="carousel slide carousel-fade" data-ride="carousel" data-interval="false">
  <ol class="carousel-indicators">
    @foreach($article->photos as $photo)
      @if($loop->first)
        <li data-target="#carousel-example-1z" data-slide-to="{{$loop->index}}" class="active"></li>
      @else
        <li data-target="#carousel-example-1z" data-slide-to="{{$loop->index}}"></li>
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
  <a class="carousel-control-prev" href="#carousel-example-1z" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carousel-example-1z" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div> 

@section('addCSS')
  <style>
    .carousel-indicators li{
      background-color: rgba(76, 175, 80, 0.5);
    }

    .carousel-item img {
      width: 100%;
      height: 500px;
      object-fit: contain;
    }

  .carousel-control-next-icon, .carousel-control-prev-icon {
    background-color: rgba(76, 175, 80, 0.2);
    border-radius: 10px;
  }
  </style>
@endsection
<!--/.Carousel Wrapper-->
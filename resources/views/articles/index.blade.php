@extends('app')

@section('addJS')
  <script>
  window.onload = function(){
    $("#carousel-example-1z").carousel('pause');
  };
  </script>
@endsection

@section('title', '記事一覧')
@section('content')
  @include('nav')
  <div class="container">
    @foreach($articles as $article)
      @include('articles.card')
    @endforeach

    <div class="pagetop">
      <p class="mt-2 float-right"><a href="#top"><i class="fas fa-arrow-circle-up fa-4x text-first-color ml-auto"></i></a></p>
    </div>
  </div>
@section('addJS')
<script type="text/javascript">
    $(function(){
        var amount = 200; //スクロール量（px）
        $('.pagetop').hide();
        $(window).scroll(function(){
            var scrollPoint = $(this).scrollTop();
            (scrollPoint > amount)?$('.pagetop').fadeIn():(scrollPoint < amount)?$('.pagetop').fadeOut():$('.pagetop').show();
        });
    });
</script>
@endsection

@section('addCSS')
  <style>

    .container {
      position: relative;
    }

    .pagetop{
      position:fixed;
      bottom:5px;
      right:5px;
    }

  </style>
@endsection
  </div>
@endsection

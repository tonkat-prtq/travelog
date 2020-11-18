@extends('app')

@section('title', '記事一覧')

@include('nav')

@section('content')
  <div class="container index">
    @foreach($articles as $article)
      @include('articles.card')
    @endforeach

    <div class="pagetop">
      <p class="mt-2 float-right"><a href="#top"><i class="fas fa-arrow-circle-up fa-3x text-first-color ml-auto"></i></a></p>
    </div>

    <!-- ページネーション -->
    <div class="article-paginator mt-2">
    {{ $articles->links() }}
    </div>
  </div>


  @section('addCSS')
  <style>

  </style>
  @endsection

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

@endsection

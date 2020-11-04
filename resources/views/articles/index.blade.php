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
  </div>
@endsection

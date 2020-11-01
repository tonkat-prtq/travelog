@extends('app')

@section('search', '検索結果')

@section('content')
  @include('nav')
  <div class="container">
    @isset($articles)
      @foreach($articles as $article)
        @include('articles.card')
      @endforeach
    @endisset
  </div>
@endsection

@extends('errors.layouts.base')

@section('title', '404 Not Found')

@section('message', 'ページが見つかりませんでした。')

@section('detail', 'URLのタイプミス、もしくはページが移動または削除された可能性があります。 トップページに戻るか、もう一度検索してください。')

@section('link')
  <p class="text-center">
    <a class="text-second-color" href="{{env('APP_URL')}}">トップページへ戻る</a>
  </p>
@endsection

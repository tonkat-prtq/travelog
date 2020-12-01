test@extends('errors.layouts.base')

@section('title', 'Filesize Over')

@section('message', 'アップロードしようとしている画像ファイルの合計サイズが大きすぎます')

@section('detail', 'アップロードする画像のサイズを減らして、再投稿してください')

@section('link')
  <p class="text-center">
    <a class="text-second-color" href="{{env('APP_URL')}}">トップページへ戻る</a>
  </p>
@endsection

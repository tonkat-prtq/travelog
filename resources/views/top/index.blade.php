@extends('app')

@section('title', 'Travelog')

@include('nav')

@section('content')
<div class="jumbotron jumbotron-fluid bg-white">
  <div class="container">
    <h1 class="display-4">Travelog</h1>
    <p class="lead">旅の記録を、写真で振り返る。</p>
  </div>

  <div class="container">
    <a href="https://github.com/tonkat-prtq/travelog" class="text-muted text-decoration-none">
    Github
      <i class="fab fa-github fa-lg text-muted"></i>
    </a>
    </br>
    <a href="https://www.tech-commit.jp/3xDgzQ1lxQL8T2OS/portfolios" class="text-second-color">詳しい学習記録はこちら</a>
  </div>
  <!-- /.container -->
</div>

<!-- /.jumbotron -->

<div class="container-1 bg-second-color pb-3 pt-5">
  <!-- ブラウザのwidthで画像を変える -->
  <picture class="demo-image img-fluid">
    <source media="(min-width:768px)" srcset="{{ asset('images/lp-carousel-lg.gif') }}" class="img-fluid">
    <img src="{{ asset('images/lp-carousel-sm.gif') }}" class="img-fluid">
  </picture>
  <p class="mt-3 h4 font-weight-light text-nice-white-color">写真と一緒に、旅の思い出を記録しよう</p>
</div>

<div class="container-2 pb-3 pt-3">
  <p class="font-weight-light h4 text-black mb-2">
    編集時、画像のサムネイルを表示＋削除ボタンの実装
  </p>
  <picture class="carousel-gif img-fluid">
    <source media="(min-width:768px)" srcset="{{ asset('images/lp-edit-images-lg-only.gif') }}" class="img-fluid">
    <img src="{{ asset('images/lp-edit-images-lg-only.gif') }}" class="img-fluid">
  </picture>
</div>

<div class="container-3 pb-3 pt-4 bg-second-color">
    <ul class="font-weight-light list-unstyled h4 text-nice-white-color">
      <li>レスポンシブ対応</li>
      <li class="mt-2">AWS Certificate Maneger + Route 53 + EC2ロードバランサーで、HTTPS化</li>
      <li class="mt-2">Googleログイン実装</li>
    </ul>
</div>

<!-- /.container -->

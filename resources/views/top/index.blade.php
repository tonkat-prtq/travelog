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

<div class="container-1">
  <!-- ブラウザのwidthで画像を変える -->
  <picture class="demo-image img-fluid">
    <source media="(min-width:768px)" srcset="{{ asset('images/lp-indexpage-lg.png') }}" class="img-fluid">
    <img src="{{ asset('images/lp-indexpage-sm.png') }}" class="img-fluid">
  </picture>
  <p class="mt-3">写真をメインとし、邪魔しないスッキリとしたデザイン</p>
</div>

<div class="photo-container">
</div>
<!-- /.container -->

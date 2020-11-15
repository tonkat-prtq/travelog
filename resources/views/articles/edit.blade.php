@extends('app')
@section('title', '記事更新')
@include('nav')
@section('content')
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card mt-3">
          <div class="card-body pt-0">
            @include('error_card_list')
            <div class="card-text">
              <form method="POST" action="{{ route('articles.update', ['article' => $article]) }}" enctype="multipart/form-data">
                @method('PATCH')
                @include('articles.form')
                <button type="submit" class="btn-second-color blue-gradient btn-block pb-2 pt-2">更新する</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
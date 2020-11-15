@extends('app')

@section('title', 'ユーザー登録')

@include('nav')

@include('error_card_list')

@section('content')
  <div class="container">
    <div class="row">
      <div class="mx-auto col col-12 col-sm-11 col-md-9 col-lg-7 col-xl-6">
        <div class="card mt-3">
          <div class="card-body text-center">
            <h2 class="h3 card-title text-center mt-2">ユーザー登録</h2>
            <a href="{{ route('login.{provider}', ['provider' => 'google']) }}" class="btn btn-block btn-danger">
              <i class="fab fa-google mr-1"></i>Googleで登録
            </a>


            <div class="card-text">
              <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group mt-4">
                  <label for="name">ユーザー名</label>
                  <input class="form-control" type="text" id="name" name="name" placeholder="NapoleonSolo" required value="{{ old('name') }}">
                  <small>英数字4〜16文字(登録後の変更はできません)</small>
                </div>
                <div class="form-group mt-2">
                  <label for="email">メールアドレス</label>
                  <input class="form-control" type="text" id="email" name="email" placeholder="example@com" required value="{{ old('email') }}" >
                </div>
                <div class="form-group">
                  <label for="password">パスワード</label>
                  <input class="form-control" type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                  <label for="password_confirmation">パスワード(確認)</label>
                  <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" required>
                </div>
                <button class="btn-second-color btn-block mt-4 mb-2 pb-1 pt-1" type="submit">ユーザー登録</button>
              </form>

              <div class="mt-0">
                <a href="{{ route('login') }}" class="btn-block text-decoration-none btn-first-color pb-1 pt-1">ログインはこちら</a>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

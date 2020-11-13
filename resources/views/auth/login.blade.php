@extends('app')

@section('title', 'ログイン')

@include('nav')

@include('error_card_list')

<link rel="stylesheet" href="{{ asset('css/card.css') }}">

@section('content')
  <div class="container">
    <div class="row">
      <div class="mx-auto col col-12 col-sm-11 col-md-9 col-lg-7 col-xl-6">
        <div class="card mt-3">
          <div class="card-body text-center">
            <h2 class="h3 card-title text-center mt-2">ログイン</h2>
            <a href="{{ route('login.{provider}', ['provider' => 'google']) }}" class="btn btn-block btn-danger">
              <i class="fab fa-google mr-1"></i>Googleでログイン
            </a>


            <div class="card-text">
              <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group mt-4">
                  <label for="email">メールアドレス</label>
                  <input class="form-control" type="text" id="email" name="email" placeholder="example@com" required value="{{ old('email') }}">
                </div>

                <div class="form-group">
                  <label for="password">パスワード</label>
                  <input class="form-control" type="password" id="password" name="password" required>
                </div>

                <input type="hidden" name="remember" id="remember" value="on">

                <button class="btn-first-color btn-block mt-4 mb-2 shadow-none pb-1 pt-1" type="submit">ログイン</button>

              </form>

              <div class="mt-0">
                <a href="{{ route('register') }}" class="card-text btn-block btn-second-color text-decoration-none mt-1 mb-1 pt-1 pb-1">ユーザー登録はこちら</a>
              </div>

              <div class="small mt-2">
                <a href="{{ route('password.request') }}" class="card-text">パスワードを忘れた方</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

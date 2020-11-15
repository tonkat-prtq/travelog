<!-- navbar -->
<nav class="navbar navbar-expand-md bg-second-color">
  <!-- navbar brand -->
  <a class="navbar-brand text-white text-decoration-none mr-5" href="/"><i class="fas fa-camera-retro mr-1"></i>Travelog</a>
  <!-- toggle button -->
  <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#Navber" aria-controls="Navber" aria-expanded="false" aria-label="レスポンシブ・ナビゲーションバー">
    <span class="navbar-toggler-icon text-white fas fa-bars fa-lg"></span>
  </button>

  <!-- navbarの、折りたたまれて非表示になるアイテム -->
  <div class="collapse navbar-collapse" id="Navber">

    <!-- navbarのアイテムたち -->
    <ul class="navbar-nav ml-auto">

      <!-- 検索フォーム -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <form action = "/search" method="post" class="form-inline mb-0">
            @csrf
            <input class="form-control" type="text" name='input' value="{{$input ?? ''}}" placeholder="Search" aria-label="Search">
          </form>
        </li>
      </ul> 

      <!-- 未ログイン時 -->
      @guest
      <li class="nav-item">
        <a class="nav-link text-white text-decoration-none" href="{{ route('register') }}">ユーザー登録</a>
      </li>
      @endguest

      @guest
      <li class="nav-item">
        <a class="nav-link text-white text-decoration-none" href="{{ route('login') }}">ログイン</a>
      </li>
      @endguest 

      <!-- ログイン時 -->
      @auth 
      <li class="nav-item">
        <a class="nav-link text-white text-decoration-none" href="{{ route('articles.create') }}"><i class="fas fa-pen mr-1"></i>投稿する</a>
      </li>
      @endauth
      
      @auth 
      <!-- Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white text-decoration-none" id="navbarDropdownMenuLink" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle text-white text-decoration-none"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <button class="dropdown-item text-decoration-none mypage-button" type="button"
                  onclick="location.href='{{ route("users.show", ["name" => Auth::user()->name]) }}'">
            マイページ
          </button>
          <div class="dropdown-divider"></div>
          <button form="logout-button" class="dropdown-item logout-button" type="submit">
            ログアウト
          </button>
        </div>
      </li>
      <form id="logout-button" method="POST" action="{{ route('logout') }}">
        @csrf 
      </form>
      <!-- Dropdown -->
      @endauth
    </ul>
  </div>
</nav>

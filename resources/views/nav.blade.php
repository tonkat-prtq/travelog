<nav class="navbar navbar-expand bg-second-color justify-content-center">

  <a class="navbar-brand ml-auto mr-auto text-white text-decoration-none" href="/"><i class="fas fa-camera-retro mr-1"></i>Travelog</a>

  <div class="navbar-nav ml-auto mr-auto vertical-align-center">
    <li class="nav-item">
      <form action = "/search" method="post" class="form-inline mb-0">
        @csrf
        <input class="form-control" type="text" name='input' value="{{$input ?? ''}}" placeholder="Search" aria-label="Search">
      </form>
    </li>
  </div>

  <ul class="navbar-nav ml-auto mr-auto">

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

</nav>

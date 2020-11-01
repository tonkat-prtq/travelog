<nav class="navbar navbar-expand navbar-dark blue-gradient justify-content-center">

  <a class="navbar-brand ml-auto mr-auto" href="/"><i class="far fa-sticky-note mr-1"></i>Memo</a>

  <ul class="navbar-nav ml-auto mr-auto">
    <li class="nav-item">
      <form class="form-inline">
        <input class="form-control" type="text" placeholder="Search" aria-label="Search">
      </form>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto mr-auto">

    @guest 
    <li class="nav-item">
      <a class="nav-link" href="{{ route('register') }}">ユーザー登録</a>
    </li>
    @endguest 

    @guest 
    <li class="nav-item">
      <a class="nav-link" href="{{ route('login') }}">ログイン</a>
    </li>
    @endguest 
      
    @auth 
    <li class="nav-item">
      <a class="nav-link" href="{{ route('articles.create') }}"><i class="fas fa-pen mr-1"></i>投稿する</a>
    </li>
    @endauth
    
    @auth 
    <!-- Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-user-circle"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
        <button class="dropdown-item" type="button"
                onclick="location.href='{{ route("users.show", ["name" => Auth::user()->name]) }}'">
          マイページ
        </button>
        <div class="dropdown-divider"></div>
        <button form="logout-button" class="dropdown-item" type="submit">
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

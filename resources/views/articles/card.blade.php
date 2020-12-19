<!-- card setting -->
<div class="card mt-3">
  <!-- card header -->
  <div class="card-header d-flex flex-row">
    <a href="{{ route('users.show', ['name' => $article->user->name]) }}" class="text-divider-color">
    <i class="fas fa-user-circle fa-lg mr-2 mt-1"></i>
    </a>
    <div>
      <div class="card-header-user">
        <a href="{{ route('users.show', ['name' => $article->user->name]) }}">
          {{ $article->user->name }}
        </a>
      </div>
    </div>
    <div class="ml-auto">
    @if( Auth::id() === $article->user_id )
    <!-- dropdown -->
      <div class="ml-auto card-text">
        <div class="dropdown">
          <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v text-black-50"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route("articles.edit", ['article' => $article]) }}">
              <i class="fas fa-pen mr-1"></i>記事を更新する
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" data-toggle="modal" data-target="#modal-delete-{{ $article->id }}">
              <i class="fas fa-trash-alt mr-1"></i>記事を削除する
            </a>
          </div>
        </div>
      </div>
      <!-- dropdown -->

      <!-- modal -->
      <div id="modal-delete-{{ $article->id }}" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('articles.destroy', ['article' => $article]) }}">
              @csrf
              @method('DELETE')
              <div class="modal-body">
                {{ $article->title }}を削除します。よろしいですか？
              </div>
              <div class="modal-footer justify-content-between">
                <a class="btn btn-grey" data-dismiss="modal">キャンセル</a>
                <button type="submit" class="btn btn-danger">削除する</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- modal -->
      @endif
    </div>
  </div>
  <!-- card header -->

  <!-- 画像表示専用のビューを作って呼び出している -->

  <!-- photo carousel -->
  @if (($article->photos->count() > 0))
    @include('articles.photos')
  @endif
  <!-- photo carousel -->

  <!-- card title -->
  <div class="card-body pt-0 mt-1">
    <h4 class="h4 card-title text-center">
      <a class="text-dark article-title" href="{{ route('articles.show', ['article' => $article]) }}">
        {{ $article->title }}
      </a>
      <span class="travel-date">{{ $article->start_date->format('Y-m-d') }}
      @if ($article->end_date->format('Y-m-d') !== $article->start_date->format('Y-m-d'))
        ~ {{ $article->end_date->format('Y-m-d') }}
      @endif
      </span>
    </h4>
    <div class="card-text">
      {!! nl2br(e( $article->content ))  !!}
    </div>
  </div>
  <!-- card title -->

  <!-- card footer -->
  <div class="card-body pt-0 pb-2 pl-3 d-flex flex-row">
    <!-- いいねボタン -->
    <div class="card-text">
      <article-like
        :initial-is-liked-by='@json($article->isLikedBy(Auth::user()))'
        :initial-count-likes='@json($article->count_likes)'
        :authorized='@json(Auth::check())'
        endpoint="{{ route('articles.like', ['article' => $article ]) }}"
      >
      </article-like>
    </div>
    <!-- いいねボタン -->

    <!-- 投稿日時 -->
    <div class="card-text font-weight-lighter ml-auto small">{{ $article->created_at->diffForHumans() }}</div>
  </div>
  <!-- card footer -->

  <!-- Tag -->
  @foreach($article->tags as $tag)
    @if($loop->first)
      <div class="card-body pt-0 pb-4 pl-3">
        <div class="card-text line-height">
    @endif
      <a href="{{ route('tags.show', ['name' => $tag->name]) }}" class="border p-1 mr-1 mt-1 text-muted">
        {{ $tag->hashtag }}
      </a>
    @if($loop->last)
        </div>
      </div>
    @endif
  @endforeach
  <!-- Tag -->
  
</div>
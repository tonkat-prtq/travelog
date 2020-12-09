@csrf
<div class="form-group">
  <label class="form-title  mt-3">タイトル</label>
  <input type="text" name="title" class="form-control" placeholder="タイトル" required value="{{ $article->title ?? old('title') }}">
</div>
<div class="form-group mt-2">
  <article-tags-input
    :initial-tags='@json($tagNames ?? [])'
    :autocomplete-items='@json($allTagNames ?? [])'
  >
  </article-tags-input>
</div>
<div class="row mb-2">
  <div class="col">
    <div class="form-label">
    開始日
    </div>
    <div class="md-form">
      <input type="date" name="start_date" class="form-control" required value="{{ $article->start_date ?? old('start_date') }}">
    </div>
  </div>
  <div class="col">
    <div class="form-label">
    終了日
    </div>
    <div class="md-form">
      <input type="date" name="end_date" class="form-control" required value="{{ $article->end_date ?? old('end_date') }}">
    </div>
  </div>
</div>
<div class="form-group">
  画像ファイル（複数可, 1ファイル2MBまで)
  <div class="custom-file">
    <input type="file" class="custom-file-input" id="inputFile" name="files[][photo]" multiple>
    <label class="custom-file-label" for="inputFile">ファイルを選択 ( jpg, png, bmp )</label>
  </div>
</div>
@if (!empty($article) && $article->photos)
  @include('articles.photos_edit')
@endif
<div class="form-group">
  <label></label>
  <textarea name="content" required class="form-control" rows="16" placeholder="本文">{{ $article->content ?? old('content') }}</textarea>
</div>

@csrf
<div class="md-form">
  <label>タイトル</label>
  <input type="text" name="title" class="form-control" required value="{{ $article->title ?? old('title') }}">
</div>
<div class="form-label">
開始日
</div>
<div class="md-form">
  <input type="date" name="start_date" class="form-control" required value="{{ $article->start_date ?? old('start_date') }}">
</div>
<div class="form-label">
終了日
</div>
<div class="md-form">
  <input type="date" name="end_date" class="form-control" required value="{{ $article->end_date ?? old('end_date') }}">
</div>
<div class="form-file">
    <label for="photo">画像ファイル（複数可）:</label>
    <input type="file" class="form-control" name="files[][photo]" multiple>
</div>
<div class="form-group">
  <label></label>
  <textarea name="content" required class="form-control" rows="16" placeholder="本文">{{ $article->content ?? old('content') }}</textarea>
</div>

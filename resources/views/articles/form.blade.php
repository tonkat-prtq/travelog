@csrf
<div class="md-form">
  <label>タイトル</label>
  <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
</div>
<div class="form-label">
開始日
</div>
<div class="md-form">
  <input type="date" name="start_date" class="form-control" required value="{{ old('start_date') }}">
</div>
<div class="form-label">
終了日
</div>
<div class="md-form">
  <input type="date" name="end_date" class="form-control" required value="{{ old('end_date') }}">
</div>
<div class="form-group">
  <label></label>
  <textarea name="content" required class="form-control" rows="16" placeholder="本文">{{ old('content') }}</textarea>
</div>

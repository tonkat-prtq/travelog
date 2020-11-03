<div class="photos">
  @foreach($article->photos as $photo)
  <img src="{{ $photo->storage_key }}" class="img-fluid" alt="Responsive Image"> 
  @endforeach
</div>
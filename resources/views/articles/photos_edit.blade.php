<div class="stored-photo">
  {{-- 記事に紐付いている写真の数だけforeachで回し、変数$photoに格納 --}}
  @foreach($article->photos as $photo)
    {{-- $photoを表示 --}}
    <img src="{{asset("storage/{$photo->storage_key}") }}" id="photo-thumbnail-{{$photo->id}}" class="img-thumbnail" alt="Responsive Image" style="width: 10rem"> 
    {{-- 更新ボタンを押したときに、紐付いていた写真の情報をhidden_fieldに持たせる --}}
    <input type="hidden" id="photo-{{$photo->id}}" class="uploadedphoto" name="stored_photo_ids[]" value="{{$photo->id}}">
    {{-- 削除ボタンにdata-delete-idを持たせることで、JavaScript側に削除したい写真のidを渡せる --}}
    <input type="button" value="削除" class="btn-delete" id="btn-{{$photo->id}}" data-delete-id="{{$photo->id}}">
  @endforeach
  <script type="module">
    // .btn-deleteクラスを持つボタンがクリックされたとき
    $('.btn-delete').on('click', function() {
      // deletePhotoIdという定数に、data-delete-idの値をセット
      const deletePhotoId = $(this).attr('data-delete-id');
      // サムネイルを削除
      $('#' + "photo-thumbnail-" + deletePhotoId).remove();
      // 紐付いていた写真のhidden_fieldを削除
      $('#' + "photo-" + deletePhotoId).remove();
      // 押した削除ボタンを削除
      $('#' + "btn-" + deletePhotoId).remove();
    });
  </script>
</div>
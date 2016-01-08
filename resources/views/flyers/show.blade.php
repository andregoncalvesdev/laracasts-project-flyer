@extends('layout')

@section('csslibs')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/dropzone.css">
@stop

@section('content')
  <div class="cenas">
    <div class="row">
      <div class="col-md-4">
        <h1>{{ $flyer->street }}</h1>
        <h2>{{ $flyer->price }}</h2>

        <hr>

        <div class="description">
          {!! nl2br($flyer->description) !!}
        </div>
      </div>


      <div class="col-md-8 gallery">
          @foreach($flyer->photos->chunk(3) as $set)
            <div class="row">
              @foreach ($set as $photo)
                <img src="/{{ $photo->thumbnail_path }}" alt="" class="thumbnail gallery_image">
              @endforeach
            </div>
          @endforeach
      </div>
    </div>
  </div>

  <h2>Add Your Photos</h2>

  <form
    action="{{ route('store_photo_path', [$flyer->zip, $flyer->street] )}}"
    method="POST"
    id="addPhotosForm"
    class="dropzone">
    {{ csrf_field() }}
  </form>
@stop

@section('scripts.footer')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/dropzone.js"></script>
  <script>
    Dropzone.options.addPhotosForm = {
      paramName: 'photo',
      maxFileSize: 3,
      acceptedFiles: '.jpg, .jpeg, .png, .bmp'
    };
  </script>
@stop

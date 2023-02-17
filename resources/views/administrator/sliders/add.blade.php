@extends('administrator.layouts.master')

@include('administrator.'.$prefixView.'.header')

@section('css')

@endsection

@section('content')

    <div class="container-fluid list-products">
        <div class="row">

            <form action="{{route('administrator.'.$prefixView.'.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="col-md-12">

                    <div class="form-group">
                        <label>Link</label>
                        <input type="text" name="link" class="form-control @error('link') is-invalid @enderror"
                               value="{{old('link')}}" required>
                        @error('link')
                        <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>

                    @if($isSingleImage)
                        <div class="mt-3 mb-3">
                            @include('administrator.components.upload_image', ['post_api' => $imagePostUrl, 'table' => $table, 'image' => $imagePathSingple , 'relate_id' => $relateImageTableId])
                        </div>
                    @endif

                    @if($isMultipleImages)
                        <div class="mt-3 mb-3">
                            @include('administrator.components.upload_multiple_images', ['post_api' => $imageMultiplePostUrl, 'delete_api' => $imageMultipleDeleteUrl , 'sort_api' => $imageMultipleSortUrl, 'table' => $table , 'images' => $imagesPath,'relate_id' => $relateImageTableId])
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary mt-3">Lưu</button>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('js')

@endsection

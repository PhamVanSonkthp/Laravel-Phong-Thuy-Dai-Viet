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

                    <div class="form-group mt-3">
                        <label>Tiêu đề</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{old('title')}}" required>
                        @error('title')
                        <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group mt-3">

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

                        <div class="form-group mt-3">
                            <label>Nhập nội dung</label>
                            <textarea style="min-height: 400px;" name="contents"
                                      class="form-control tinymce_editor_init @error('contents') is-invalid @enderror"
                                      rows="8">{{old('contents')}}</textarea>
                            @error('contents')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Thêm mới</button>
                    </div>
                </div>
            </form>

        </div>

    </div>

@endsection

@section('js')


@endsection


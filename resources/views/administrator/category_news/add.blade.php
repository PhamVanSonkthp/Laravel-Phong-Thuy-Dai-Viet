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

                    @include('administrator.components.require_input_text' , ['name' => 'name' , 'label' => 'Tên'])

                    @include('administrator.components.select_category' , ['lable' => 'Tin hiển thị ở App','name' => 'category_type_id' ,'html_category' => \App\Models\NewType::getCategory(isset($item) ? optional($item)->new_type_id : ''), 'isDefaultFirst'=>true])

                    @include('administrator.components.input_number' , ['name' => 'index' , 'label' => 'Thứ tự hiển thị (sắp xếp từ nhỏ tới lớn)'])

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

                        @include('administrator.components.button_save')
                    </div>
                </div>
            </form>

        </div>

    </div>

@endsection

@section('js')


@endsection


@extends('administrator.layouts.master')

@include('administrator.'.$prefixView.'.header')

@section('css')

@endsection

@section('content')

    <form action="{{route('administrator.'.$prefixView.'.update', ['id'=> $item->id]) }}" method="post"
          enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="col-md-12">

            @include('administrator.components.require_input_text' , ['name' => 'name' , 'label' => 'Tên'])

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

            @include('administrator.components.require_input_text' , ['name' => 'short_description' , 'label' => 'Mô tả ngắn'])

            @include('administrator.components.require_textarea_description', ['name' => 'description' , 'label' => 'Mô tả'])

            @include('administrator.components.select_category' , ['name' => 'category_id' ,'html_category' => \App\Models\Category::getCategory(isset($item) ? optional($item)->category_id : ''), 'can_create' => true])

            @include('administrator.components.require_input_number' , ['name' => 'price_import' , 'label' => 'Giá nhập'])

            @include('administrator.components.require_input_number' , ['name' => 'price_client' , 'label' => 'Giá bán lẻ'])

            @include('administrator.components.require_input_number' , ['name' => 'price_agent' , 'label' => 'Giá bán buôn (đại lý)'])

            @include('administrator.components.require_input_number' , ['name' => 'price_partner' , 'label' => 'Giá CTV (Cộng tác viên)'])

            @include('administrator.components.require_input_number' , ['name' => 'inventory' , 'label' => 'Tồn kho'])

            @include('administrator.components.button_save')

        </div>
    </form>

@endsection

@section('js')

@endsection

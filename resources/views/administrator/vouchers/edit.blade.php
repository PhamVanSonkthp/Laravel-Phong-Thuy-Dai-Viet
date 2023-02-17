@extends('administrator.layouts.master')

@include('administrator.'.$prefixView.'.header')

@section('css')

@endsection

@section('content')

    <div class="container-fluid list-products">
        <div class="row">

            <form action="{{route('administrator.'.$prefixView.'.update', ['id'=> $item->id]) }}" method="post"
                  enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="col-md-12">
                    @include('administrator.components.require_input_text' , ['name' => 'name' , 'label' => 'Tên'])

                    @include('administrator.components.input_text' , ['name' => 'code' , 'label' => 'Mã voucher'])

                    @include('administrator.components.require_input_datetime' , ['name' => 'begin' , 'label' => 'Thời gian bắt đầu'])

                    @include('administrator.components.require_input_datetime' , ['name' => 'end' , 'label' => 'Thời gian kết thúc'])

                    <div class="form-group mt-3">
                        <label>Loại giảm giá | Mức giảm @include('administrator.components.lable_require') </label>

                        <div class="row">
                            <div class="col-6">
                                <select name="type_discount" class="form-control select2_init">
                                    <option value="1">Theo số tiền</option>
                                    <option value="2">Theo phần trăm</option>
                                </select>
                            </div>

                            <div class="col-6">

                                @include('administrator.components.input_number' , ['name' => 'discount_amount' , 'placeholder' => 'Giảm theo số tiền', 'no_margin' => true])

                                @include('administrator.components.input_number' , ['name' => 'discount_percent' , 'placeholder' => 'Giảm theo %', 'no_margin' => true, 'hidden' => true])

                                @include('administrator.components.input_number' , ['name' => 'max_discount_percent_amount' , 'placeholder' => 'Giảm tối đa', 'hidden' => true])

                            </div>
                        </div>

                    </div>

                    @include('administrator.components.require_input_number' , ['name' => 'min_amount' , 'label' => 'Giá trị đơn hàng tối thiểu'])

                    @include('administrator.components.require_input_number' , ['name' => 'max_use_by_time' , 'label' => 'Tổng lượt sử dụng tối đa'])

                    @include('administrator.components.require_input_number' , ['name' => 'max_use_by_user' , 'label' => 'Lượt sử dụng tối đa/Người mua'])

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
            </form>

        </div>
    </div>
@endsection

@section('js')
    <script>
        $('select[name="type_discount"]').on('change', function() {
            const val = $(this).val()

            if (val == 1){
                $('input[name="discount_amount"]').show()
                $('input[name="discount_percent"]').hide()
                $('input[name="max_discount_percent_amount"]').hide()
            }else{
                $('input[name="discount_amount"]').hide()
                $('input[name="discount_percent"]').show()
                $('input[name="max_discount_percent_amount"]').show()
            }
        })

        @if(isset($item->discount_percent) && !empty($item->discount_percent))
            $('select[name="type_discount"]').val("2").trigger("change")
        @endif

    </script>
@endsection

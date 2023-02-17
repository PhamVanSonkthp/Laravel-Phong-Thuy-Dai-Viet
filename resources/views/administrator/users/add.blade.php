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

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group mt-3">
                                <label>Tên khách hàng<span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{old('name')}}" required>
                                @error('name')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mt-3">
                                <label>Số điện thoại<span class="text-danger">*</span></label>
                                <input type="text" name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{old('phone')}}" required>
                                @error('phone')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mt-3">
                                <label>Email<span class="text-danger">*</span></label>
                                <input type="text" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{old('email')}}" required>
                                @error('email')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mt-3">
                                <label>Mật khẩu<span class="text-danger">*</span></label>
                                <input type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       value="{{old('password')}}" required>
                                @error('password')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
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

                    <div class="mt-3">
                        <label>Lọại khách hàng</label>
                        <select name="user_type_id" class="form-control select2_init">
                            @foreach($userTypes as $userTypeItem)
                                <option value="{{$userTypeItem->id}}" {{request('user_type_id') == $userTypeItem->id ? 'selected' : ''}}>{{$userTypeItem->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    @include('administrator.components.require_select2' , ['name' => 'user_type_id' , 'label' => 'Mô tả ngắn', 'select2Items' => $userTypes])

                    @include('administrator.components.button_save')

                </div>
            </form>


        </div>

    </div>

@endsection

@section('js')


@endsection


@extends('administrator.layouts.master')

@include('administrator.'.$prefixView.'.header')

@section('css')


@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">

            <form action="{{route('administrator.'.$prefixView.'.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="col-md-12">

                    <input type="text" name="is_admin" style="display: none;" value="1">

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group mt-3">
                                <label>Tên nhân viên<span class="text-danger">*</span></label>
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

                        <div class="col-md-3">
                            <div class="form-group mt-3">
                                <label>Chọn vai trò</label>
                                <select name="role_ids[]" class="form-control select2_init" multiple>
                                    <option value=""></option>
                                    @foreach($roles as $roleItem)
                                        <option value="{{$roleItem->id}}">{{$roleItem->name}}</option>
                                    @endforeach
                                </select>
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

                    <button type="submit" class="btn btn-primary mt-3">Thêm mới</button>

                </div>
            </form>


        </div>

    </div>

@endsection

@section('js')


@endsection


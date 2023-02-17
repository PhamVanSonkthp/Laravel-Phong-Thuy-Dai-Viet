@extends('administrator.layouts.master')

@include('administrator.'.$prefixView.'.header')

@section('css')

@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">

            <form action="{{route('administrator.'.$prefixView.'.update', ['id'=> $item->id]) }}" method="post"
                  enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="col-md-12">

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group mt-3">
                                <label>Tên khách hàng<span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{$item->name}}" required>
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
                                       value="{{$item->phone}}" required>
                                @error('phone')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mt-3">
                                <label>Email<span class="text-danger">*</span></label>
                                <input type="text" name="email" autocomplete="off"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{$item->email}}" required readonly>
                                @error('email')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mt-3">
                                <label>Mật khẩu<span class="text-danger">*</span></label>
                                <input type="text" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       value="">
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
                                        <option
                                            {{$rolesOfUser->contains($roleItem->id) ? 'selected' : ''}}
                                            value="{{$roleItem->id}}">{{$roleItem->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="mt-3">
                        @include('administrator.components.upload_image', ['post_api' => route('ajax,administrator.upload_image.store'), 'relate_id' => $item->id , 'table' => 'users', 'image' => $item->avatar("original")])
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Lưu</button>

                </div>
            </form>

        </div>
    </div>
@endsection

@section('js')

@endsection

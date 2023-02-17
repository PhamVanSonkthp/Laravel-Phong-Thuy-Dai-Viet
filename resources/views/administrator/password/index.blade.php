@extends('administrator.layouts.master')

@include('administrator.password.header')

@section('css')

@endsection

@section('content')

    <div class="container-fluid list-products">
        <div class="row">
            <!-- Individual column searching (text inputs) Starts-->

            @if (Session::has('error'))
                <div class="text-danger mb-4" style="font-weight: bold;">
                    {{ Session::get('error') }}
                </div>
            @endif

            @if (Session::has('success'))
                <div class="text-success mb-4" style="font-weight: bold;">
                    {{ Session::get('success') }}
                </div>
            @endif

            <form action="{{route('administrator.password.update') }}" method="post"
                  enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="col-md-6">

                    <div class="form-group">
                        <label>Mật khẩu cũ</label>
                        <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror" required>
                        @error('old_password')
                        <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group mt-3">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                        @error('new_password')
                        <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group mt-3">
                        <label>Xác nhận mật khẩu</label>
                        <input type="password" name="new_password_confirm" class="form-control @error('new_password_confirm') is-invalid @enderror" required>
                        @error('new_password_confirm')
                        <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Lưu thay đổi</button>

                </div>
            </form>

        </div>
    </div>

@endsection

@section('js')

@endsection

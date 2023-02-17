@extends('administrator.layouts.master')

@include('administrator.'.$prefixView.'.header')

@section('css')

@endsection

@section('content')

    <div class="container-fluid list-products">
        <div class="row">
            <!-- Individual column searching (text inputs) Starts-->

            <form action="{{route('administrator.'.$prefixView.'.update', ['id'=> $item->id]) }}" method="post"
                  enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="col-md-6">

                    <div class="form-group">
                        <label>Số ngày dùng thử</label>
                        <input type="text" name="number_trail" class="form-control @error('number_trail') is-invalid @enderror"
                               value="{{$item->number_trail}}" required>
                        @error('number_trail')
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

@extends('administrator.layouts.master')

@include('administrator.permission.header')

@section('css')

@endsection

@section('content')

    <form action="{{route('administrator.permissions.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Chọn phân module</label>
            <select class="form-control select2_init @error('module_parent') is-invalid @enderror" name="module_parent">
                <option value="">Chọn tên module</option>
                @foreach(config('permissions.table_module') as $module_item)
                    <option value="{{$module_item}}">{{$module_item}}</option>
                @endforeach
            </select>
            @error('module_parent')
            <div class="alert alert-danger">{{$message}}</div>
            @enderror
        </div>

        <div class="form-group mt-3">
            <div class="row">
                @foreach(config('permissions.module_children') as $module_item_children)
                    <div class="col-md-3">
                        <label>
                            <input name="module_children[]" type="checkbox" value="{{$module_item_children}}" checked>
                            {{$module_item_children}}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>

@endsection


@section('js')
    <script src="{{asset('vendor/select2/select2.min.js') }}"></script>
    <script src="{{asset('admins/products/add/add.js') }}"></script>
@endsection

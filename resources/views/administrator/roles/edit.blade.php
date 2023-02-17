@extends('administrator.layouts.master')

@include('administrator.'.$prefixView.'.header')

@section('css')

@endsection

@section('content')
    <div class="container-fluid list-products">
        <div class="row">
            <form action="{{route('administrator.'.$prefixView.'.update', ['id'=> $role->id]) }}" method="post"
                  enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="col-md-6">

                    <div class="form-group">
                        <label>Tên @include('administrator.components.lable_require')</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               placeholder="Nhập tên" value="{{$role->name}}">
                        @error('name')
                        <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group mt-3">
                        <label>Mô tả @include('administrator.components.lable_require')</label>
                        <input type="text" name="display_name"
                               class="form-control @error('display_name') is-invalid @enderror"
                               placeholder="Nhập mô tả" value="{{$role->display_name}}">
                        @error('display_name')
                        <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mt-3">

                        @foreach($premissionsParent as $premissionsParentItem)
                            <div class="card border-primary mb-3 col-md-12">
                                <div class="card-header">
                                    <input id="parent-{{$premissionsParentItem->id}}" class="checkbox_wrapper" type="checkbox" value="">
                                    <label for="parent-{{$premissionsParentItem->id}}">
                                        Quyền: {{$premissionsParentItem->display_name}}
                                    </label>

                                </div>
                                <div class="row">
                                    @foreach($premissionsParentItem->permissionsChildren as $permissionsChildrenItem)
                                        <div class="card-body text-primary col-md-3">
                                            <h5 class="card-title">
                                                <input id="{{$permissionsChildrenItem->id}}" class="checkbox_children" {{$permissionsChecked->contains('id',$permissionsChildrenItem->id) ? 'checked' : ''}} type="checkbox" name="permission_id[]" value="{{$permissionsChildrenItem->id}}">
                                                <label for="{{$permissionsChildrenItem->id}}">
                                                    {{ str_replace("delete","Xóa",str_replace("edit","Chỉnh sửa",str_replace("add","Thêm mới",str_replace("list",'Xem',$permissionsChildrenItem->name)))) }}
                                                </label>
                                            </h5>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')

    <script>
        $('.checkbox_wrapper').on('click', function () {
            $(this).parents('.card').find('.checkbox_children').prop('checked', $(this).prop('checked'))
        })
    </script>
@endsection

@extends('administrator.layouts.master')

@include('administrator.'.$prefixView.'.header')

@section('css')

@endsection

@section('content')

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <!-- Individual column searching (text inputs) Starts-->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header">
                        @include('administrator.'.$prefixView.'.search')
                    </div>

                    <div class="card-body">

                        @include('administrator.components.checkbox_delete_table')

                        <div class="table-responsive product-table">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th><input id="check_box_delete_all" type="checkbox" class="checkbox-parent" onclick="onSelectCheckboxDeleteItem()"></th>
                                    <th>#</th>
                                    <th>Avatar</th>
                                    <th>Tên KH</th>
                                    <th>Số điện thoại</th>
                                    <th>Email</th>
                                    <th>Ngày sinh</th>
                                    <th>Giới tính</th>
                                    <th>Loại</th>
                                    <th>Ngày sử dụng</th>
                                    <th>Hành động</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($items as $item)
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="checkbox-delete-item" value="{{$item->id}}">
                                        </td>
                                        <td>{{$item->id}}</td>
                                        <td>
                                            <img class="rounded-circle" src="{{$item->avatar()}}" alt="">
                                        </td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->phone}}</td>
                                        <td>{{$item->email}}</td>
                                        <td>{{\App\Models\Formatter::getOnlyDate($item->date_of_birth)}}</td>
                                        <td>{{ optional($item->gender)->name}}</td>
                                        <td>{{ optional($item->userType)->name}}</td>
                                        <td>{{\App\Models\Formatter::getDateTime($item->created_at)}}</td>
                                        <td>
                                            <a href="{{route('administrator.'.$prefixView.'.edit' , ['id'=> $item->id])}}"
                                               class="btn btn-outline-secondary btn-sm edit"><i class="fa-solid fa-pen"></i></a>

                                            <a href="{{route('administrator.'.$prefixView.'.delete' , ['id'=> $item->id])}}"
                                               data-url="{{route('administrator.'.$prefixView.'.delete' , ['id'=> $item->id])}}"
                                               class="btn btn-outline-danger btn-sm action_delete">
                                                <i class="fa-solid fa-x"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div>
                            @include('administrator.components.footer_table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Individual column searching (text inputs) Ends-->
    <!-- Container-fluid Ends-->

@endsection

@section('js')

@endsection

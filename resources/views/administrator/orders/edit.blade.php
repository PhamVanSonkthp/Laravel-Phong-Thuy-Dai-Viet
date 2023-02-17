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

                    @include('administrator.components.require_input_text' , ['name' => 'search' , 'label' => 'Sản phẩm'])

                    <div class="card">
                        <div class="table-responsive product-table">
                            <table class="table table-hover ">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th></th>
                                    <th>Số lượng</th>
                                    <th>Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>11</td>
                                    <td>
                                        <div>
                                            <div class="row">
                                                <div class="col-2">
                                                    <img class="rounded-circle" src="https://bizweb.dktcdn.net/100/244/861/products/chuong-gio-lpcg1003-jpg.jpg?v=1519783750000" alt="">
                                                </div>
                                                <div class="col-10">
                                                    <div>
                                                        Chuông gió phong thủy mã đáo thành công
                                                    </div>
                                                    <div>
                                                        Phân loại:
                                                        <strong>Default Title</strong>,
                                                        <strong></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" autocomplete="off" name="name" class="form-control number" value="1" required="">
                                    </td>
                                    <td>
                                        <a href="http://localhost:8000/administrator/orders/delete/77" data-url="http://localhost:8000/administrator/orders/delete/77" class="btn btn-outline-danger btn-sm delete action_delete" title="" data-bs-original-title="Delete">
                                            <i class="fa-solid fa-x"></i>
                                        </a>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    @include('administrator.components.button_save')

                </div>
            </form>

        </div>
    </div>
@endsection

@section('js')

@endsection

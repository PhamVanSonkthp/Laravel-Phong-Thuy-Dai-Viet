@extends('administrator.layouts.master')

@include('administrator.'.$prefixView.'.header')

@section('css')

@endsection

@section('content')

    <div class="container-fluid list-products">
        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">

                        @include('administrator.'.$prefixView.'.search')

                    </div>

                    <div class="card-body">

                        @include('administrator.components.checkbox_delete_table')

                        <div class="table-responsive product-table">
                            <table class="table table-hover ">
                                <thead>
                                <tr>
                                    <th><input id="check_box_delete_all" type="checkbox" class="checkbox-parent" onclick="onSelectCheckboxDeleteItem()"></th>
                                    <th>#</th>
                                    <th>Tiêu đề</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Đã dùng</th>
                                    <th>Lượt dùng tối đa</th>
                                    <th>Loại mã</th>
                                    <th></th>
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
                                        <td>{{$item->name}}</td>
                                        <td>{{\App\Models\Formatter::getDateTime($item->begin)}}</td>
                                        <td>{{\App\Models\Formatter::getDateTime($item->end)}}</td>
                                        <td>{{\App\Models\Formatter::formatNumber($item->used)}}</td>
                                        <td>{{\App\Models\Formatter::formatNumber($item->max_use_by_time)}}</td>
                                        <td>{{$item->textTypeVoucher()}}</td>
                                        <td>
                                            @if($item->typeVoucher() == 1)
                                                {{\App\Models\Formatter::formatMoney($item->discount_amount)}}
                                            @else
                                                Giảm: {{\App\Models\Formatter::formatNumber($item->discount_percent)}}% - Giảm tối đa: {{\App\Models\Formatter::formatMoney($item->max_discount_percent_amount)}}
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('administrator.'.$prefixView.'.edit' , ['id'=> $item->id ])}}"
                                               class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>

                                            <a href="{{route('administrator.'.$prefixView.'.delete' , ['id'=> $item->id])}}"
                                               data-url="{{route('administrator.'.$prefixView.'.delete' , ['id'=> $item->id])}}"
                                               class="btn btn-outline-danger btn-sm delete action_delete"
                                               title="Delete">
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

@endsection

@section('js')

@endsection


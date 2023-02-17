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
                                    <th><input id="check_box_delete_all" type="checkbox" class="checkbox-parent"
                                               onclick="onSelectCheckboxDeleteItem()"></th>
                                    <th>#</th>
                                    <th>Khách hàng</th>
                                    <th>Sản phẩm</th>
                                    <th></th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr data-id="{{$item->id}}">
                                        <td class="text-center">
                                            <input type="checkbox" class="checkbox-delete-item" value="{{$item->id}}">
                                        </td>
                                        <td>{{$item->id}}</td>
                                        <td>
                                            <a>{{optional($item->user)->name}}</a>
                                        </td>
                                        <td>
                                            @foreach($item->products as $productItem)
                                                <div class="row mt-1">
                                                    <div class="col-2">
                                                        <img class="rounded-circle"
                                                             src="{{$productItem->product_image}}" alt="">
                                                    </div>
                                                    <div class="col-9" style="border-bottom: solid 1px aliceblue;border-top: solid 1px aliceblue;">
                                                        <div>
                                                            {{\App\Models\Formatter::getShortDescriptionAttribute($productItem->name)}}
                                                        </div>
                                                        @if(!empty($productItem->order_size) || !empty($productItem->order_color))
                                                            <div>
                                                                Phân loại:
                                                                <strong>{{\App\Models\Formatter::getShortDescriptionAttribute($productItem->order_size)}}</strong>,
                                                                <strong>{{\App\Models\Formatter::getShortDescriptionAttribute($productItem->order_color)}}</strong>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="col-1" style="border-bottom: solid 1px aliceblue;border-top: solid 1px aliceblue;">
                                                        x{{\App\Models\Formatter::formatNumber($productItem->quantity)}}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($item->waitingConfirm())
                                                <a style="color: lightskyblue;cursor: pointer;" onclick="onReadyShip(this)">Chuẩn bị hàng</a>
                                            @endif
                                        </td>
                                        <td class="text-status">
                                            {{ optional($item->orderStatus)->name}}
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
    <script>
        function onReadyShip(e){

            let that = $(e)

            const id = that.parent().parent().data('id')

            let url = "{{route('ajax.administrator.orders.update_to_shipping' , ['id' => 0])}}"

            url = url.replace("/0", "/" + id)

            console.log(url)

            callAjax(
                "PUT",
                url,
                {
                    order_status_id: 2
                },
                (response) => {

                    that.parent().parent().find('.text-status').html('Đang giao')
                    that.remove()

                },
                (error) => {

                },
                false,
            )

        }
    </script>
@endsection


@extends('administrator.layouts.master')

@include('administrator.'.$prefixView.'.header')

@section('css')

@endsection

@section('content')

    <form action="{{route('administrator.'.$prefixView.'.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="col-md-12">

            @include('administrator.components.require_input_text' , ['name' => 'name' , 'label' => 'Tên'])

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

            @include('administrator.components.require_input_text' , ['name' => 'short_description' , 'label' => 'Mô tả ngắn'])

            @include('administrator.components.require_textarea_description', ['name' => 'description' , 'label' => 'Mô tả'])

            @include('administrator.components.select_category' , ['name' => 'category_id' ,'html_category' => \App\Models\Category::getCategory(isset($item) ? optional($item)->category_id : ''), 'can_create' => true])

            <div id="container_infor__attributes" class="p-3">
                <label>
                    Sản phẩm có biển thể
                </label>
                <button onclick="addValueAttribute()" type="button" class="btn btn-outline-success"><i
                        class="fa-solid fa-plus"></i></button>
            </div>

            <div id="bassic_price">

            </div>

            <input id="_headers" name="_headers" type="text" value="" class="hidden">

            <input id="_attributes" name="_attributes" type="text" value="" class="hidden">

            <div id="table_bassic_price" class="card p-3 m-3" style="display: none;">

            </div>

            <div id="price">
                @include('administrator.components.input_number' , ['name' => 'price_import' , 'label' => 'Giá nhập'])

                @include('administrator.components.input_number' , ['name' => 'price_client' , 'label' => 'Giá bán lẻ'])

                @include('administrator.components.input_number' , ['name' => 'price_agent' , 'label' => 'Giá bán buôn (đại lý)'])

                @include('administrator.components.input_number' , ['name' => 'price_partner' , 'label' => 'Giá CTV (Cộng tác viên)'])

                @include('administrator.components.input_number' , ['name' => 'inventory' , 'label' => 'Tồn kho'])
            </div>

            @include('administrator.components.button_save')
        </div>
    </form>

@endsection


@section('js')
    <script>
        function _addAttribute() {
            $('#bassic_price').append(`<div class="p-3">

                <div id="attribute_2" class="card p-3">

                    <div class="text-end">
                        <button onclick="removeAllAttribute(this)" type="button" class="btn btn-danger"><i
                                class="fa-solid fa-x"></i></button>
                    </div>

                    <div class="d-flex mt-3">
                        <input type="text" autocomplete="off" class="form-control header-_attributes" placeholder="Thuộc tính" oninput="renderTableAttribute()" required>
                        <button type="button" onclick="addItemValueAttribute(this)" class="btn btn-success ms-1"
                                data-bs-original-title="" title=""><i class="fa-solid fa-plus"></i></button>
                    </div>

                    <div class="ms-3 mt-3 me-3 d-flex align-items-center">
                        <div class="d-flex mt-1">
                            <input type="text" autocomplete="off" class="form-control value-attribute" required placeholder="Giá trị" oninput="renderTableAttribute()">
                        </div>

                        <div class="text-end">
                            <button onclick="_removeAttribute(this)" type="button" class="btn btn-danger"><i class="fa-solid fa-x"></i></button>
                        </div>
                    </div>
                </div>

            </div>`)

            getAll_attributes()
        }

        function addValueAttribute() {
            $('#container_infor__attributes').html('')

            $('#bassic_price').html(`<div class="p-3">

                <div class="card p-3">

                    <div class="text-end">
                        <button onclick="removeAllAttribute(this)" type="button" class="btn btn-danger"><i
                                class="fa-solid fa-x"></i></button>
                    </div>

                    <div class="d-flex mt-3">
                        <input type="text" autocomplete="off" class="form-control header-_attributes"  oninput="renderTableAttribute()"
                               placeholder="Thuộc tính" required>
                        <button type="button" onclick="addItemValueAttribute(this)" class="btn btn-success ms-1"
                                data-bs-original-title="" title=""><i class="fa-solid fa-plus"></i></button>
                    </div>

                    <div class="ms-3 mt-3 me-3 d-flex align-items-center">
                        <div class="d-flex mt-1">
                            <input type="text" autocomplete="off" class="form-control value-attribute" required
                                   placeholder="Giá trị" oninput="renderTableAttribute()">
                        </div>

                        <div class="text-end">
                            <button onclick="_removeAttribute(this)" type="button" class="btn btn-danger"><i class="fa-solid fa-x"></i></button>
                        </div>
                    </div>
                </div></div><div class="add-attibutes p-3">
                    <label>
                        Thêm thuộc tính
                    </label>
                    <button onclick="_addAttribute()" type="button" class="btn btn-outline-success"
                            data-bs-original-title="" title=""><i class="fa-solid fa-plus"></i></button>
                </div>`)

            getAll_attributes()
        }

        function removeValueAttribute(e) {
            const ele = $(e)
            ele.parent().remove()
        }

        function removeAllAttribute(e) {
            const ele = $(e)
            ele.parent().parent().parent().remove()

            getAll_attributes()

            renderTableAttribute()
        }

        function addItemValueAttribute(e) {
            $(e).parent().parent().append(`<div class="ms-3 mt-3 me-3 d-flex align-items-center">
                        <div class="d-flex mt-1">
                            <input type="text" autocomplete="off" class="form-control value-attribute" required
                                   placeholder="Giá trị" oninput="renderTableAttribute()">
                        </div>

                        <div class="text-end">
                            <button onclick="_removeAttribute(this)" type="button" class="btn btn-danger"><i class="fa-solid fa-x"></i></button>
                        </div>
                    </div>`)
        }

        function _removeAttribute(e) {
            $(e).parent().parent().remove()

            renderTableAttribute()
        }

        let _headers = []
        let _attributes = []

        function getAll_attributes() {

            _headers = []
            _attributes = []

            let p3s = document.querySelectorAll('#bassic_price > .p-3')

            for (let i = 0; i < p3s.length; i++) {
                const header = p3s[i].querySelector('.header-_attributes')

                if (!empty(header)) {
                    _headers.push(header.value)

                    const child__attributes = p3s[i].querySelectorAll('.value-attribute')

                    const value__attributes = []

                    for (let j = 0; j < child__attributes.length; j++) {
                        value__attributes.push(child__attributes[j].value)
                    }
                    _attributes.push(value__attributes)
                }
            }
            $('.add-attibutes').remove()

            if (_headers.length < 2) {
                $('#bassic_price').append(`<div class="add-attibutes">
                    <label>
                        Thêm thuộc tính
                    </label>
                    <button onclick="_addAttribute()" type="button" class="btn btn-outline-success"
                            data-bs-original-title="" title=""><i class="fa-solid fa-plus"></i></button>
                </div>`)
            }

            if (_headers.length == 0) {
                $('#price').show()
                $('#table_bassic_price').hide()
            } else {
                $('#price').hide()
                $('#table_bassic_price').show()
            }

        }

        function renderTableAttribute() {

            _headers = []
            _attributes = []

            let p3s = document.querySelectorAll('#bassic_price > .p-3')

            for (let i = 0; i < p3s.length; i++) {
                const header = p3s[i].querySelector('.header-_attributes')

                if (!empty(header)) {
                    _headers.push(header.value)

                    const child__attributes = p3s[i].querySelectorAll('.value-attribute')

                    const value__attributes = []

                    for (let j = 0; j < child__attributes.length; j++) {
                        value__attributes.push(child__attributes[j].value)
                    }
                    _attributes.push(value__attributes)
                }
            }

            console.log(_headers)
            console.log(_attributes)

            $('#_headers').val(JSON.stringify(_headers))
            $('#_attributes').val(JSON.stringify(_attributes))

            $('#table_bassic_price').html('')

            if (_headers.length == 1){

                let header = `<div class="row mt-2">
                        <div class="col-4">
                            ${_headers[0]}
                        </div>
                        <div class="col-1">
                            Giá nhập
                        </div>
                        <div class="col-1">
                            Giá bán lẻ
                        </div>
                        <div class="col-1">
                            Giá bán buôn
                        </div>
                        <div class="col-1">
                            Giá CTV
                        </div>
                        <div class="col-2">
                            Kho hàng
                        </div>
                        <div class="col-2">
                            SKU
                        </div>
                    </div>`

                $('#table_bassic_price').append(header)

                for (let i = 0 ; i < _attributes[0].length;i++){
                    let row = '<div class="row mt-2">'
                    row += `<div class="col-4">${_attributes[0][i]}</div>`
                    row += `<div class="col-1">
                            <input name="import_prices[]" type="text" autocomplete="off" class="form-control number" value="" required>
                        </div>
                        <div class="col-1">
                            <input name="client_prices[]" type="text" autocomplete="off" class="form-control number" value="" required>
                        </div>
                        <div class="col-1">
                            <input name="agent_prices[]" type="text" autocomplete="off" class="form-control number" value="" required>
                        </div>
                        <div class="col-1">
                            <input name="partner_prices[]" type="text" autocomplete="off" class="form-control number" value="" required>
                        </div>
                        <div class="col-2">
                            <input name="inventories[]" type="text" autocomplete="off" class="form-control number" value="" required>
                        </div>
                        <div class="col-2">
                            <input name="skus[]" type="text" autocomplete="off" class="form-control" value="">
                        </div>`

                    row += "</div>"

                    $('#table_bassic_price').append(row)
                }
            }else{
                let header = `<div class="row mt-2">
                        <div class="col-2">
                            ${_headers[0]}
                        </div>
                        <div class="col-2">
                            ${_headers[1]}
                        </div>
                        <div class="col-1">
                            Giá nhập
                        </div>
                        <div class="col-1">
                            Giá bán lẻ
                        </div>
                        <div class="col-1">
                            Giá bán buôn
                        </div>
                        <div class="col-1">
                            Giá CTV
                        </div>
                        <div class="col-2">
                            Kho hàng
                        </div>
                        <div class="col-2">
                            SKU
                        </div>
                    </div>`

                $('#table_bassic_price').append(header)

                for (let i = 0 ; i < _attributes[0].length;i++){
                    for(let j = 0 ; j < _attributes[1].length;j++){
                        let row = '<div class="row mt-2">'
                        row += `<div class="col-2">${_attributes[0][i]}</div>`
                        row += `<div class="col-2">${_attributes[1][j]}</div>`
                        row += `<div class="col-1">
                            <input name="import_prices[]" type="text" autocomplete="off" class="form-control number" value="" required>
                        </div>
                        <div class="col-1">
                            <input name="client_prices[]" type="text" autocomplete="off" class="form-control number" value="" required>
                        </div>
                        <div class="col-1">
                            <input name="agent_prices[]" type="text" autocomplete="off" class="form-control number" value="" required>
                        </div>
                        <div class="col-1">
                            <input name="partner_prices[]" type="text" autocomplete="off" class="form-control number" value="" required>
                        </div>
                        <div class="col-2">
                            <input name="inventories[]" type="text" autocomplete="off" class="form-control number" value="" required>
                        </div>
                        <div class="col-2">
                            <input name="skus[]" type="text" autocomplete="off" class="form-control" value="">
                        </div>`

                        row += "</div>"

                        $('#table_bassic_price').append(row)
                    }
                }
            }


        }
    </script>
@endsection

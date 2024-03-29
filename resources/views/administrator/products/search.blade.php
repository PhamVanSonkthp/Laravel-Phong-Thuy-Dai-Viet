

<div>
    @include('administrator.components.search')

    <a href="{{route('administrator.'.$prefixView.'.create')}}" class="btn btn-success float-end"><i class="fa-solid fa-plus"></i></a>

    <a onclick="onExport()" class="btn btn-outline-primary float-end me-2" data-bs-original-title="" title="Export Excel"><i class="fa-sharp fa-solid fa-file-excel"></i></a>

    @include('administrator.components.input_import_excel')

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-6">

            <div class="row align-items-end">
                <div class="col-2">
                    <label>Kho hàng</label>
                </div>

                <div class="col-3">
                    @include('administrator.components.input_number' , ['name' => 'min_inventory' , 'label' => 'Tối thiểu'])
                </div>
                <div class="col-1">
                    <div class="text-center mb-2">
                        -
                    </div>
                </div>

                <div class="col-3">
                    @include('administrator.components.input_number' , ['name' => 'max_inventory' , 'label' => 'Tối đa'])
                </div>

                <div class="col-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="check_box_feature" name="check_box_feature" {{request('is_feature') ? 'checked' : ''}}>
                        <label class="form-check-label" for="check_box_feature">
                            Sản phẩm xu hướng
                        </label>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-6">
            <div>
                @include('administrator.components.search_select2_allow_clear' , ['name' => 'category_id' , 'label' => 'Danh mục sản phẩm', 'select2Items' => $categories])
            </div>
        </div>

    </div>

</div>


<script>

    function onSearchQuery() {
        addUrlParameterObjects([
            {name: "search_query", value: $('#input_search_query').val()},
            {name: "from", value: input_query_from},
            {name: "to", value: input_query_to},
            {name: "min_inventory", value: $('input[name="min_inventory"]').val()},
            {name: "max_inventory", value: $('input[name="max_inventory"]').val()},
            {name: "is_feature", value: $('input[name="check_box_feature"]').is(":checked") ? '1' : ''},
        ])
    }

    function onExport() {
        window.location.href = "{{route('administrator.'.$prefixView.'.export')}}" + window.location.search
    }


</script>

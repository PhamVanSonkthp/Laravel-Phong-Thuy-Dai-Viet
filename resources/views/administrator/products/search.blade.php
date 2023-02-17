<div>
    @include('administrator.components.search')

    <a href="{{route('administrator.'.$prefixView.'.create')}}" class="btn btn-success float-end"><i
            class="fa-solid fa-plus"></i></a>

    <a href="{{route('administrator.'.$prefixView.'.export')}}" class="btn btn-outline-primary float-end me-2" data-bs-original-title="" title="Excel"><i class="fa-sharp fa-solid fa-file-excel"></i></a>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-6">

            <div class="row align-items-end">
                <div class="col-2">
                    <label>Kho hàng</label>
                </div>

                <div class="col-4">
                    @include('administrator.components.input_number' , ['name' => 'min_inventory' , 'label' => 'Tối thiểu'])
                </div>
                <div class="col-1">
                    <div class="text-center mb-2">
                        -
                    </div>
                </div>

                <div class="col-4">
                    @include('administrator.components.input_number' , ['name' => 'max_inventory' , 'label' => 'Tối đa'])
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
        ])
    }

</script>

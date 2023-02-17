
<style>
    .wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .wrapper .file-upload {
        height: 37px;
        width: 50px;
        border-radius: 100px;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        background-image: linear-gradient(to bottom, #2590eb 50%, #fff 50%);
        background-size: 100% 200%;
        transition: all 1s;
        color: #fff;
        font-size: 20px;
    }
    .wrapper .file-upload input[type='file'] {
        height: 200px;
        width: 200px;
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }
    .wrapper .file-upload:hover {
        background-position: 0 -100%;
        color: #2590eb;
    }

</style>

<div>
    @include('administrator.components.search')

    <a href="{{route('administrator.'.$prefixView.'.create')}}" class="btn btn-success float-end"><i class="fa-solid fa-plus"></i></a>

    <a href="{{route('administrator.'.$prefixView.'.export')}}" class="btn btn-outline-primary float-end me-2" data-bs-original-title="" title="Export Excel"><i class="fa-sharp fa-solid fa-file-excel"></i></a>


    <div class="wrapper" style="float: right;">
        <div class="file-upload me-2">
            <input id="input_import" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" onchange="onImport()" />
            <i class="fa fa-arrow-up"></i>
        </div>
    </div>

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

    function onImport(){
        var formData = new FormData(); // Currently empty
        formData.append('import_file', document.querySelector('#input_import').files[0], 'chris.jpg');

        callAjaxMultipart(
            "POST",
            "{{route('administrator.'.$prefixView.'.import')}}",
            formData,
            (response) => {
                alert('đã thêm ' + response + " sản phẩm")
            },
            (error) => {
                console.log(error)
            },
            (percent) => {
                console.log(percent)
            },
            true,
            true,
            true,
        )
    }

</script>

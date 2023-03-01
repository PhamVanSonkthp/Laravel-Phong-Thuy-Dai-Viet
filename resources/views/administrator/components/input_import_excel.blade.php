
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


<div class="wrapper" style="float: right;">
    <div class="file-upload me-2">
        <input id="input_import" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" onchange="onImport()" />
        <i class="fa fa-arrow-up"></i>
    </div>
</div>

<script>
    function onImport(){
        var formData = new FormData(); // Currently empty
        formData.append('import_file', document.querySelector('#input_import').files[0], 'chris.jpg');

        callAjaxMultipart(
            "POST",
            "{{route('administrator.'.$prefixView.'.import')}}",
            formData,
            (response) => {
                console.log(response)
                if (confirm('Đã thêm ' + response + " bản ghi") == true) {
                    window.location.reload()
                } else {
                    window.location.reload()
                }
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

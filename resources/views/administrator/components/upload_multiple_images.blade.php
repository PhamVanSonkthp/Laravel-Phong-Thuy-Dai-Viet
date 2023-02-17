<style>
    #drop-region {
        background-color: #fff;
        border-radius: 20px;
        box-shadow: 0 0 35px rgba(0, 0, 0, 0.05);
        /*width:400px;*/
        padding: 20px;
        /*text-align: center;*/
        /*cursor: pointer;*/
        transition: .3s;
        min-height: 314px;
        position: relative;
    }

    .drop-region-active{
        filter: drop-shadow(0 0 0.15rem crimson);
    }

    #drop-region:hover {
        box-shadow: 0 0 45px rgba(0, 0, 0, 0.1);
    }

    #image-preview {
        margin-top: 20px;
        display: flex;
        gap: 10px;
        overflow-x: auto;
    }

    #image-preview .image-view {
        display: inline-block;
        position: relative;
        margin-right: 13px;
        margin-left: 13px;
    }

    #image-preview .image-view img {
        max-width: 102px;
        max-height: 220px;
        box-shadow: rgb(0 0 0 / 20%) 0px 0px 1px inset;
        border-radius: 12px;
        border: 1px solid #dee2e6 !important;
    }

    #image-preview .overlay {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        right: 0;
        z-index: 2;
        background: rgba(255, 255, 255, 0.5);
    }

    #drop-message {
        position: absolute;
        transform: translate(-50%, -50%);
        left: 50%;
        bottom: 50%;
    }

    .delete-button {
        width: 0px;
        color: red;
        font-size: 25px;
        position: absolute;
        left: -5px;
        top: 0px;
    }

    .delete-button:hover {
        cursor: pointer;
    }

    .delete-button {
        visibility: hidden;
    }

    .image-view {
        cursor: pointer;
    }

    .image-view:hover .delete-button {
        visibility: visible;
    }

    .container-spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .drop-blur {
        filter: blur(2px);
        -webkit-filter: blur(2px);
    }


</style>

@php

if(isset($item)) {
    $images = $item->images;
    $relateImageTableId = $item->id;
    $relate_id = $item->id;
}

@endphp

<div id="drop-region">
    <div id="drop-message">
        Kéo thả hình ảnh vào đây
    </div>
    <ul id="image-preview">
        @if(isset($images))
            @foreach($images as $image)
                <li class="image-view" id="drop_image__{{$image->id}}">
                    <img src="{{$image->image_path}}">
                    <i class="fa fa-minus-square-o delete-button" onclick="deleteDropImage(this)"></i>
                </li>
            @endforeach

        @endif
    </ul>
</div>

<script>

    @if(isset($images))
    @if(count($images))
    $('#drop-message').hide()
    @endif
    @endif

    const url_upload_file = "{{$post_api}}"
    const url_delete_file = "{{$delete_api}}"
    const url_sort_file = "{{$sort_api}}"
    const maximum_upload_file = 10e6
    const accept_upload_file = ['image/jpeg', 'image/png', 'image/gif']

    $('#image-preview').on('sortupdate', function () {
        const imageIdsArray = [];
        $('#image-preview li').each(function (index) {
            var id = $(this).attr('id');
            if (isDefine(id)) {
                const split_id = id.split("__");
                imageIdsArray.push(split_id[1]);
            }
        });

        callAjax(
            "PUT",
            url_sort_file,
            {
                ids: imageIdsArray
            },
            (response) => {

            },
            (error) => {

            },
            false,
        )
    });

    $("#image-preview").sortable({
        update: function (event, ui) {
            // dropIndex = ui.item.index();
        },
    });


    var // where files are dropped + file selector is opened
        dropRegion = document.getElementById("drop-region"),
        // where images are previewed
        imagePreviewRegion = document.getElementById("image-preview");


    // open file selector when clicked on the drop region
    var fakeInput = document.createElement("input");
    fakeInput.type = "file";
    fakeInput.accept = "image/*";
    fakeInput.multiple = true;
    // dropRegion.addEventListener('click', function () {
    //     fakeInput.click();
    // });

    fakeInput.addEventListener("change", function () {
        var files = fakeInput.files;
        handleFiles(files);
    });


    function preventDefault(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    dropRegion.addEventListener('dragenter', preventDefault, false)
    dropRegion.addEventListener('dragleave', preventDefault, false)
    dropRegion.addEventListener('dragover', preventDefault, false)
    dropRegion.addEventListener('drop', preventDefault, false)


    function handleDrop(e) {
        var dt = e.dataTransfer,
            files = dt.files;

        if (files.length) {

            handleFiles(files);

        } else {

            // check for img
            var html = dt.getData('text/html'),
                match = html && /\bsrc="?([^"\s]+)"?\s*/.exec(html),
                url = match && match[1];


            if (url) {
                uploadImageFromURL(url);
                return;
            }

        }


        function uploadImageFromURL(url) {
            var img = new Image;
            var c = document.createElement("canvas");
            var ctx = c.getContext("2d");

            img.onload = function () {
                c.width = this.naturalWidth;     // update canvas size to match image
                c.height = this.naturalHeight;
                ctx.drawImage(this, 0, 0);       // draw in image
                c.toBlob(function (blob) {        // get content as PNG blob

                    // call our main function
                    handleFiles([blob]);

                }, "image/png");
            };
            img.onerror = function () {
                alert("Error in uploading");
            }
            img.crossOrigin = "";              // if from different origin
            img.src = url;
        }

    }

    dropRegion.addEventListener('drop', handleDrop, false);


    function handleFiles(files) {
        for (var i = 0, len = files.length; i < len; i++) {
            if (validateImage(files[i]))
                previewAnduploadImage(files[i]);
        }
    }

    function validateImage(image) {
        // check the type
        var validTypes = accept_upload_file;
        if (validTypes.indexOf(image.type) === -1) {
            alert("Invalid File Type");
            return false;
        }

        // check the size
        var maxSizeInBytes = maximum_upload_file; // 10MB
        if (image.size > maxSizeInBytes) {
            alert("File too large");
            return false;
        }

        return true;

    }

    function previewAnduploadImage(image) {

        let random_id = uuidv4()

        $('#drop-message').hide()

        // container
        var imgView = document.createElement("li");
        imgView.className = "image-view";
        imgView.id = "drop_image__" + random_id;

        imagePreviewRegion.appendChild(imgView);

        // previewing image
        var img = document.createElement("img");
        img.className = "drop-blur"
        imgView.appendChild(img);

        // progress overlay
        var overlay = document.createElement("i");
        overlay.className = "overlay";
        imgView.appendChild(overlay);

        // spinner overlay
        var container_spinner = document.createElement("span");
        container_spinner.className = "container-spinner";
        var spinner = document.createElement("i");
        spinner.className = "fa fa-spin fa-spinner";
        container_spinner.appendChild(spinner);
        imgView.appendChild(container_spinner);

        // delete button
        var delete_button = document.createElement("i");
        // delete_button.onclick = deleteDropImage;
        delete_button.setAttribute("onclick","deleteDropImage(this)");
        delete_button.className = "fa fa-minus-square-o delete-button";
        imgView.appendChild(delete_button);

        // read the image...
        var reader = new FileReader();
        reader.onload = function (e) {
            img.src = e.target.result;
        }
        reader.readAsDataURL(image);

        // create FormData
        var formData = new FormData();
        formData.append('image', image);
        formData.append('id', random_id);
        formData.append('table', '{{isset($table) ? $table : ''}}');
        formData.append('relate_id', '{{isset($relate_id) ? $relate_id : ''}}');

        callAjaxMultipart(
            "POST",
            url_upload_file,
            formData,
            (response) => {
                container_spinner.remove()
                overlay.remove();
                img.classList.remove("drop-blur");
                $("#image-preview").sortable("refresh");
                $('#image-preview').trigger('sortupdate')
            },
            (error) => {
                console.log(error)
            },
            (percent) => {
                overlay.style.width = percent;
            },
            false,
            false,
            false,
        )

    }

    function deleteDropImage(event){
        const removed_id = $(event).parent().attr('id').split('__')[1]
        $(event).parent().remove()
        if ($('#image-preview').children().length == 0) {
            $('#drop-message').show()
        }
        callAjax(
            "DELETE",
            url_delete_file,
            {
                id: removed_id
            },
            (response) => {

            },
            (error) => {

            },
            false,
        )
    }

</script>

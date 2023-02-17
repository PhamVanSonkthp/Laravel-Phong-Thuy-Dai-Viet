<style>
    #single-drop-region {
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

        display: flex;
        justify-content: center;
        align-items: center;

    }

    #single-drop-region:hover {
        box-shadow: 0 0 45px rgba(0, 0, 0, 0.1);
        cursor: pointer;
    }

    #single-image-preview {
        /*margin-top: 20px;*/
        display: flex;
        gap: 10px;
        overflow-x: auto;
        justify-content: center;
    }

    #single-image-preview .single-image-view {
        display: inline-block;
        position: relative;
        margin-right: 13px;
        margin-left: 13px;
    }

    #single-image-preview .single-image-view img {
        /*max-width: 102px;*/
        width: 100%;
        max-height: 220px;
        box-shadow: rgb(0 0 0 / 20%) 0px 0px 1px inset;
        border-radius: 12px;
        border: 1px solid #dee2e6 !important;
        pointer-events: none;
    }

    #single-image-preview .overlay {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        right: 0;
        z-index: 2;
        background: rgba(255, 255, 255, 0.5);
    }

    #single-drop-message {
        position: absolute;
        transform: translate(-50%, -50%);
        left: 50%;
        bottom: 50%;
    }

    .single-image-view {
        cursor: pointer;
    }

    .single-image-view:hover .delete-button {
        visibility: visible;
    }

    .single-container-spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .single-drop-blur {
        filter: blur(2px);
        -webkit-filter: blur(2px);
    }
</style>

@php

    if(isset($item)) {
        $image = optional($item->image)->image_path;
        $relateImageTableId = $item->id;
        $relate_id = $item->id;
    }

@endphp

<div id="single-drop-region" style="max-width: 314px;max-height: 314px;">
    <div id="single-drop-message">
        Icon
    </div>
    <ul id="single-image-preview">
        @if(isset($image))
            <li class="single-image-view">
                <img style="" src="{{$image}}">
            </li>
        @endif
    </ul>
</div>

<script>
    @if(isset($image))
        $('#single-drop-message').hide()
    @endif

    const single_url_upload_file = "{{$post_api}}"
    const single_maximum_upload_file = 10e6
    const single_accept_upload_file = ['image/jpeg', 'image/png', 'image/gif']

    var // where files are dropped + file selector is opened
        single_dropRegion = document.getElementById("single-drop-region"),
        // where images are previewed
        single_imagePreviewRegion = document.getElementById("single-image-preview");


    // open file selector when clicked on the drop region
    var single_fakeInput = document.createElement("input");
    single_fakeInput.type = "file";
    single_fakeInput.accept = "image/*";
    single_fakeInput.multiple = false;
    single_dropRegion.addEventListener('click', function () {
        single_fakeInput.click();
    });

    single_fakeInput.addEventListener("change", function () {
        var files = single_fakeInput.files;
        single_handleFiles(files);
    });


    function single_preventDefault(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    single_dropRegion.addEventListener('dragenter', single_preventDefault, false)
    single_dropRegion.addEventListener('dragleave', single_preventDefault, false)
    single_dropRegion.addEventListener('dragover', single_preventDefault, false)
    single_dropRegion.addEventListener('drop', single_preventDefault, false)


    function single_handleDrop(e) {
        var dt = e.dataTransfer,
            files = dt.files;

        if (files.length) {

            single_handleFiles(files);

        } else {

            // check for img
            var html = dt.getData('text/html'),
                match = html && /\bsrc="?([^"\s]+)"?\s*/.exec(html),
                url = match && match[1];


            if (url) {
                single_uploadImageFromURL(url);
                return;
            }

        }


        function single_uploadImageFromURL(url) {
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

    single_dropRegion.addEventListener('drop', single_handleDrop, false);


    function single_handleFiles(files) {
        for (var i = 0, len = files.length; i < len; i++) {
            if (single_validateImage(files[i]))
                single_previewAnduploadImage(files[i]);
        }
    }

    function single_validateImage(image) {
        // check the type
        var validTypes = single_accept_upload_file;
        if (validTypes.indexOf(image.type) === -1) {
            alert("Invalid File Type");
            return false;
        }

        // check the size
        var maxSizeInBytes = single_maximum_upload_file; // 10MB
        if (image.size > maxSizeInBytes) {
            alert("File too large");
            return false;
        }

        return true;

    }

    function single_previewAnduploadImage(image) {

        let random_id = uuidv4()

        $('#single-drop-message').hide()

        // container
        var imgView = document.createElement("li");
        imgView.className = "single-image-view";
        imgView.id = "drop_image__" + random_id;

        single_imagePreviewRegion.innerHTML = ''
        single_imagePreviewRegion.appendChild(imgView);

        // previewing image
        var img = document.createElement("img");
        img.className = "single-drop-blur"
        imgView.appendChild(img);

        // progress overlay
        var overlay = document.createElement("i");
        overlay.className = "overlay";
        imgView.appendChild(overlay);

        // spinner overlay
        var container_spinner = document.createElement("span");
        container_spinner.className = "single-container-spinner";
        var spinner = document.createElement("i");
        spinner.className = "fa fa-spin fa-spinner";
        container_spinner.appendChild(spinner);
        imgView.appendChild(container_spinner);

        // delete button
        // var delete_button = document.createElement("i");
        // // delete_button.onclick = deleteDropImage;
        // delete_button.setAttribute("onclick", "deleteDropImage(this)");
        // delete_button.className = "fa fa-minus-square-o delete-button";
        // imgView.appendChild(delete_button);

        // read the image...
        var reader = new FileReader();
        reader.onload = function (e) {
            img.src = e.target.result;
        }
        reader.readAsDataURL(image);

        // create FormData
        var formData = new FormData();
        formData.append('image', image);
        formData.append('id', '{{$relate_id}}');
        formData.append('table', '{{isset($table) ? $table : ''}}');
        formData.append('relate_id', '{{isset($relate_id) ? $relate_id : ''}}');

        callAjaxMultipart(
            "POST",
            single_url_upload_file,
            formData,
            (response) => {
                container_spinner.remove()
                overlay.remove();
                img.classList.remove("single-drop-blur");
                console.log(response)
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


</script>

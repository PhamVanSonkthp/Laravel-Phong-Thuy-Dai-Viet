
<div class="modal fade" id="show_image_modal" tabindex="-1" aria-labelledby="show_image_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="show_image_modalLabel">Ảnh</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <img id="image_show_on_modal">
        </div>
    </div>
</div>


<script>
    function showImage(e) {
        const src = $(e).attr("src")

        const myModal = new bootstrap.Modal(document.getElementById('show_image_modal'))
        $("#image_show_on_modal").attr("src", src)
        myModal.show()
    }
</script>

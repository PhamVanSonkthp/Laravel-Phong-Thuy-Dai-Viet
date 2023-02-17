<div>
    @include('administrator.components.search')

    <a href="{{route('administrator.'.$prefixView.'.create')}}" class="btn btn-outline-success float-end"><i
            class="fa-solid fa-plus"></i></a>

    <a href="{{route('administrator.'.$prefixView.'.export')}}" class="btn btn-outline-primary float-end me-2" data-bs-original-title="" title="Excel"><i class="fa-sharp fa-solid fa-file-excel"></i></i></a>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-3">
            <div class="mt-3">
                <label>Lọại khách hàng</label>
                <select name="user_type_id" class="form-control select2_init_allow_clear">
                    @foreach($userTypes as $userTypeItem)
                        <option value="{{$userTypeItem->id}}" {{request('user_type_id') == $userTypeItem->id ? 'selected' : ''}}>{{$userTypeItem->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>
</div>


<script>

    $('select[name="user_type_id"]').on('change', function () {
        addUrlParameter('user_type_id', this.value)
    });

</script>

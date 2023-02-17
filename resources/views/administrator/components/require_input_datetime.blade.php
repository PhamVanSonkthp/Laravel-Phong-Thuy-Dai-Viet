@php
    if(isset($item)){
        $value = $item->$name;
    }else{
        $value = old($name);
    }

    $defaultDatetime = $value;
@endphp

<div class="form-group mt-3">
    <label>{{$label}} @include('administrator.components.lable_require') </label>
    <input type="date" autocomplete="off" name="{{$name}}" class="bg-white form-control open-jquery-date-time @error($name) is-invalid @enderror"
           value="" required placeholder="--/--/--">
    @error($name)
    <div class="alert alert-danger">{{$message}}</div>
    @enderror
</div>

<script>

    $(document).ready(function () {
        $('.open-jquery-date-time').flatpickr({
            enableTime: true,
            dateFormat: "{{config('_my_config.type_date_time_no_second')}}",
            defaultDate: "{{ $defaultDatetime }}",
        });
    });


</script>

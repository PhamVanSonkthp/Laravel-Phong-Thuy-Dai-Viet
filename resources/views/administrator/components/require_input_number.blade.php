@php
    if(isset($item)){
        $value = $item->$name;
    }else{
        $value = old($name);
    }
@endphp

<div class="form-group {{ (isset($no_margin) && $no_margin == true) ? "" : "mt-3" }}">
    @if(isset($label))<label> {{$label}} @include('administrator.components.lable_require') </label>@endif
    <input type="text" autocomplete="off" name="{{$name}}" class="form-control number @error($name) is-invalid @enderror"
           value="{{$value}}" required placeholder="{{isset($placeholder) ? $placeholder : ''}}">
    @error($name)
    <div class="alert alert-danger">{{$message}}</div>
    @enderror
</div>

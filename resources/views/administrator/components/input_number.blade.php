@php
    if(isset($item)){
        $value = $item->$name;
    }else{
        $value = old($name) ?? request($name);
    }
@endphp

<div class="form-group {{ (isset($no_margin) && $no_margin == true) ? "" : "mt-3" }}">
    @if(isset($label))<label> {{$label}} </label>@endif
    <input type="text" autocomplete="off" name="{{$name}}" class="form-control number @error($name) is-invalid @enderror"
           value="{{$value}}" placeholder="{{isset($placeholder) ? $placeholder : ''}}" style="{{isset($hidden) ? "display: none;" : ''}}">
    @error($name)
    <div class="alert alert-danger">{{$message}}</div>
    @enderror
</div>

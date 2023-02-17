@php
    if(isset($item)){
        $value = $item->$name;
    }else{
        $value = old($name);
    }
@endphp

<div class="form-group mt-3">
    <label>{{$label}}</label>
    <input type="text" autocomplete="off" name="{{$name}}" class="form-control @error($name) is-invalid @enderror"
           value="{{$value}}">
    @error($name)
    <div class="alert alert-danger">{{$message}}</div>
    @enderror
</div>

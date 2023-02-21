@php
    if(isset($item)){
        $value = $item->$name;
    }else{
        $value = old($name);
    }
@endphp

<div class="form-group mt-3">
    <label>{{isset($lable) ? $lable : ''}}</label>
    <select class="form-control select2_init{{(isset($can_create) && $can_create) ? '_tag' : ''}} @error('category_id') is-invalid @enderror"
            name="{{$name}}">
        <option value="0">-Không có danh mục-</option>
        {!! $html_category !!}
    </select>
    @error('category_id')
    <div class="alert alert-danger">{{$message}}</div>
    @enderror
</div>

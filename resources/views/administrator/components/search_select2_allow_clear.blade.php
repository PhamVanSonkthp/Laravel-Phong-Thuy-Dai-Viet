@php
    $value = request($name);
@endphp

<div class="mt-3">
    <label>{{$label}}</label>
    <select name="{{$name}}" class="form-control select2_init_allow_clear">
        <option value="">
            Chọn
        </option>
        @foreach($select2Items as $select2Item)
            <option value="{{$select2Item->id}}" {{$value == $select2Item->id ? 'selected' : ''}}>{{$select2Item->name ?? $select2Item->title}}</option>
        @endforeach
    </select>
</div>


<script>

    $('select[name="{{$name}}"]').on('change', function () {
        addUrlParameter('{{$name}}', this.value)
    });

</script>

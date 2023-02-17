@php
    $title = "Danh mục sản phẩm";
@endphp
@section('title')
    <title>{{$title}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('name')
    <h4 class="page-title">{{$title}}</h4>
@endsection

@section('category')
    class="mm-active"
@endsection

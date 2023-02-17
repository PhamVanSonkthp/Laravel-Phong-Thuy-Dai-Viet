@php
    $title = "Sản phẩm";
@endphp
@section('title')
    <title>{{$title}}</title>
@endsection

@section('name')
    <h4 class="page-title">{{$title}}</h4>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('product')
    class="mm-active"
@endsection

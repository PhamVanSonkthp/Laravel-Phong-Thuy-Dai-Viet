<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <title>Admin & Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin Infinity Ltd" name="description">
    <meta content="Pham Son" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ env('APP_URL') . \App\Models\Helper::logoImagePath() }}">

    @yield('title')

    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/administrator/css/vendors/font-awesome.css')}}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/administrator/css/vendors/icofont.css')}}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/administrator/css/vendors/themify.css')}}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/administrator/css/vendors/flag-icon.css')}}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/administrator/css/vendors/feather-icon.css')}}">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/administrator/css/vendors/scrollbar.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/administrator/css/vendors/date-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/administrator/css/vendors/owlcarousel.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/administrator/css/vendors/prism.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/administrator/css/vendors/whether-icon.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/vendor/datatable/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/vendor/owlcarousel/owlcarousel.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/vendor/rating/rating.css')}}">
{{--    <link rel="stylesheet" type="text/css" href="{{asset('/vendor/fontawesome-6.0.0/css/fontawesome.css')}}"/>--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" media="all" href="{{asset('vendor/datetimepicker/daterangepicker.css')}}"/>
    <link rel="stylesheet" type="text/css" media="all" href="{{asset('vendor/select2/select2.min.css')}}"/>
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/administrator/css/vendors/bootstrap.css')}}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/administrator/css/style.css')}}">
    <link id="color" rel="stylesheet" href="{{asset('/assets/administrator/css/color-1.css')}}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/administrator/css/responsive.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('/assets/administrator/css/vendors/jquery-ui.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/administrator/css/order-image.css')}}" >
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


    <script src="{{asset('/assets/administrator/js/jquery-3.5.1.min.js')}}"></script>
    <script src="{{asset('/assets/administrator/js/jquery.ui.min.js')}}"></script>
    <script src="{{asset('/vendor/jquery-ui-1.13.2/jquery-ui.js')}}"></script>
    <script src="{{asset('/vendor/masknumber/jquery.masknumber.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @include('administrator.components.helper')

    <style>
        .product-table{
            overflow-x: auto !important;
        }

        .product-table > table > tbody > tr td:last-child {
            width: 1%;
            white-space: nowrap;
        }

        .product-table > table > tbody > tr td:first-child {
            width: 1%;
            white-space: nowrap;
            text-align: start;
        }

        .fa {
            font-family: 'FontAwesome';
        }

        .list-products .product-table table th {
            min-width: 0 !important;
        }

        .customizer-links{
            display: none;
        }

        .simplebar-content-wrapper{
            overflow: visible;
        }

        .rounded-circle {
            height: 40px;
            width: 40px;
            object-fit: cover;
        }

    </style>
    @yield('css')
</head>

<body>

<!-- Loader starts-->
<div class="loader-wrapper">
    <div class="loader">
        <div class="loader-bar"></div>
        <div class="loader-bar"></div>
        <div class="loader-bar"></div>
        <div class="loader-bar"></div>
        <div class="loader-bar"></div>
        <div class="loader-ball"></div>
    </div>
</div>
<!-- Loader ends-->

<!-- tap on top starts-->
<div class="tap-top"><i data-feather="chevrons-up"></i></div>
<!-- tap on tap ends-->
<!-- page-wrapper Start-->
<div class="page-wrapper compact-wrapper" id="pageWrapper">

@include('administrator.components.header')

<!-- Page Body Start-->
    <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
        <div class="sidebar-wrapper">
            <div>
                @include('administrator.components.logo')
                @include('administrator.components.slidebars')
            </div>
        </div>
        <!-- Page Sidebar Ends-->
        <div class="page-body">

            <div class="container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <h3>{{isset($title) ? $title : ''}}</h3>
                        </div>
                    </div>
                </div>
            </div>

            @yield('content')

        </div>

        <!-- footer start-->

        @include('administrator.components.footer')
    </div>
</div>

<!-- Bootstrap js-->
<script src="{{asset('/assets/administrator/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
<!-- feather icon js-->
<script src="{{asset('/assets/administrator/js/icons/feather-icon/feather.min.js')}}"></script>
<script src="{{asset('/assets/administrator/js/icons/feather-icon/feather-icon.js')}}"></script>
<!-- scrollbar js-->
<script src="{{asset('/assets/administrator/js/scrollbar/simplebar.js')}}"></script>
<script src="{{asset('/assets/administrator/js/scrollbar/custom.js')}}"></script>
<!-- Sidebar jquery-->
<script src="{{asset('/assets/administrator/js/config.js')}}"></script>
<!-- Plugins JS start-->
<script src="{{asset('/assets/administrator/js/sidebar-menu.js')}}"></script>
<script src="{{asset('/assets/administrator/js/prism/prism.min.js')}}"></script>
<script src="{{asset('/assets/administrator/js/counter/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('/assets/administrator/js/counter/jquery.counterup.min.js')}}"></script>
<script src="{{asset('/assets/administrator/js/counter/counter-custom.js')}}"></script>
<script src="{{asset('/assets/administrator/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('/assets/administrator/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('/assets/administrator/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{asset('/assets/administrator/js/owlcarousel/owl.carousel.js')}}"></script>
<script src="{{asset('/assets/administrator/js/general-widget.js')}}"></script>
<script src="{{asset('/assets/administrator/js/tooltip-init.js')}}"></script>
<script src="{{asset('/vendor/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{asset('vendor/sweet-alert-2/sweetalert2@11.js')}}"></script>
<script src="{{asset('vendor/select2/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/datetimepicker/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/datetimepicker/daterangepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/helper/main_helper.js')}}"></script>

<!-- Plugins JS Ends-->

<!-- Plugin used-->
<script src="{{asset('/vendor/rating/jquery.barrating.js')}}"></script>
<script src="{{asset('/vendor/rating/rating-script.js')}}"></script>
<script src="{{asset('/vendor/ecommerce.js')}}"></script>
<script src="{{asset('/vendor/product-list-custom.js')}}"></script>
<script src="{{asset('/vendor/script.js')}}"></script>
<script src="{{asset('/vendor/theme-customizer/customizer.js')}}"></script>

<!-- Theme js-->
<script src="{{asset('/assets/administrator/js/script.js')}}"></script>
<script src="{{asset('/assets/administrator/js/theme-customizer/customizer.js')}}"></script>

<script>

    function viewBirthOfDay() {

        const searchParams = new URLSearchParams(window.location.search)
        searchParams.set('date_of_birth', new Date().toISOString().slice(0, 10))
        window.location.search = searchParams.toString()
    }

    function isEmptyInput(id, is_alert = false, message_alert = "", is_focus = false){
        if (!$('#' + id).val().trim()) {
            if(is_alert){
                alert(message_alert)
            }

            if(is_focus){
                $('#' + id).focus()
            }

            return true
        }
        return false
    }

    function callAjaxMultipart(method = "GET", url, data, success, error, on_process = null, is_loading = true){
        $.ajax({
            type: method,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            url: url,
            beforeSend: function () {
                if(is_loading){
                    showLoading()
                }
            },
            success: function (response) {
                if(is_loading){
                    hideLoading()
                }
                success(response)
            },
            error: function (err) {
                if(is_loading){
                    hideLoading()
                }
                Swal.fire(
                    {
                        icon: 'error',
                        title: err.responseText,
                    }
                );
                error(err)
            },
            xhr:function (){
                // get the native XmlHttpRequest object
                var xhr = $.ajaxSettings.xhr() ;
                // set the onprogress event handler
                xhr.upload.onprogress = function(evt){
                    // console.log('progress', evt.loaded/evt.total*100)

                    if (on_process){
                        on_process(evt.loaded/evt.total*100)
                    }

                } ;
                // set the onload event handler
                xhr.upload.onload = function(){

                } ;
                // return the customized object

                return xhr ;
            }
        });
    }

    function callAjax(method = "GET", url, data, success, error, is_loading = true){
        $.ajax({
            type: method,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            cache: false,
            data: data,
            url: url,
            beforeSend: function () {
                if(is_loading){
                    showLoading()
                }
            },
            success: function (response) {
                if(is_loading){
                    hideLoading()
                }
                success(response)
            },
            error: function (err) {
                if(is_loading){
                    hideLoading()
                }
                Swal.fire(
                    {
                        icon: 'error',
                        title: err.responseText,
                    }
                );
                error(err)
            },
        });
    }

    function hideModal(id){
        $('#' + id).modal('hide');
    }

    function showModal(id){
        $('#' + id).modal('show');
    }

    function isCheckedInput(id){
        return $("#" + id).is(":checked") == "true" || $("#" + id).is(":checked") == true
    }

    $("input.number").maskNumber({
        integer: true
    })

</script>

@yield('js')

</body>


</html>

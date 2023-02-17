<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - {{env('APP_NAME')}}</title>

    <meta name="promotion" content="Admin - {{env('APP_NAME')}}">
    <meta name="Description" content="Admin - {{env('APP_NAME')}}">

    <meta property="og:url" content="{{env('APP_URL') . "/admin"}}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Admin - {{env('APP_NAME')}}" />
    <meta property="og:description" content="Admin - {{env('APP_NAME')}}" />
    <meta property="og:image" content="{{ env('APP_URL') . \App\Models\Helper::logoImagePath() }}" />

    <link rel="shortcut icon" href="{{ env('APP_URL') . \App\Models\Helper::logoImagePath() }}">

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #17a2b8;
            height: 100vh;
        }
        #login .container #login-row #login-column #login-box {
            margin-top: 120px;
            max-width: 600px;
            border: 1px solid #9C9C9C;
            background-color: #EAEAEA;
        }
        #login .container #login-row #login-column #login-box #login-form {
            padding: 20px;
        }
        #login .container #login-row #login-column #login-box #login-form #register-link {
            margin-top: -85px;
        }
    </style>
</head>
<body>
<div id="login">
    <h3 class="text-center text-white pt-5">Quản trị</h3>
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-6">
                <div id="login-box" class="col-md-12">
                    <form id="login-form" class="form" action="" method="post">
                        @csrf
                        <h3 class="text-center text-info">Đăng nhập</h3>
                        <div class="form-group">
                            <label for="email" class="text-info">Username:</label><br>
                            <input type="text" name="email" id="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password" class="text-info">Password:</label><br>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="form-group">
{{--                            <label for="remember-me" class="text-info"><span>Remember me</span> <span><input id="remember-me" name="remember_me" type="checkbox"></span></label><br>--}}
                            <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                        </div>

                        @if (Session::has('message'))
                            <div class="alert alert-danger text-center">
                                <p>{{ Session::get('message') }}</p>
                            </div>
                        @endif

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{--<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>--}}
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>--}}
</body>

</html>




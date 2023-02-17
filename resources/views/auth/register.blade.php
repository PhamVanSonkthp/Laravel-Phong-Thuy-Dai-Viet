@extends('layouts.app')

@section('content')
    <div class="accountbg"
         style="background: url({{asset('/assets/images/Slider-Infinity-Main-min.png')}});background-size: cover;background-position: center;z-index: -1;"></div>

    <div class="account-pages mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mt-4">
                                <div class="mb-3">
                                    <a href="{{ route('welcome.index') }}"><img
                                            src="{{asset(optional(\App\Models\Logo::first())->image_path)}}" height="150"
                                            alt="logo"></a>
                                </div>
                            </div>
                            <div class="p-3">
                                <h4 class="font-size-18 mt-2 text-center">Đăng ký</h4>

                                <form method="POST" action="{{ route('register') }}" class="form-horizontal">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="col-form-label text-md-end">Họ và tên <span class="text-danger">*</span></label>

                                        <div>
                                            <input id="name" type="text"
                                                   class="form-control @error('name') is-invalid @enderror" name="name"
                                                   value="{{ old('name') }}" required autocomplete="name" autofocus>

                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email"
                                               class="col-form-label text-md-end">Email <span class="text-danger">*</span></label>

                                        <div>
                                            <input id="email" type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   name="email" value="{{ old('email') }}" required
                                                   autocomplete="email">

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password"
                                               class="col-form-label text-md-end">Mật khẩu <span class="text-danger">*</span></label>

                                        <div>
                                            <input id="password" type="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   name="password" required autocomplete="new-password">

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password-confirm"
                                               class="col-form-label text-md-end">Nhập lại mật khẩu <span class="text-danger">*</span></label>

                                        <div>
                                            <input id="password-confirm" type="password" class="form-control"
                                                   name="password_confirmation" required autocomplete="new-password">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone"
                                               class="col-form-label text-md-end">Số điện thoại</label>

                                        <div>
                                            <input id="phone" type="text"
                                                   class="form-control @error('phone') is-invalid @enderror"
                                                   name="phone" value="{{ old('phone') }}"
                                                   autocomplete="phone">

                                            @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="user_discourd"
                                               class="col-form-label text-md-end">Username Discord</label>

                                        <div>
                                            <input id="user_discourd" type="text"
                                                   class="form-control @error('user_discourd') is-invalid @enderror"
                                                   name="user_discord" value="{{ old('user_discourd') }}"
                                                   autocomplete="user_discourd">

                                            @error('user_discourd')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="refer"
                                               class="col-form-label text-md-end">Mã giới thiệu</label>

                                        <div>
                                            <input id="refer" type="text"
                                                   class="form-control @error('refer') is-invalid @enderror"
                                                   name="refer" value="{{ old('refer') }}"
                                                   autocomplete="refer">

                                            @error('user_discourd')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="gender"
                                               class="col-form-label text-md-end">Giới tính</label>

                                        <div style="display: flex">
                                            <div class="form-check form-check-inline" style="display: flex; align-items: center;margin-right: 36px;">
                                                <input class="form-check-input" type="radio" style="margin-bottom: 6px;margin-left: 0;" name="gender" id="inlineRadio1" value="1" {{old('gender') == 1 ? 'checked' : ''}}>
                                                <label class="form-check-label" for="inlineRadio1" style="margin-bottom: 0; margin-left: 10px">Nam</label>
                                            </div>
                                            <div class="form-check form-check-inline" style="display: flex; align-items: center">
                                                <input class="form-check-input" type="radio" style="margin-bottom: 6px;margin-left: 0;" name="gender" id="inlineRadio2" value="0" {{old('gender') == 0 ? 'checked' : ''}}>
                                                <label class="form-check-label" for="inlineRadio2" style="margin-bottom: 0; margin-left: 10px">Nữ</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="formGroupDateOfBirth" class="form-label">Ngày sinh</label>
                                        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" id="formGroupDateOfBirth" >
                                        @error('date_of_birth')
                                        <div class="alert alert-danger">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="text-end">
                                            <button
                                                class="btn btn-primary w-md waves-effect waves-light button-register"
                                                type="submit">Register
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-0 row">
                                        <div class="col-12 mt-4">
                                            <div class="form-check">
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    <p class=" text-muted mb-0">Bằng cách đăng ký, bạn đồng ý với <a href="">Điều khoản thanh toán</a> của chúng tôi</p>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                    <div class="mt-5 text-center position-relative">
                        <p class="text-white">Bạn đã có tài khoản ? <a href="{{ route('login') }}"
                                                                           class="font-weight-bold text-primary">
                                Login </a></p>
                        <p class="text-white">
                            <script>document.write(new Date().getFullYear())</script>
                            © {{ config('app.name', 'Laravel') }}.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>


        $(document).ready(function () {
            $('#flexCheckDefault').change(function () {
                if (this.checked) {
                    $('.button-register').prop('disabled', false);
                } else {
                    $('.button-register').prop('disabled', true);
                }
            });
        });

    </script>

@endsection

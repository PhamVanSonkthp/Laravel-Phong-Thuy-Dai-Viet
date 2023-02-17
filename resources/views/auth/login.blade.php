@extends('layouts.app')

@section('content')
    <div class="accountbg"
         style="background: url({{asset('user/assets/images/banner2000x1333.jpg')}});background-size: cover;background-position: center;z-index: -1;left: 370px;position: fixed;"></div>

    <div class="wrapper-page account-page-full">

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="card shadow-none">
                <div class="card-block">

                    <div class="account-box">

                        <div class="card-box shadow-none p-4">
                            <div class="p-2">
                                <div class="text-center mt-4">
                                    <a href="{{ route('welcome.index') }}"><img src="{{asset(optional(\App\Models\Logo::first())->image_path)}}" height="150"
                                                              alt="logo"></a>
                                </div>

                                <h4 class="font-size-18 mt-5 text-center">Welcome To Mau Bui Finance</h4>
{{--                                <p class="text-muted text-center">Đăng nhập để tiếp tục với {{ config('app.name', 'Laravel') }}.</p>--}}

                                <form class="mt-4" action="#">

                                    <div class="mb-3">
                                        <label for="email"
                                               class="col-form-label text-md-end">Email</label>

                                        <div>
                                            <input id="email" type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   name="email" value="{{ old('email') }}" required autocomplete="email"
                                                   autofocus>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="col-form-label text-md-end">{{ __('Password') }}</label>

                                        <div>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                <label class="form-check-label" for="remember">
                                                    {{ __('Remember Me') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <div>
                                            <button type="submit" class="btn text-white" style="background-color: #D3AB56;">
                                                Đăng nhập
                                            </button>

                                            <a href="{{ route('login.provider', 'google') }}"
                                               class="btn btn-secondary">{{ __('Google Sign in') }}</a>

                                            @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    <strong class="text-dark">Bạn đã quên mật khẩu?</strong>
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                </form>

                                <p class=" text-muted mb-0 mt-2">Bằng cách đăng ký, bạn đồng ý với <a href=""><strong class="text-dark">Điều khoản thanh toán</strong></a> của chúng tôi</p>

                                <div class="mt-5 pt-4 text-center position-relative">

                                    @guest
                                    @if (Route::has('register'))
                                        <div>Bạn chưa có tài khoản ?</div>
                                            <a href="{{ route('register') }}"
                                               class=" mt-2 mb-2 btn fw-medium text-white" style="background-color: #D3AB56;"> Đăng ký ngay </a>
                                    @endif

                                    @endguest
                                    <p>
                                        <script>document.write(new Date().getFullYear())</script>
                                        © {{ config('app.name', 'Laravel') }}.
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection

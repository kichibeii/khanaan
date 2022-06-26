@extends('layouts.veera')
@section('title', 'Login & Register')

@section('content')

<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2>Login & Register</h2>
                    <nav class="page-breadcrumb">
                        <ul class="d-flex justify-content-center">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('register') }}" class="active">Login & Register</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Header Wrapper -->

<!-- Start My Account Wrapper -->
<div id="my-account-page-wrapper" class="pt-88 pt-md-58 pt-sm-48 pb-50 pb-md-20 pb-sm-10">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <!-- Start Login Area Wrapper -->
                <div class="my-account-item-wrapper">
                    <h3>Login</h3>

                    <div class="my-account-form-wrap">
                        <form method="POST" action="{{ route('login') }}" class="form-ladda">
                            @csrf
                            <div class="single-form-input">
                                <label for="username">Username <sup>*</sup></label>
                                <input type="text" id="username" name="username" required class="@error('username') is-invalid @enderror" />
                                @error('username')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="single-form-input">
                                <label for="login_pwsd">Password <sup>*</sup></label>
                                <input id="password" type="password" class=" @error('password') is-invalid @enderror" name="password" required>
                            </div>

                            <div class="single-form-input d-flex align-items-center mb-14">
                                <button class="btn btn-black" type="submit">Login</button>

                                <div class="custom-control custom-checkbox ml-20">
                                    <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="remember">{{ __('Remember Me') }}</label>
                                </div>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="lost-pswd">
                                    <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                                </div>
                            @endif

                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- End Login Area Wrapper -->
            </div>

            <div class="col-md-6">
                <!-- Start Register Area Wrapper -->
                <div class="my-account-item-wrapper mt-sm-34">
                    <h3>Register</h3>

                    <div class="my-account-form-wrap">
                        {!! Form::open([ 'route'=>['register'], 'class'=>'form-ladda' ]) !!}
                            <div class="single-form-input">
                                <label for="reg_name">Name <sup>*</sup></label>
                                <input id="reg_name" type="text" class="@error('reg_name') is-invalid @enderror" name="reg_name" value="{{ old('reg_name') }}" required autocomplete="name" >
                                @error('reg_name')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="single-form-input">
                                <label for="reg_wa">WhatsApp Number <sup>*</sup></label>
                                <input id="reg_wa" type="text" class="@error('reg_wa') is-invalid @enderror" name="reg_wa" value="{{ old('reg_wa') }}" required >
                                @error('reg_wa')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="single-form-input">
                                <label for="reg_email">E-Mail Address<sup>*</sup></label>
                                <input id="reg_email" type="email" class="@error('reg_email') is-invalid @enderror" name="reg_email" value="{{ old('reg_email') }}" required autocomplete="email" >
                                @error('reg_email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="single-form-input">
                                <label for="reg_username">Username <sup>*</sup></label>
                                <input id="reg_username" type="text" class="@error('reg_username') is-invalid @enderror" name="reg_username" value="{{ old('reg_username') }}" required autocomplete="username" >
                                @error('reg_username')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="single-form-input">
                                <label for="reg_password">Password <sup>*</sup></label>
                                <input id="reg_password" type="password" class="@error('reg_password') is-invalid @enderror" name="reg_password" required autocomplete="new-password">
                                @error('reg_password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="single-form-input mb-18">
                               <p class="mb-0">Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our
                                   <a href="#">privacy policy</a>.</p>
                            </div>

                            <div class="single-form-input">
                                <button class="btn btn-black" type="submit">Register</button>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- End Register Area Wrapper -->
            </div>
        </div>
    </div>
</div>
<!-- End My Account Wrapper -->


@endsection

@extends('layouts.veera')

@section('content')
<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2>{{ __('Reset Password') }}</h2>
                    <nav class="page-breadcrumb">
                        <ul class="d-flex justify-content-center">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('password.request') }}" class="active">{{ __('Reset Password') }}</a></li>
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
                <!-- Start Register Area Wrapper -->
                <div class="my-account-item-wrapper mt-sm-34">
                    <h3>{{ __('Reset Password') }}</h3  >
                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="my-account-form-wrap">
                        {!! Form::open([ 'route'=>['password.email'] ]) !!}

                            <div class="single-form-input">
                                <label for="email">E-Mail Address<sup>*</sup></label>
                                <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" >
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="single-form-input">
                                <button class="btn btn-black" type="submit">{{ __('Send Password Reset Link') }}</button>
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

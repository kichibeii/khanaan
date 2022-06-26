@extends('layouts.veera')

@section('content')

<!-- Start My Account Wrapper -->
<div id="my-account-page-wrapper" class="pt-88 pt-md-58 pt-sm-48 pb-50 pb-md-20 pb-sm-10">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <!-- Start Register Area Wrapper -->
                <div class="my-account-item-wrapper mt-sm-34">
                    <h3>{{ __('Reset Password') }}</h3>

                    <div class="my-account-form-wrap">
                        {!! Form::open([ 'route'=>['password.update'] ]) !!}
                        <input type="hidden" name="token" value="{{ $token }}">

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
                                <label for="password">Password <sup>*</sup></label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="single-form-input">
                                <label for="password-confirm">Password <sup>*</sup></label>
                                <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                                @error('password_confirmation')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="single-form-input">
                                <button class="btn btn-black" type="submit">{{ __('Reset Password') }}</button>
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

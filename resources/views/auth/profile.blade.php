@extends('layouts.veera')
@section('title', 'My Account')

@section('styles-after')
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
<style>
.input-control{
    padding : 10px !important;
}
.toast-message{
    font-size: 90%;
}
</style>
@endsection
@section('scripts')
<!--=== Revolution Slider Js ===-->
<script src="/assets/js/vendor/imagesloaded.pkgd.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(function () { //ready
    @if(session('success'))
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        toastr.success("{!! session('success') !!}");
    @endif

    @if(session('error'))
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        toastr.warning("{!! session('error') !!}", "{{ __('main.failed') }}");
    @endif
});

</script>
@endsection
@section('content')

<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2>My Account</h2>
                    <nav class="page-breadcrumb">
                        <ul class="d-flex justify-content-center">
                            <li><a href="{{ route('home') }}">{{ __('main.home') }}</a></li>
                            <li><a href="{{ route('profile') }}" class="active">My Account</a></li>
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
        {!! Form::open([ 'route'=>['profile'], 'enctype'=>"multipart/form-data" ]) !!}
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="{{ route(config('imagecache.route'), ['template' => 'resize-medium', 'filename' => \App\User::getImage(Auth::user()) ]) }}" class="rounded-circle border" alt="" height="150">
                        {!! Form::file('reg_file', ['class'=>'form-control-file mt-3'.( $errors->has('file') ? ' form-control-danger' : '' ) ]); !!}
                        @error('reg_file')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <div class="single-form-input">
                            <label for="reg_name">{{ __('main.name') }} <sup>*</sup></label>
                            <input id="reg_name" type="text" class="input-control @error('reg_name') is-invalid @enderror" name="reg_name" value="{{ $user->name }}" required autocomplete="name" >
                            @error('reg_name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="single-form-input">
                            <label for="reg_wa">{{ __('main.wa_no') }} <sup>*</sup></label>
                            <input id="reg_wa" type="text" class="input-control @error('reg_wa') is-invalid @enderror" name="reg_wa" value="{{ $user->wa_number }}" required >
                            @error('reg_wa')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="single-form-input">
                            <label for="reg_email">{{ __('main.email_address') }}<sup>*</sup></label>
                            <input id="reg_email" type="email" class="input-control @error('reg_email') is-invalid @enderror" name="reg_email" value="{{ $user->email }}" required autocomplete="email" >
                            @error('reg_email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="single-form-input">
                            <label for="reg_username">{{ __('main.username') }} <sup>*</sup></label>
                            <input id="reg_username" type="text" class="input-control @error('reg_username') is-invalid @enderror" name="reg_username" value="{{ $user->username }}" required autocomplete="username" >
                            @error('reg_username')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="single-form-input">
                            <label for="reg_password">{{ __('main.password') }}</label>
                            <input id="reg_password" type="password" class="form-control @error('reg_password') is-invalid @enderror" name="reg_password" placeholder="{{ __('main.fill_in_to_change_the_password') }}" autocomplete="new-password">
                            @error('reg_password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="single-form-input">
                            <button class="btn btn-black" type="submit">{{ __('main.save') }}</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<!-- End My Account Wrapper -->


@endsection

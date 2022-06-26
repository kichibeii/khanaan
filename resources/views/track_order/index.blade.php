@extends('layouts.veera')
@section('title', 'My Account')
@section('scripts')
<script src="/assets/js/trak-order-custom.js"></script>
@endsection
@section('content')

<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2>{{ $utils['title'] }}</h2>
                    <nav class="page-breadcrumb">
                        <ul class="d-flex justify-content-center">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ $utils['route'] }}" class="active">{{ $utils['title'] }}</a></li>
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
        {{ __('main.track_order_inform',['domain' => env('APP_BASE_DOMAIN')]) }}
        <div class="row">
            <div class="col-md-8">
                <div class="my-account-form-wrap">
                    <div class="single-form-input mt-3">
                        <label for="invoice_number">{{ __('main.order_id') }}<sup>*</sup></label>
                        <input id="invoice_number" type="text" name="invoice_number" required >
                        <span class="invalid-feedback d-block" role="alert"></span>
                    </div>
                    <div class="single-form-input">
                        <button class="btn btn-black btn-submit" data-title="{{ __('main.submit') }}" type="button">{{ __('main.submit') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="dt-detail mt-5">
            
        </div>
    </div>
</div>
<!-- End My Account Wrapper -->

@endsection
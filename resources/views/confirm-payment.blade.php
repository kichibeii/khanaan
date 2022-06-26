@extends('layouts.veera')

@section('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="/assets/js/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="/admin/plugins/plentz-jquery-maskmoney/dist/jquery.maskMoney.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

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

    @if(session('errors'))
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

        @foreach (Session::get('data') as $k => $error)
        toastr.error("{!! $error !!}", "{{ __('main.failed') }}");
        @endforeach
    @endif

    $('.is_money').maskMoney({
        thousands: '.',
        decimal: ',',
        precision: 0
    });

    $('.kt_datepicker').datepicker({
        todayHighlight: true,
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
});

</script>
@endsection

@section('styles-after')
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
<link href="/assets/js/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/js/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />

<style>
    .toast-message{
        font-size: 90%;
    }
    .select2 {
width:100%!important;
}
</style>
@endsection

@section('content')
<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2>{{ __('main.confirm_payment') }}</h2>
                    <nav class="page-breadcrumb">
                        <ul class="d-flex justify-content-center">
                            <li><a href="{{ route('home') }}">{{ __('main.home') }}</a></li>
                            <li><a href="{{ route('password.request') }}" class="active">{{ __('main.confirm_payment') }}</a></li>
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
            <div class="col-md-8">
                <!-- Start Register Area Wrapper -->
                <div class="my-account-item-wrapper mt-sm-34">
                    <h3>{{ __('main.confirm_payment') }}</h3>
                    <p>{{ __('main.payment_notif1') }}</p>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="my-account-form-wrap">
                        {!! Form::open([ 'route'=>['store-confirm-payment'] ]) !!}

                            <div class="single-form-input">
                                <label for="invoice_number">{{ __('main.order_id') }}<sup>*</sup></label>
                                <input id="invoice_number" type="text" class="@error('invoice_number') is-invalid @enderror" name="invoice_number" value="{{ old('invoice_number') }}" required >
                                @error('invoice_number')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="single-form-input">
                                <label for="bank_id">{{ __('main.transfer_to') }}<sup>*</sup></label>
                                {!!Form::select('bank_id', $utils['banks'], old('bank_id'), ['id'=>'bank_id', 'class' => 'form-control kt-select2'])!!}
                                @error('bank_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="single-form-input">
                                <label for="amount_transfer">{{ __('main.the_amount_of_money_transferred') }}<sup>*</sup></label>
                                <input id="amount_transfer" type="text" class="text-right is_money @error('amount_transfer') is-invalid @enderror" name="amount_transfer" value="{{ old('amount_transfer') }}" required >
                                @error('amount_transfer')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="single-form-input">
                                <label for="transfer_date">{{ __('main.transfer_date') }}<sup>*</sup></label>
                                <input id="transfer_date" type="text" class="kt_datepicker @error('transfer_date') is-invalid @enderror" name="transfer_date" value="{{ old('transfer_date') }}" required >
                                @error('transfer_date')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="single-form-input">
                                <button class="btn btn-black" type="submit">{{ __('main.submit') }}</button>
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

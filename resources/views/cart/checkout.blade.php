@extends('layouts.veera')
@section('title', __('main.checkout'))
@section('menuActive', 'cart')
@section('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="/assets/js/select2/js/select2.full.min.js" type="text/javascript"></script>


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
});

</script>
@endsection

@section('styles-after')
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
<link href="/assets/js/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/js/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
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
@php
$idr = 1;
$currentCurrency = currentCurrency();
if ($currentCurrency == 'usd'){
    $idr = \App\Setting::getValue('dollar');
}
$voucher = currentVoucher();
@endphp

<input type="hidden" id="idr" value="{{ $idr }}">
<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2>{{ __('main.checkout') }}</h2>
                    <nav class="page-breadcrumb">
                        <ul class="d-flex justify-content-center">
                            <li><a href="{{ route('home') }}">{{ __('main.home') }}</a></li>
                            <li><a href="{{ route('checkout') }}" class="active">{{ __('main.checkout') }}</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Header Wrapper -->

<!--== Start Checkout Page Wrapper ==-->
<div id="checkout-page-wrapper" class="pt-90 pt-md-60 pt-sm-50 pb-50 pb-md-20 pb-sm-10">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="checkout-page-coupon-area">
                    <!-- Checkout Coupon Accordion Start -->
                    <div class="checkoutAccordion" id="checkOutAccordion">
                        <div class="card">
                            <h3><i class="fa fa-info-circle"></i> {{ __('main.do_you_have_a_voucher') }} <span data-toggle="collapse"
                                                                                       data-target="#couponaccordion">{{ __('main.c_ick_here_to_fill_in_the_voucher_code') }}</span>
                            </h3>
                            <div id="couponaccordion" class="collapse" data-parent="#checkOutAccordion">
                                <div class="card-body">
                                    <div class="apply-coupon-wrapper">
                                        <p>{{ __('main.if_have_voucher_enter_here') }}</p>
                                        {!! Form::open([ 'route'=>['cart.voucher-update'] ]) !!}
                                            <input type="hidden" name="action" value="2">
                                            <input type="text" name="voucher" placeholder="{{ __('main.fill_in_your_voucher_code') }}" required value="{{ $cartData ? $voucher['voucher_code'] : '' }}"/>
                                            <button class="btn btn-black" type="submit">{{ __('main.activate_the_voucher') }}</button>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Checkout Coupon Accordion End -->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <!-- Checkout Form Area Start -->
                <div class="checkout-billing-details-wrap">
                    <h2>{{ __('main.data_shipping_address') }}</h2>
                    <div class="billing-form-wrap">


                        @if ($billingSelected)
                        <p>{{ __('main.your_order_will_be_sent_to') }}:</p>
                        <div class="card">
                            <div class="card-body">
                                <h3>{{ $billingSelected->name }}</h3>
                                <p>{{ $billingSelected->address }}
                                    @if (is_null($billingSelected->country_id))
                                    <br>{{ $billingSelected->subdistrict_name}}
                                    {{ $billingSelected->type_name}} {{ $billingSelected->city_name}} {{ $billingSelected->postcode}}
                                    <br>{{ $billingSelected->province_name }}
                                    @else
                                    <br>{{ $billingSelected->postcode}}
                                    @endif
                                    <br>{{ $billingSelected->country_name}}
                                    <br>{{ $billingSelected->handphone}}
                                </p>
                            </div>
                        </div>
                        <br>
                        @endif

                        @if (count($billings) > 1)
                        {{ __('main.select_other_shipping_address') }}:
                        <select name="biling_id" id="biling_id" class="nice-select">
                            @foreach ($billings as $billing)
                            <option value="{{ $billing->id }}" {{ $billing->id == $billingSelected->id ? 'selected' : '' }}>{{ $billing->name }}</option>
                            @endforeach
                        </select>
                        <br><br>
                        @endif

                        @if (auth()->check())
                        <h2>{{ $billingSelected ? __('main.or').' ' : '' }}{{ __('main.add_a_new_shipping_address') }}</h2>
                        @endif
                        @if (auth()->check())
                        {!! Form::open([ 'route'=>['billing.store'] ]) !!}
                        @endif
                            <div class="single-input-item">
                                <label for="name" class="required">{{ __('main.full_name') }}</label>
                                <input type="text" id="name" name="name" placeholder="{{ __('main.full_name') }}" required value="" />
                            </div>

                            <div class="single-input-item">
                                <label for="country_id" class="required">{{ __('main.country') }}</label>
                                <select name="country_id" id="country_id" class="form-control kt-select2">
                                    <option value="0" selected>Indonesia</option>
                                </select>
                                @if ($errors->has('country_id')) <span class="form-text kt-font-danger"> {{ $errors->first('country_id') }} </span>@endif
                            </div>

                            <div id="block-indo">
                                <div class="single-input-item">
                                    <label for="province_id" class="required">{{ __('main.province') }}</label>
                                    {!!Form::select('province_id', [], old('province_id'), ['id'=>'province_id', 'class' => 'form-control kt-select2'])!!}
                                    @if ($errors->has('province_id')) <span class="form-text kt-font-danger"> {{ $errors->first('province_id') }} </span>@endif
                                </div>

                                <div class="single-input-item">
                                    <label for="province" class="required">{{ __('main.city') }}</label>
                                    {!!Form::select('city_id', [], old('city_id'), ['id'=>'city_id', 'class' => 'form-control kt-select2'])!!}
                                </div>

                                <div class="single-input-item">
                                    <label for="subdistrict_id" class="required">{{ __('main.sub_district') }}</label>
                                    {!!Form::select('subdistrict_id', [], old('subdistrict_id'), ['id'=>'subdistrict_id', 'class' => 'form-control kt-select2'])!!}
                                </div>
                            </div>

                            <div class="single-input-item">
                                <label for="address" class="required">{{ __('main.address') }}</label>
                                <input type="text" id="address" name="address" value="" placeholder="{{ __('main.address') }}" required />
                            </div>

                            <div class="single-input-item">
                                <label for="postcode" class="required">{{ __('main.postal_code') }}</label>
                                <input type="text" name="postcode" id="postcode"  placeholder="{{ __('main.postal_code') }}" required maxlength="6" />
                            </div>

                            @if (!auth()->check())
                            <div class="single-input-item">
                                <label for="email" class="required">{{ __('main.email_address') }}</label>
                                <input type="email" name="email" id="email"  placeholder="{{ __('main.email_address') }}" required />
                            </div>
                            @endif

                            <div class="single-input-item">
                                <label for="handphone" class="required">{{ __('main.phone_number') }}</label>
                                <input type="text" name="handphone" id="handphone"  placeholder="{{ __('main.phone_number') }}" required maxlength="20"/>
                            </div>

                            @if (auth()->check())
                            <div class="checkout-box-wrap">
                                <div class="single-input-item">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="is_main" id="is_main">
                                        <label class="custom-control-label" for="is_main">{{ __('main.make_it_my_primary_shipping_address') }}</label>
                                    </div>
                                </div>
                            </div>


                            <button class="btn btn-full btn-warning mt-26">{{ __('main.save_shipping_address') }}</button>
                            @endif

                        @if (auth()->check())
                        {!! Form::close() !!}
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xl-5 ml-auto">
                <!-- Checkout Page Order Details -->
                {!! Form::open([ 'route'=>['checkout'], 'id' => 'form-checkout' ]) !!}
                @if (!auth()->check())
                <input type="hidden" name="name_guest" id="name_guest">
                <input type="hidden" name="country_id_guest" id="country_id_guest">
                <input type="hidden" name="province_id_guest" id="province_id_guest">
                <input type="hidden" name="city_id_guest" id="city_id_guest">
                <input type="hidden" name="subdistrict_id_guest" id="subdistrict_id_guest">
                <input type="hidden" name="address_guest" id="address_guest">
                <input type="hidden" name="postcode_guest" id="postcode_guest">
                <input type="hidden" name="handphone_guest" id="handphone_guest">
                <input type="hidden" name="email_guest" id="email_guest">
                @endif
                <div class="order-details-area-wrap">
                    <h2>{{ __('main.your_order') }}</h2>

                    <div class="order-details-table table-responsive">

                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>{{ __('main.product') }}</th>
                                    <th>{{ __('main.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $currentDateTime = \Carbon\Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s");
                                $arrSizes = App\Dropdown::getOptions('size');
                                $arrColors = App\Color::whereStatus(1)->pluck('title', 'id')->toArray();
                                $subTotal = 0;
                                $totalWeight = 0;
                                @endphp

                                @foreach (Cart::content() as $id => $cart)
                                @php
                                $product = App\Product::selectRaw("id, weight, code, title, slug, image, image_second, price, description, qty,
                                            (SELECT price FROM ".getTableName(with(new App\ProductDiscount)->getTable())." pd WHERE pd.product_id = ".getTableName(with(new App\Product)->getTable()).".id AND ((pd.date_start IS NULL OR pd.date_start <= '".$currentDateTime."') AND (pd.date_end IS NULL OR pd.date_end >= '".$currentDateTime."')) ORDER BY pd.priority ASC LIMIT 1 ) AS discount
                                            ")
                                            ->where('status',1)
                                            ->where('published_on','<=',$currentDateTime)
                                            ->where('id',$cart->id)
                                            ->first();
                                @endphp
                                @if ($product)
                                @php
                                $price = $product->price;
                                if ($product->discount > 0){
                                    $price = $product->discount;
                                }
                                $priceText = getPrice($currentCurrency, $idr, $price);

                                $total = $price * $cart->qty;
                                $subTotal = $subTotal + $total;
                                $subTotalText = getPrice($currentCurrency, $idr, $subTotal);

                                $totalWeight = $totalWeight + ($cart->qty * $product->weight);
                                @endphp
                                <tr class="cart-item">
                                    <td><span class="product-title">{{ $product->title  }} ({{ $arrColors[$cart->options->color] }} {{ $arrSizes[$cart->options->size] }})</span> <span class="product-quantity">&#215;  {{ $cart->qty }}</span></td>
                                    <td>{{ displayPrice($currentCurrency, $priceText) }}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="cart-subtotal">
                                    <th>{{ __('main.subtotal') }}</th>
                                    <td align="right">{{ displayPrice($currentCurrency, $subTotalText) }}</td>
                                </tr>
                                @php
                                $voucher_nominal = 0;
                                @endphp
                                @if ($cartData && $voucher['voucher_id'] != '')
                                @php
                                $voucher_nominal = $cartData ? $voucher['voucher_nominal'] : '';
                                $voucher_nominalText = getPrice($currentCurrency, $idr, $voucher_nominal);
                                @endphp
                                <tr class="cart-sub-total">
                                    <th>{{ __('main.voucher') }}</th>
                                    <td align="right">-{{ displayPrice($currentCurrency, $voucher_nominalText) }}</td>
                                </tr>
                                @endif
                                <tr class="shipping">
                                    <input type="hidden" id="val_sub_total" name="val_sub_total" value="{{ $subTotal }}">
                                    <input type="hidden" id="val_total_voucher" name="val_total_shipping_charge" value="{{ $voucher_nominal }}">
                                    <input type="hidden" id="val_total_shipping_charge" name="val_total_shipping_charge" value="">
                                    <input type="hidden" id="weight" name="weight" value="{{ $totalWeight }}">
                                    <input type="hidden" id="billing" name="billing" value="{{ $billingSelected ? $billingSelected->id : 0 }}">
                                    <th>{{ __('main.shipping_options') }}</th>
                                    <td>

                                    </td>
                                </tr>
                                <tr class="shipping">
                                    <td colspan="2">
                                        {!!Form::select('courier', [], old('courier'), ['id' => 'courier', 'required', 'class' => 'form-control kt-select2'])!!}
                                    </td>
                                </tr>
                                <tr class="final-total">
                                    @php
                                    $total = $subTotal - $voucher_nominal;
                                    $totalText = getPrice($currentCurrency, $idr, $total);
                                    @endphp
                                    <th>{{ __('main.total') }}</th>
                                    <td><span class="total-amount">{{ displayPrice($currentCurrency, $totalText) }}</span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="order-details-footer">
                        <div class="single-input-item">
                            <label for="ordernote">{{ __('main.order_notes') }}</label>
                            <textarea name="ordernote" id="ordernote" cols="30" rows="3" placeholder="{{ __('main.order_notes_example') }}"></textarea>
                        </div>

                        <div class="custom-control custom-checkbox mt-10">
                            <input type="checkbox" id="privacy" class="custom-control-input" required />
                            <label for="privacy" class="custom-control-label">{{ __('main.term_condition_accept') }}</label>
                        </div>

                        <button class="btn btn-full btn-black mt-26" type="submit">{{ __('main.complete_order') }}</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>
</div>
<!--== End Checkout Page Wrapper ==-->


@endsection

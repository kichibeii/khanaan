@extends('layouts.veera')
@section('title', __('main.cart'))
@section('menuActive', 'cart')
@section('scripts')
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
<style>
    .toast-message{
        font-size: 90%;
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

<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2>{{ __('main.cart') }}</h2>
                    <nav class="page-breadcrumb">
                        <ul class="d-flex justify-content-center">
                            <li><a href="{{ route('home') }}">{{ __('main.home') }}</a></li>
                            <li><a href="{{ route('cart') }}" class="active">{{ __('main.cart') }}</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Header Wrapper -->

<!--== Start Cart Page Wrapper ==-->
<div id="cart-page-wrapper" class="pt-86 pt-md-56 pt-sm-46 pb-50 pb-md-20 pb-sm-10">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="shopping-cart-list-area">
                    <div class="shopping-cart-table table-responsive">
                        @if (Cart::count() > 0)
                        {!! Form::open([ 'route'=>['cart.update'] ]) !!}
                        <input type="hidden" name="action" value="1">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>{{ __('main.product') }}</th>
                                    <th>{{ __('main.price') }}</th>
                                    <th>{{ __('main.qty') }}</th>
                                    <th>{{ __('main.total') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                    <input type="hidden" id="quantity" data-max="10" />
                                    @php
                                    $currentDateTime = \Carbon\Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s");
                                    $arrSizes = App\Dropdown::getOptions('size');
                                    $arrColors = App\Color::whereStatus(1)->pluck('title', 'id')->toArray();
                                    $subTotal = 0;
                                    @endphp

                                    @foreach (Cart::content() as $id => $cart)
                                    @php
                                    $product = App\Product::selectRaw("id, code, title, slug, image, image_second, price, description, qty,
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
                                    $price = getPrice($currentCurrency, $idr, $price);

                                    $productImage = $product->images()->where('color_id', $cart->options->color)->orderBy('sort_order', 'ASC')->first();

                                    $total = $price * $cart->qty;
                                    $subTotal = $subTotal + $total;
                                    @endphp
                                    <tr>
                                        <td class="product-list">
                                            <div class="cart-product-item d-flex align-items-center">
                                                <div class="remove-icon">
                                                    <a href="{{ route('cart.destroy', $id) }}"><i class="fa fa-trash-o"></i></a>
                                                </div>
                                                <a href="single-product-sticky.html" class="product-thumb">
                                                    <img src="{{ route(config('imagecache.route'), ['template' => 'product-list', 'filename' => $product->id.'/'.$productImage->image ]) }}" alt="{{ $product->title }}"/>
                                                </a>
                                                <a href="{{ route('shop.show', $product->slug) }}" class="product-name">
                                                    <strong>{{ $product->title  }}</strong>
                                                <br>{{ __('main.color') }}: {{ $arrColors[$cart->options->color] }}
                                                <br>{{ __('main.size') }}: {{ $arrSizes[$cart->options->size] }}
                                                </a>

                                            </div>
                                        </td>
                                        <td>
                                            <span class="price">{{ displayPrice($currentCurrency, $price) }}</span>
                                        </td>
                                        <td>
                                            <div class="pro-qty">
                                                <input type="text" class="quantity cart-qty" data-max="10" data-id="{{ $id }}" name="qty[{{ $id }}]" value="{{ $cart->qty }}"/>
                                            </div>
                                            <input type="hidden" class="price" id="price-{{ $id }}" value="{{ $price }}">
                                        </td>
                                        <td>
                                            <span class="price" data-id="{{ $id }}">{{ displayPrice($currentCurrency, ($price * $cart->qty)) }}</span>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach


                            </tbody>
                        </table>
                        @else
                        {{ __('main.empty_cart') }}
                        <br><br><br><br><br><br><br><br>
                        @endif
                    </div>

                    @if (Cart::count() > 0)
                    <div class="cart-coupon-update-area d-sm-flex justify-content-between align-items-center">
                        <div class="coupon-form-wrap">
                            <input type="text" name="voucher" placeholder="{{ __('main.enter_the_voucher_code_here') }}" value="{{ $cartData && $voucher['voucher_id'] != '' ? $voucher['voucher_code'] : '' }}"/>
                        </div>

                        <div class="cart-update-buttons mt-xs-14">
                            <button class="btn-update-cart" type="submit">{{ __('main.update_cart_voucher') }}</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                @if (Cart::count() > 0)
                <!-- Cart Calculate Area -->
                <div class="cart-calculate-area mt-sm-30 mt-md-30">
                    <h5 class="cal-title">{{ __('main.shopping_cart_total') }}</h5>

                    <div class="cart-cal-table table-responsive">
                        <table class="table table-borderless">
                            <tr class="cart-sub-total">
                                <th>{{ __('main.subtotal') }}</th>
                                <td align="right">{{ displayPrice($currentCurrency, $subTotal) }}</td>
                            </tr>
                            @php

                            $voucher_nominal = 0;
                            @endphp
                            @if ($cartData && $voucher['voucher_id'] != '')
                            @php
                            $voucher_nominal = getPrice($currentCurrency, $idr, $voucher['voucher_nominal']);
                            @endphp
                            <tr class="cart-sub-total">
                                <th>{{ __('main.voucher') }}</th>
                                <td align="right">-{{ displayPrice($currentCurrency, $voucher_nominal) }}</td>
                            </tr>
                            @endif
                            <tr class="order-total">
                                <th>{{ __('main.total') }}</th>
                                <td align="right"><b>{{ displayPrice($currentCurrency, ($subTotal - $voucher_nominal)) }}</b></td>
                            </tr>
                        </table>
                    </div>

                    <div class="proceed-checkout-btn">
                        <a href="{{ route('checkout') }}" class="btn btn-full btn-black">{{ __('main.proceed_to_checkout') }}</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!--== End Cart Page Wrapper ==-->


@endsection

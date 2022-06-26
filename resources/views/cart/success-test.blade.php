@extends('layouts.veera')
@section('title', 'Order Sukses')
@section('menuActive', 'cart')
@section('scripts')
@endsection

@section('styles-after')
<style>
    .order-details-area-wrap .order-details-table .table tbody .cart-item:first-child td {
        padding-top: 10px!important;
    }

    .info-bank td {
        padding: 15px;
    }

    .order-details-area-wrap h2 {
        margin-bottom: 15px!important;
    }

    .order-details-area-wrap .order-details-table .table tr td, .order-details-area-wrap .order-details-table .table tr th {
        padding: 10px 0!important;
    }

    .order-details-area-wrap {
        margin-top: 0px!important;
        padding: 30px;
        min-height: 256px;
    }

    .order-details-area-wrap .order-details-table .table tbody, .order-details-area-wrap .order-details-table .table tfoot tr {
        border-bottom: none;
    }

    .order-details-table.table-responsive p{
        line-height: 1.5em;
        color: #000;
        margin-top: 8px;
    }

    .info-bank{
        font-size: 1.5rem
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

$billingSelected = $invoice->billing;
@endphp

<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2><i class="fa fa-check text-success" aria-hidden="true"></i>
                         {{ __('main.thanks_order') }}</h2>
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
            <div class="col-lg-6 col-xl-6">
                <div class="order-details-area-wrap">
                    <h2>{{ __('main.summary') }}</h2>
                    <div class="order-details-table table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr class="cart-item">
                                    <td><span class="product-title">{{ __('main.order_number') }}</span></td>
                                    <td class="text-success font-weight-bold">#{{ $invoice->invoice_number }}</td>
                                </tr>
                                <tr class="cart-item">
                                    <td><span class="product-title">{{ __('main.order_date') }}</span></td>
                                    <td>{{ date('d/m/Y H:i', strtotime($invoice->invoice_date)) }}</td>
                                </tr>
                                <tr class="cart-item">
                                    <td><span class="product-title">{{ __('main.order_due_date') }}</span></td>
                                    <td>{{ date('d/m/Y H:i', strtotime($invoice->invoice_due_date)) }}</td>
                                </tr>
                                <tr class="cart-item">
                                    <td><span class="product-title">{{ __('main.order_total') }}</span></td>
                                    @php
                                    $grand_total = getPrice($currentCurrency, $idr, $invoice->grand_total);
                                    @endphp
                                    <td class="font-weight-bold">{{ displayPrice($currentCurrency, $grand_total) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xl-6">
                <div class="order-details-area-wrap">
                    <h2>{{ __('main.shipping_address') }}</h2>
                    <div class="order-details-table table-responsive">
                        <p>
                                    <strong>{{ $billingSelected->name }}</strong><br>
                                    {{ $billingSelected->address }}
                                    @if ($invoice->language == 'id')
                                    @if (is_null($invoice->destination_country_id))
                                    <br>{{ $billingSelected->subdistrict_name}}
                                    <br>{{ $billingSelected->type_name}} {{ $billingSelected->city_name}} {{ $billingSelected->postcode }}
                                    <br>{{ $billingSelected->province_name}}, {{ $billingSelected->country_name }}
                                    @endif
                                    <br>{{ $billingSelected->country_name }}
                                    <br>Kode Pos {{ $billingSelected->postcode }}
                                    <br>{{ $billingSelected->handphone}}
                                    @else
                                    @if (is_null($invoice->destination_country_id))
                                    <br>{{ $billingSelected->subdistrict_name}}
                                    <br>{{ $billingSelected->type_name}} {{ $billingSelected->city_name}} {{ $billingSelected->postcode }}
                                    <br>{{ $billingSelected->province_name}}, {{ $billingSelected->country_name }}
                                    @endif
                                    <br>{{ $billingSelected->handphone}}
                                    @endif
                                    <br>
                                </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Checkout Page Wrapper ==-->

<div id="cart-page-wrapper" class="pt-md-56 pt-sm-46 pb-50 pb-md-20 pb-sm-10">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="shopping-cart-list-area">
                    <div class="shopping-cart-table table-responsive">

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
                                    @php
                                    $currentDateTime = \Carbon\Carbon::now('Asia/Jakarta')->format("Y-m-d H:i:s");
                                    $arrSizes = App\Dropdown::getOptions('size');
                                    $arrColors = App\Color::whereStatus(1)->pluck('title', 'id')->toArray();
                                    $subTotal = 0;
                                    @endphp

                                    @foreach ($invoice->products as $product)
                                    @php
                                    $productData = $product->product;
                                    $price = $product->price;
                                    if ($product->discount > 0){
                                        $price = $product->discount;
                                    }
                                    $price = getPrice($currentCurrency, $idr, $price);

                                    $productImage = $productData->images()->where('color_id', $product->color_id)->orderBy('sort_order', 'ASC')->first();

                                    $total = $price * $product->qty;
                                    $subTotal = $subTotal + $total;
                                    @endphp
                                    <tr>
                                        <td class="product-list">
                                            <div class="cart-product-item d-flex align-items-center">
                                                <a href="single-product-sticky.html" class="product-thumb">
                                                    <img src="{{ route(config('imagecache.route'), ['template' => 'product-list', 'filename' => $productData->id.'/'.$productImage->image ]) }}" alt="{{ $productData->title }}"/>
                                                </a>
                                                <a href="{{ route('shop.show', $product->slug) }}" class="product-name">
                                                    <strong>{{ $productData->title  }}</strong>
                                                <br>{{ __('main.color') }}: {{ $arrColors[$product->color_id] }}
                                                <br>{{ __('main.size') }}: {{ $arrSizes[$product->size_id] }}
                                                </a>

                                            </div>
                                        </td>
                                        <td>
                                            <span class="price">{{ displayPrice($currentCurrency, $price) }}</span>
                                        </td>
                                        <td>
                                            <span class="price">{{ $product->qty }}</span>
                                        </td>
                                        <td>
                                            <span class="price">{{ displayPrice($currentCurrency, ($price * $product->qty)) }}</span>
                                        </td>
                                    </tr>
                                    @endforeach


                            </tbody>
                        </table>
                    </div>


                </div>
            </div>

            <div class="col-lg-4">
                <!-- Cart Calculate Area -->
                <div class="cart-calculate-area mt-sm-30 mt-md-30">
                    <h5 class="cal-title">{{ __('main.shopping_cart_total') }}</h5>

                    <div class="cart-cal-table ">
                        <table class="table table-borderless">
                            <tr class="cart-sub-total">
                                <th>{{ __('main.subtotal') }}</th>
                                <td align="right">{{ displayPrice($currentCurrency, $subTotal) }}</td>
                            </tr>
                            @php
                            $voucher_nominal = 0;
                            $delivery_cost = getPrice($currentCurrency, $idr, $invoice->total_shipping_charge);
                            $unique_code = getPrice($currentCurrency, $idr, $invoice->unique_code);
                            @endphp
                            @if (!is_null($invoice->voucher_id))
                            @php
                            $voucher_nominal = getPrice($currentCurrency, $idr, $invoice->voucher_nominal);
                            @endphp

                            <tr class="cart-sub-total">
                                <th>{{ __('main.voucher') }}</th>
                                <td align="right">-{{ displayPrice($currentCurrency, $voucher_nominal) }}</td>
                            </tr>
                            @endif
                            <tr class="cart-sub-total">
                                <th>{{ __('main.delivery_cost') }}</th>
                                <td align="right">{{ displayPrice($currentCurrency, $delivery_cost) }}</td>
                            </tr>
                            <tr class="cart-sub-total d-none">
                                <th>{{ __('main.unique_code') }}</th>
                                <td align="right">{{ displayPrice($currentCurrency, $unique_code) }}</td>
                            </tr>
                            <tr class="order-total">
                                <th>{{ __('main.total_amount') }}</th>
                                <td align="right"><b>{{ displayPrice($currentCurrency, $grand_total) }}</b></td>
                            </tr>
                        </table>


                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Cart Page Wrapper ==-->

<div id="cart-page-wrapper" class="pt-md-56 pt-sm-46 pb-50 pb-md-20 pb-sm-10">
    <div class="container">
        <div class="row">

            @php
            $banks = \App\Bank::whereStatus(1)->get();
            @endphp
            <div class="col-lg-12 ">
                <div class="bg-light pt-20 pb-20 pl-20 pr-20 info-bank">
                    <p>{{ __('main.please_transfer') }}:</p>

                <table>

                @foreach ($banks as $bank)
                <tr>
                    <td><img src="{{ route(config('imagecache.route'), ['template' => 'resize-medium', 'filename' => \App\Bank::getImage($bank) ]) }}" alt=""></td>
                    <td>
                        <strong>{{ $bank->name }}</strong><br>
                        {{ $bank->account_number }}<br> {{ $bank->owner_name }} <br><br>
                    </td>
                </tr>
                @endforeach
                </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

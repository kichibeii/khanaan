@extends('layouts.admin.dashboard')
@section('title', $title)
@section('icon', $icon)

@section('styles-after')
<link rel="stylesheet" href="/css/admin/show.css">
<style>
.kt-invoice-2 .kt-invoice__body table tbody tr td div{
    font-weight: normal;
    font-size: 1rem;
}
</style>
@endsection

@section('styles-before')
<link href="/css/admin/invoice.css" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
<script src="/admin/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="/admin/plugins/plentz-jquery-maskmoney/dist/jquery.maskMoney.min.js" type="text/javascript"></script>
<script src="/js/admin/{{ $controller }}/add.js" type="text/javascript"></script>
@endsection

@section('content-subheader-toolbar')
<div class="kt-subheader__breadcrumbs">
    <a href="/" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route($controller.'.index') }}" class="kt-subheader__breadcrumbs-link">
        {{ $title }}
    </a>

    <span class="kt-subheader__breadcrumbs-separator"></span>
    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">{{ $utils['action'] }}</span>
</div>
@endsection

@section('content-dashboard')
@php
    $idr = $order->idr_rate;
    $currentCurrency = $order->currency;
@endphp

<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg d-print-none">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                {{ $utils['action'] }}
            </h3>
        </div>
    </div>

    <div class="kt-portlet__body">
        <div class="kt-invoice-2">
            <div class="kt-invoice__head">
                <div class="kt-invoice__container">
                    <div class="kt-invoice__brand">
                        <h1 class="kt-invoice__title text-uppercase">{!! $title !!}</h1>
                        <div href="#" class="kt-invoice__logo">
                            <span class="kt-invoice__desc">
                                <strong>Dikirim ke:</strong>
                                <span>{{ $utils['billing']->name }}</span>
                                <span>{{ $utils['billing']->address }}</span>
                                @if ($order->language == 'id')
                                @if (is_null($order->destination_country_id))
                                <span>{{ $utils['billing']->subdistrict_name}}
                                {{ $utils['billing']->type_name}} {{ $utils['billing']->city_name}} {{ $utils['billing']->postcode }}</span>
                                <span>{{ $utils['billing']->province_name}}</span>
                                @else
                                <span>{{ $utils['billing']->postcode }}</span>
                                @endif
                                <span>{{ $utils['billing']->country_name }}</span>
                                <span>{{ $utils['billing']->handphone}}</span>
                                @else
                                @if (is_null($order->destination_country_id))
                                <span>{{ $utils['billing']->subdistrict_name}}
                                {{ $utils['billing']->type_name}} {{ $utils['billing']->city_name}} {{ $utils['billing']->postcode }}</span>
                                <span>{{ $utils['billing']->province_name}}</span>
                                @else
                                <span>{{ $utils['billing']->postcode }}</span>
                                @endif
                                <span>{{ $utils['billing']->country_name }}</span>
                                <span>{{ $utils['billing']->handphone}}</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="kt-invoice__items">
                        <div class="kt-invoice__item">
                            <span class="kt-invoice__subtitle">TANGGAL INVOICE.</span>
                            <span class="kt-invoice__text">{{ dateFormat($order->invoice_date) }}</span>
                        </div>
                        <div class="kt-invoice__item">
                            <span class="kt-invoice__subtitle">NOMOR INVOICE.</span>
                            <span class="kt-invoice__text">{{ $order->invoice_number }}</span>
                        </div>
                        @if (!is_null($order->user_id))
                        <div class="kt-invoice__item">
                            <span class="kt-invoice__subtitle">NAMA MEMBER</span>
                            <span class="kt-invoice__text">{{ $utils['member']->name }}</span>
                        </div>
                        @endif
                        <div class="kt-invoice__item">
                            <span class="kt-invoice__subtitle">STATUS</span>
                            <span class="kt-invoice__text">{{ arrStatusPayment()[$order->status_payment] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-invoice__body">
                <div class="kt-invoice__container">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th style="text-align:left!important;">PRODUK</th>
                                    <th>QTY</th>
                                    <th>HARGA</th>
                                    <th>JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($order->products))
                                    @foreach ($order->products as $product)
                                    @php
                                    $price = getPrice($currentCurrency, $idr, $product->price);
                                    $total = $price * $product->qty;
                                    $productImage = $product->product->images()->where('color_id', $product->color_id)->orderBy('sort_order', 'ASC')->first();
                                    @endphp
                                    <tr>
                                        <td><img src="{{ route(config('imagecache.route'), ['template' => 'product-list', 'filename' => $product->product_id.'/'.$productImage->image ]) }}" alt="" width="80"/></td>
                                        <td style="text-align:left!important;">
                                            {{ $product->product->title }}
                                            <div>
                                            Warna: {{ $utils['arrColors'][$product->color_id] }}<br>
                                            Ukuran: {{ $utils['arrSizes'][$product->size_id] }}
                                            </div>
                                        </td>
                                        <td>
                                            {{ numberFormat($product->qty) }}
                                        </td>
                                        <td>
                                            {{ displayPrice($currentCurrency, $price) }}
                                        </td>
                                        <td class="kt-font-danger kt-font-lg">
                                            {{ displayPrice($currentCurrency, $total) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>

            <div class="kt-invoice__footer">
                <div class="kt-invoice__container">
                    <div class="table-responsive">
                        <div>
                            <strong>Kurir</strong><br>
                            {{ $order->courier_name }} - {{ $order->courier_service }} {{ !empty($order->courier_service_description) ? ' - ' . $order->courier_service_description : '' }} {{ !empty($order->destination_etd) ? ' - ' . $order->destination_etd : '' }} <br>
                            @if($order->nomor_resi)
                            {{ __('main.no_resi') }} : {{ $order->nomor_resi }} <br>
                            @endif
                            <br>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SUB TOTAL</th>
                                    <th>ONGKOS KIRIM</th>
                                    <th class="d-none">KODE UNIK</th>
                                    <th>VOUCHER</th>
                                    <th>GRAND TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $total_order = getPrice($currentCurrency, $idr, $order->total_order);
                                $total_shipping_charge = getPrice($currentCurrency, $idr, $order->total_shipping_charge);
                                $voucher_nominal = getPrice($currentCurrency, $idr, $order->voucher_nominal);
                                $grand_total = getPrice($currentCurrency, $idr, $order->grand_total);
                                @endphp
                                <tr>
                                    <td>{{ displayPrice($currentCurrency, $total_order) }}</td>
                                    <td>{{ displayPrice($currentCurrency, $total_shipping_charge) }}</td>
                                    <td class="d-none">{{ numberFormat($order->unique_code) }}</td>
                                    <td>- {{ displayPrice($currentCurrency, $voucher_nominal) }}</td>
                                    <td class="kt-font-danger kt-font-xl kt-font-boldest">{{ displayPrice($currentCurrency, $grand_total) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="kt-invoice__actions">
                <div class="kt-invoice__container">
                    <a href="{{ route($controller.'.index') }}" class="btn btn-label-brand btn-bold">
                        {{ __('main.back') }}
                    </a>
                    <button type="button" class="btn btn-brand btn-bold" onclick="window.print();">Print</button>
                    @if ($order->status_payment == 0 && $order->status_invoice == 1)
                    <button type="button" class="btn btn-success btn-bold d-none" data-toggle="modal" data-target="#modalApprove">Approve Pembayaran</button>
                    <!-- Modal -->
                    <div class="modal" tabindex="-1" role="dialog" id="modalApprove">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            {!! Form::open([ 'route'=>[$controller.'.approve', $order->id], 'class' => 'kt-form', 'id'=>'kt_form' ]) !!}
                            <input type="hidden" name="root" value="{{ $root }}">
                                <div class="modal-header">
                                    <h5 class="modal-title">Approve Pembayaran Invoice #{{ $order->invoice_number }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                <div class="form-group row">
                                    {!! Form::label('date', 'Tanggal', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        {!! Form::text('date', ($root == 'confirm_payment' ? date('d-m-Y', strtotime($confirmPayment->transfer_date)) : '' ) , ['required', 'autocomplete'=>'off', 'class'=>'kt_datepicker form-control'.( $errors->has('date') ? ' is-invalid' : '' ) ]); !!}
                                        @if ($errors->has('date')) <span class="form-text kt-font-danger"> {{ $errors->first('date') }} </span>@endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('amount', 'Jumlah Bayar', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        {!! Form::text('amount', ($root == 'confirm_payment' ? numberFormat($confirmPayment->amount) : numberFormat($order->grand_total) ) , ['required', 'class'=>'text-right is_money form-control'.( $errors->has('amount') ? ' is-invalid' : '' ) ]); !!}
                                        @if ($errors->has('amount')) <span class="form-text kt-font-danger"> {{ $errors->first('amount') }} </span>@endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('bank_id', 'Bank', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        {!! Form::select('bank_id', $utils['banks'], ($root == 'confirm_payment' ? $confirmPayment->bank_id : '' ), ['class'=>'select2 form-control custom-select']); !!}
                                        @if ($errors->has('bank_id')) <span class="form-text kt-font-danger"> {{ $errors->first('bank_id') }} </span>@endif
                                    </div>
                                </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Approve</button>
                                </div>
                            {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ($order->status_payment == 1 && $order->status_invoice == 1)
                    <button type="button" class="btn btn-success btn-bold" data-toggle="modal" data-target="#modalResi">Update Nomor Resi Pengiriman</button>
                    <!-- Modal -->
                    <div class="modal" tabindex="-1" role="dialog" id="modalResi">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            {!! Form::open([ 'route'=>[$controller.'.updateResi', $order->id], 'class' => 'kt-form', 'id'=>'kt_form' ]) !!}

                                <div class="modal-header">
                                    <h5 class="modal-title">Update Nomor Resi Pengiriman Invoice #{{ $order->invoice_number }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row">
                                        {!! Form::label('date', 'Tanggal Pengiriman', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                        <div class="col-lg-9 col-xl-6">
                                            {!! Form::text('date', (!is_null($order->nomor_resi) ? date('d-m-Y', strtotime($order->date_shipped)) : ''), ['required', 'autocomplete'=>'off', 'class'=>'kt_datepicker form-control'.( $errors->has('date') ? ' is-invalid' : '' ) ]); !!}
                                            @if ($errors->has('date')) <span class="form-text kt-font-danger"> {{ $errors->first('date') }} </span>@endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('nomor_resi', 'Nomor Resi', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                        <div class="col-lg-9 col-xl-6">
                                            {!! Form::text('nomor_resi', (!is_null($order->nomor_resi) ? $order->nomor_resi : ''), ['required', 'class'=>'form-control'.( $errors->has('nomor_resi') ? ' is-invalid' : '' ) ]); !!}
                                            @if ($errors->has('nomor_resi')) <span class="form-text kt-font-danger"> {{ $errors->first('nomor_resi') }} </span>@endif
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

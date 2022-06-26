<?php
use Carbon\Carbon;

$banks = \App\Bank::whereStatus(1)->get();
$billingSelected = $invoice->billing;
//$billingSelectedRajaOngkir = $billingSelected->rajaOngkir();
$arrSizes = \App\Dropdown::getOptions('size');
$arrColors = \App\Color::whereStatus(1)->pluck('title', 'id')->toArray();
$idr = $invoice->idr_rate;
$currentCurrency = $invoice->currency;
?>

@component('mail::message')
# Dear {{ !is_null($user) ? $user->name : $billingSelected->name }},
@if ($invoice->language == 'id')
Terima kasih telah melakukan order di {{ config('app.name') }}!<br>
Untuk melihat order anda selengkapnya, silahkan klik tombol dibawah ini.
@else
Thank you for placing an order at {{ config('app.name') }}!<br>
To view your complete order, please click the button below.
@endif

@if (!is_null($invoice->xendit_url))
@component('mail::button', ['url' => $invoice->xendit_url])
@if ($invoice->language == 'id')
Bayar Sekarang
@else
Pay Now
@endcomponent
@endif
@endif

@component('mail::panel')
{{--
@foreach ($banks as $bank)
<strong>{{ $bank->name }}</strong><br>
{{ $bank->account_number }}<br>
{{ $bank->owner_name }}<br><br>
@endforeach
--}}

@if ($invoice->language == 'id')
Silahkan lakukan pembayaran sebelum: {{ date('d/m/Y H:i', strtotime($invoice->invoice_due_date)) }}
@else
Please make payment before: {{ date('d/m/Y H:i', strtotime($invoice->invoice_due_date)) }}
@endif
@endcomponent

@component('mail::panel')
@if ($invoice->language == 'id')
<strong>Alamat Pengiriman</strong><br>
@else
<strong>Shipping address</strong><br>
@endif

{{ $billingSelected->name }}<br>
{{ $billingSelected->address }}
@if ($invoice->language == 'id')
@if (is_null($invoice->destination_country_id))
<br>{{ $billingSelected->subdistrict_name}}
{{ $billingSelected->type_name}} {{ $billingSelected->city_name}} {{ $billingSelected->postcode }}
<br>{{ $billingSelected->province_name}}
@else
<br>{{ $billingSelected->postcode }}
@endif
<br>{{ $billingSelected->country_name }}
<br>{{ $billingSelected->handphone}}
@else
@if (is_null($invoice->destination_country_id))
<br>{{ $billingSelected->subdistrict_name}}
<br>{{ $billingSelected->type_name}} {{ $billingSelected->city_name}} {{ $billingSelected->postcode }}
<br>{{ $billingSelected->province_name}}
@else
<br>{{ $billingSelected->postcode }}
@endif
<br>{{ $billingSelected->country_name }}
<br>{{ $billingSelected->handphone}}
@endif

@endcomponent

@component('mail::table')
@if ($invoice->language == 'id')
| Nama Produk                   | Warna                                | Ukuran                             | Qty                               | Harga                               | Total                                               |
| ----------------------------- | ------------------------------------ |:----------------------------------:|:---------------------------------:| -----------------------------------:| ---------------------------------------------------:|
@foreach ($invoice->products as $product)
@php
$price = getPrice($currentCurrency, $idr, $product->price);
$total = $price * $product->qty;
@endphp
| {{ $product->product->title }} | {{ $arrColors[$product->color_id] }} | {{ $arrSizes[$product->size_id] }} | {{ numberFormat($product->qty) }} | {{ displayPrice($currentCurrency, $price) }} | {{ displayPrice($currentCurrency, $total) }} |
@endforeach
@else
| Product Name                  | Color                                | Size                               | Qty                               | Price                               | Total                                               |
| ----------------------------- | ------------------------------------ |:----------------------------------:|:---------------------------------:| -----------------------------------:| ---------------------------------------------------:|
@foreach ($invoice->products as $product)
@php
$price = getPrice($currentCurrency, $idr, $product->price);
$total = $price * $product->qty;
@endphp
| {{ $product->product->title }} | {{ $arrColors[$product->color_id] }} | {{ $arrSizes[$product->size_id] }} | {{ numberFormat($product->qty) }} | {{ displayPrice($currentCurrency, $price) }} | {{ displayPrice($currentCurrency, $total) }} |
@endforeach
@endif
@endcomponent

<div style="float:right">
@component('mail::table')
@php
$total_order = getPrice($currentCurrency, $idr, $invoice->total_order);
$total_shipping_charge = getPrice($currentCurrency, $idr, $invoice->total_shipping_charge);
$voucher_nominal = getPrice($currentCurrency, $idr, $invoice->voucher_nominal);
$grand_total = getPrice($currentCurrency, $idr, $invoice->grand_total);
@endphp
@if ($invoice->language == 'id')
|              |                                                     |
| ------------ | ---------------------------------------------------:|
| Sub Total    | {{ displayPrice($currentCurrency, $total_order) }}           |
| Ongkos Kirim | {{ displayPrice($currentCurrency, $total_shipping_charge) }} |
| Voucher      | - {{ displayPrice($currentCurrency, $voucher_nominal) }}     |
| <strong>Total Bayar</strong>  | <strong>{{ displayPrice($currentCurrency, $grand_total) }}</strong>           |
@else
|                |                                                     |
| -------------- | ---------------------------------------------------:|
| Sub Total      | {{ displayPrice($currentCurrency, $total_order) }}          |
| Shipping Price | {{ displayPrice($currentCurrency, $total_shipping_charge) }} |
| Voucher        | - {{ displayPrice($currentCurrency, $voucher_nominal) }}     |
| <strong>Total to Pay</strong>  | <strong>{{ displayPrice($currentCurrency, $grand_total) }}</strong>           |
@endif
@endcomponent
</div>
<div style="clear: both;"></div>

<br>
@if ($invoice->language == 'id')
Terimakasih.
@else
Thank You.
@endif
<br><br>
{{ config('app.name') }}<br>
Main Website: {{ config('app.url') }}
@endcomponent


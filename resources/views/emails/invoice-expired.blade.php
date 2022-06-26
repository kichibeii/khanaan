<?php
use Carbon\Carbon;

$banks = \App\Bank::whereStatus(1)->get();
$billingSelected = $invoice->billing;
$billingSelectedRajaOngkir = $billingSelected->rajaOngkir();
$arrSizes = \App\Dropdown::getOptions('size');
$arrColors = \App\Color::whereStatus(1)->pluck('title', 'id')->toArray();
?>

@component('mail::message')
# Dear {{ $user->name }},
@if ($invoice->language == 'id')
Kami ingatkan bahwa Anda belum melakukan pembayaran untuk Invoice #{{ $invoice->invoice_number }} yang akan jatuh tempo pada tanggal {{ date('d/m/Y H:i', strtotime($invoice->invoice_due_date)) }}. Berikut informasi detailnya :
@else
We remind you that you have not made payment for Invoice #{{ $invoice->invoice_number }} which is due on {{ date('d/m/Y H:i', strtotime($invoice->invoice_due_date)) }}. Here is the detailed information:
@endif

@component('mail::panel')
@if ($invoice->language == 'id')
Silahkan transfer ke salah satu rekening dibawah ini:<br>
@else
Please transfer to one of the accounts below:<br>
@endif

@foreach ($banks as $bank)
<strong>{{ $bank->name }}</strong><br>
{{ $bank->account_number }}<br>
{{ $bank->owner_name }}<br><br>
@endforeach

@if ($invoice->language == 'id')
Sebelum : {{ date('d/m/Y H:i', strtotime($invoice->invoice_due_date)) }}
@else
Before : {{ date('d/m/Y H:i', strtotime($invoice->invoice_due_date)) }}
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
<br>Kecamatan {{ $billingSelectedRajaOngkir->subdistrict_name}}
<br>{{ $billingSelectedRajaOngkir->type}} {{ $billingSelectedRajaOngkir->city}}
<br>Provinsi {{ $billingSelectedRajaOngkir->province}}
<br>Kode Pos {{ $billingSelected->postcode }}
<br>{{ $billingSelected->handphone}}
@else
<br>District {{ $billingSelectedRajaOngkir->subdistrict_name}}
<br>{{ $billingSelectedRajaOngkir->type}} {{ $billingSelectedRajaOngkir->city}}
<br>Province {{ $billingSelectedRajaOngkir->province}}
<br>Postal code {{ $billingSelected->postcode }}
<br>{{ $billingSelected->handphone}}
@endif

@endcomponent

@component('mail::table')
@if ($invoice->language == 'id')
| Nama Produk                   | Warna                                | Ukuran                             | Qty                               | Harga                               | Total                                               |
| ----------------------------- | ------------------------------------ |:----------------------------------:|:---------------------------------:| -----------------------------------:| ---------------------------------------------------:|
@foreach ($invoice->products as $product)
| {{ $product->product->title }} | {{ $arrColors[$product->color_id] }} | {{ $arrSizes[$product->size_id] }} | {{ numberFormat($product->qty) }} | {{ numberFormat($product->price) }} | {{ numberFormat($product->price * $product->qty) }} |
@endforeach
@else
| Product Name                  | Color                                | Size                               | Qty                               | Price                               | Total                                               |
| ----------------------------- | ------------------------------------ |:----------------------------------:|:---------------------------------:| -----------------------------------:| ---------------------------------------------------:|
@foreach ($invoice->products as $product)
| {{ $product->product->title }} | {{ $arrColors[$product->color_id] }} | {{ $arrSizes[$product->size_id] }} | {{ numberFormat($product->qty) }} | {{ numberFormat($product->price) }} | {{ numberFormat($product->price * $product->qty) }} |
@endforeach
@endif
@endcomponent

<div style="float:right">
@component('mail::table')
@if ($invoice->language == 'id')
|              |                                                     |
| ------------ | ---------------------------------------------------:|
| Sub Total    | {{ numberFormat($invoice->total_order) }}           |
| Ongkos Kirim | {{ numberFormat($invoice->total_shipping_charge) }} |
| Voucher      | - {{ numberFormat($invoice->voucher_nominal) }}     |
| <strong>Total Bayar</strong>  | <strong>{{ numberFormat($invoice->grand_total) }}</strong>           |
@else
|                |                                                     |
| -------------- | ---------------------------------------------------:|
| Sub Total      | {{ numberFormat($invoice->total_order) }}           |
| Shipping Price | {{ numberFormat($invoice->total_shipping_charge) }} |
| Voucher        | - {{ numberFormat($invoice->voucher_nominal) }}     |
| <strong>Total to Pay</strong>  | <strong>{{ numberFormat($invoice->grand_total) }}</strong>           |
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

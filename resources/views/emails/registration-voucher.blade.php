<?php
use Carbon\Carbon;
?>

@component('mail::message')
# Dear {{ $user->name }},

@if ($user->language == 'id')
<b>Kami sangat senang akhirnya kamu menjadi bagian dari {{ config('app.name') }}!</b><br>
Anda mendapatkan voucher sebesar IDR {{ numberFormat($voucher->nominal) }}, berlaku 1 minggu dari sekarang. Dengan Kode:<br>
@else
<b>We are so happy that you are finally a part of {{ config('app.name') }}!</b><br>
You get a voucher of IDR {{ numberFormat($voucher->nominal) }}, valid 1 week from now. With Code:<br>
@endif

<h3>{{ $voucher->code }}</h3>

@if ($user->language == 'id')
Gunakan voucher ini untuk berbelanja di website kami.<br>
Terimakasih.
@else
Use this voucher to shop on our website<br>
Thank You
@endif

<br><br>
{{ config('app.name') }}<br>
Main Website: {{ config('app.url') }}
@endcomponent


@php
    $class_invoice = ' label-success'; if(!$invoice->status_invoice) $class_invoice = ' label-danger';
    $class_payment = ' label-success'; if(!$invoice->status_payment) $class_payment = ' label-danger';
    $idr = $invoice->idr_rate;
    $currentCurrency = $invoice->currency;
@endphp
<div class="col-sm-12">
    <div class="data-header">
        <div class="row">
            <div class="col-sm-6">
                <label>{{ __('main.invoice_date') }}</label>
                <div><span>{{ date('d/m/Y',strtotime($invoice->invoice_date)) }}</span></div>

                <label>{{ __('main.order_id') }}</label>
                <div><span>#{{ $invoice->invoice_number }}</span></div>

                <div class="d-none">
                <label>{{  __('main.status_invoice') }}</label>
                <div><span class="status{{ $class_invoice }}">{{ arrStatusInvoice()[$invoice->status_invoice] }}</span></div>
                </div>

                <label>{{  __('main.status_payment') }}</label>
                <div><span class="status{{ $class_payment }}">{{ __('main.'.arrStatusPayment()[$invoice->status_payment]) }}</span></div>
            </div>
            <div class="col-sm-6 text-right">

                <span><strong>{{ __('main.data_shipping_address') }}</strong></span><br>
                <span>{{ $utils['billing']->name }}</span><br>
                <span>{{ $utils['billing']->address }}</span>
                @if ($invoice->language == 'id')
                @if (is_null($invoice->destination_country_id))
                <span>{{ $utils['billing']->subdistrict_name}}
                {{ $utils['billing']->type_name}} {{ $utils['billing']->city_name}} {{ $utils['billing']->postcode }}</span>
                <br><span>{{ $utils['billing']->province_name}}</span>
                @else
                <br><span>{{ $utils['billing']->postcode }}</span>
                @endif
                <br><span>{{ $utils['billing']->country_name }}</span>
                <br><span>{{ $utils['billing']->handphone}}</span>
                @else
                @if (is_null($invoice->destination_country_id))
                <span>{{ $utils['billing']->subdistrict_name}}
                <br>{{ $utils['billing']->type_name}} {{ $utils['billing']->city_name}} {{ $utils['billing']->postcode }}</span>
                <br><span>{{ $utils['billing']->province_name}}</span>
                @else
                <br><span>{{ $utils['billing']->postcode }}</span>
                @endif
                <br><span>{{ $utils['billing']->country_name }}</span>
                <br><span>{{ $utils['billing']->handphone}}</span>
                @endif



            </div>
        </div>
    </div>
    <div class="data-body mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>{{ __('main.product') }}</th>
                    <th>{{ __('main.qty') }}</th>
                    <th>{{ __('main.price') }}</th>
                    <th>{{ __('main.total') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($invoice->products as $v)
            @php
                $price = getPrice($currentCurrency, $idr, $v->price);
                $total = $price * $v->qty;
                $productImage = $v->product->images()->where('color_id', $v->color_id)->orderBy('sort_order', 'ASC')->first();
            @endphp
            <tr>
                <td>
                    <img class="img" src="{{ route(config('imagecache.route'), ['template' => 'product-list', 'filename' => $v->product_id.'/'.$productImage->image ]) }}" alt="{{ $v->product->title }}"/>
                </td>
                <td>
                    <strong>{{ $v->product->title }}</strong>
                    <br>{{ __('main.color') }}: {{ $utils['arrColors'][$v->color_id] }}
                    <br>{{ __('main.size') }}: {{ $utils['arrSizes'][$v->size_id] }}
                </td>
                <td class="text-center">{{ numberFormat($v->qty) }}</td>
                <td>{{ displayPrice($currentCurrency, $price) }}</td>
                <td class="total">{{ displayPrice($currentCurrency, $total) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

        <hr>
        <div class="pengiriman mt-3">
            {{ __('main.shipment') }}: <br>
            {{ $invoice->courier_name }} - {{ $invoice->courier_service }} {{ !empty($invoice->courier_service_description) ? ' - ' . $invoice->courier_service_description : '' }} {{ !empty($invoice->destination_etd) ? ' - ' . $invoice->destination_etd : '' }} <br>
            @if($invoice->nomor_resi)
            {{ __('main.no_resi') }} : {{ $invoice->nomor_resi }} <br>
            @endif
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                @if ($invoice->status_invoice == 1 && !is_null($invoice->xendit_url) && empty($invoice->status_payment))
                <a class="btn btn-full btn-black mt-26" href="{{ $invoice->xendit_url }}">{{ __('main.pay_now') }}</a>
                @endif
            </div>
            <div class="col-sm-6">
                <div class="pembayaran mt-3">
                    @php
                    $total_order = getPrice($currentCurrency, $idr, $invoice->total_order);
                    $total_shipping_charge = getPrice($currentCurrency, $idr, $invoice->total_shipping_charge);
                    $voucher_nominal = getPrice($currentCurrency, $idr, $invoice->voucher_nominal);
                    $grand_total = getPrice($currentCurrency, $idr, $invoice->grand_total);
                    @endphp
                    <table align="right">
                        <tr><td width="200">{{ __('main.subtotal') }}</td> <td  class="text-right">{{ displayPrice($currentCurrency, $total_order) }} </td></tr>
                        <tr><td>{{ __('main.delivery_cost') }}</td> <td class="text-right">{{ displayPrice($currentCurrency, $total_shipping_charge) }}</td></tr>
                        <tr class="d-none"><td>{{ __('main.unique_code') }}</td> <td class="text-right">{{ __('main.idr') }} {{ numberFormat($invoice->unique_code) }}</td></tr>
                        <tr><td>{{ __('main.voucher') }}</td> <td class="voucher text-right">- {{ displayPrice($currentCurrency, $voucher_nominal) }}</td></tr>
                        <tr><td>{{ __('main.grand_total') }}</td> <td class="total text-right"><strong>{{ displayPrice($currentCurrency, $grand_total) }}</strong></td></tr>
                    </table>
                </div>
            </div>
        </div>

        @if($invoice->status_payment == 1)
        <hr>
        <div class="status_pengiriman">
            {{ __('main.shipping_status') }} : <br>
            @if(isset($utils['resi_rajaongkir']['result']))
                <table class="table mt-3">
                    <thead><tr>
                        <th>{{ __('main.date') }}</th>
                        <th>{{ __('main.remark') }}</th>
                    </tr></thead>
                    <tbody>
                    @foreach($utils['resi_rajaongkir']['result']['manifest'] as $k => $v)
                    <tr>
                        <td>{{ date("d/m/Y H:i",  strtotime($v['manifest_date'].' '.$v['manifest_time'])) }}</td>
                        <td>{{ $v['manifest_description'] }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        @endif
    </div>
</div>

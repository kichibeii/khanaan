@php
    $class_invoice = ' label-success'; if(!$invoice->status_invoice) $class_invoice = ' label-danger';
    $class_payment = ' label-success'; if(!$invoice->status_payment) $class_payment = ' label-danger';
@endphp
<div class="col-sm-12">
    <table>
    <tr><td width="200">{{ __('main.order_id') }}</td><td>:</td><td>#{{ $invoice->invoice_number }}</td></tr>
        <tr><td width="200">{{ __('main.invoice_date') }}</td><td>:</td><td>{{ date('d/m/Y',strtotime($invoice->invoice_date)) }}</td></tr>
        <tr><td width="200">{{ __('main.status_invoice') }}</td><td>:</td><td>{{ arrStatusInvoice()[$invoice->status_invoice] }}</td></tr>
        <tr><td width="200">{{ __('main.status_payment') }}</td><td>:</td><td>{{ arrStatusPayment()[$invoice->status_payment] }}</td></tr>
        @if($invoice->nomor_resi)
        <tr><td width="200">{{ __('main.no_resi') }}</td><td>:</td><td>{{ $invoice->nomor_resi }}</td></tr>
        @endif
    </table>
    @if(!$invoice->nomor_resi)
    <div class="text-center"><h5>{{ __('main.resi_not_update') }}</h5></div>
    @elseif(!isset($utils['resi_rajaongkir']['result']))
    <div class="text-center"><h5>{{ __('main.track_order_not_found') }}</h5></div>
    @else
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
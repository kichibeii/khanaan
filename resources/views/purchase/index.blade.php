@extends('layouts.veera')
@section('title', __('main.history_order'))

@section('styles-after')
<link href="/assets/css/purchase-custom.css" rel="stylesheet">
@endsection
@section('content')

<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2>{{ __('main.history_order') }}</h2>
                    <nav class="page-breadcrumb">
                        <ul class="d-flex justify-content-center">
                            <li><a href="{{ route('home') }}">{{ __('main.home') }}</a></li>
                            <li><a href="{{ route('purchase') }}" class="active">{{ __('main.history_order') }}</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Header Wrapper -->

<!-- Start My Account Wrapper -->
<div id="my-account-page-wrapper" data-period="{{ $utils['period'] }}" class="pt-20 pt-md-58 pt-sm-48 pb-50 pb-md-20 pb-sm-10 ">
    <div class="container">


    <div class="row">


        <div class="col-sm-12">

            @if(count($utils['data']) <=0)
            <div class="text-center"><h2>{{ __('main.data_not_found') }}</h2></div>
            @else
                @foreach($utils['data'] as $k => $v)
                @php
                    $class_invoice = ' label-success'; if(!$v->status_invoice) $class_invoice = ' label-danger';
                    $class_payment = ' label-success'; if(!$v->status_payment) $class_payment = ' label-danger';
                    $idr = $v->idr_rate;
                    $currentCurrency = $v->currency;
                    $total = getPrice($currentCurrency, $idr, $v->grand_total);

                    $price = getPrice($currentCurrency, $idr, $v->price);
                @endphp
                <div class="card mb-40">
                    <div class="card-header">{{ date('d/m/Y',strtotime($v->invoice_date)) }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3 box-data right">
                                <label>{{ __('main.order_id') }}</label>
                                <div><span>#{{ $v->invoice_number }}</span></div>
                            </div>
                            <div class="col-sm-3 box-data right">
                                <div class="d-none">
                                <label>{{  __('main.status_invoice') }}</label>
                                <div><span class="status{{ $class_invoice }}">{{ arrStatusInvoice()[$v->status_invoice] }}</span></div>
                                </div>
                                <label>{{  __('main.status_payment') }}</label>
                                <div><span class="status{{ $class_payment }}">{{ __('main.'.arrStatusPayment()[$v->status_payment]) }}</span></div>
                            </div>
                            <div class="col-sm-3 box-data">
                                <label>{{ __('main.total_pay') }}</label>
                                <div><span class="total">{{ displayPrice($currentCurrency, $total) }}</span></div>
                            </div>
                            <div class="col-sm-3 box-data">
                                <div class="product-item d-flex align-items-center">
                                    <img class="img" src="{{ route(config('imagecache.route'), ['template' => 'product-list', 'filename' => $v->product_id.'/'.$v->product_image ]) }}" alt="{{ $v->product_name }}"/>
                                        <a href="{{ route('shop.show', $v->product_slug)}}" class="product-name">
                                            <strong>{{ $v->product_name }}</strong>
                                        <br>{{ __('main.color') }}: {{ $v->color_name }}
                                        <br>{{ __('main.size') }}: {{ $v->size_name }}
                                        <br><span class="total">{{ displayPrice($currentCurrency, $price) }}</span>
                                        x {{ number_format($v->qty) }}
                                        </a>

                                    </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row product-detail">
                            <div class="col-sm-12">
                                <a href="javascript:;" class="view-detail" data-no="{{ $v->id }}"><i class="fa fa-eye"></i> {{ __('main.view_order_details') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="page-pagination-wrapper mt-70 mt-md-50 mt-sm-40">
                    {{ $utils['data']->links('vendor.pagination.veera') }}

                </div>
            @endif
        </div>
    </div>

    </div>
</div>
<!-- End My Account Wrapper -->

<!-- Modal Detail -->
<div id="modal-detail" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('main.order_details') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="div-loader">
                <div class="loader"></div>
            </div>
            <div class="div-content"></div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="/assets/js/purchase-custom.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
$(document).ready(function(){
    var dt = $('#my-account-page-wrapper').data();
    var period = dt.period.split('-');
    $('#period').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY'
        },
        startDate: period[0],
        endDate: period[1]
    });
});
</script>
@endsection

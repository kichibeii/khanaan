@extends('layouts.admin.dashboard')
@section('title', $title)
@section('icon', $icon)

@section('scripts')
<script src="/admin/plugins/plentz-jquery-maskmoney/dist/jquery.maskMoney.min.js" type="text/javascript"></script>
<script src="/js/admin/{{ $controller }}/so-add.js" type="text/javascript"></script>
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
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                {{ $utils['action'] }} {{ $product->title }}
            </h3>
        </div>
    </div>

    {!! Form::open([ 'route'=>[$controller.'.so.store', $product->id], 'class' => 'kt-form', 'id'=>'kt_form' ]) !!}
    <div class="kt-portlet__body">
        <div class="kt-form kt-form--label-right">
            <div class="kt-form__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body">
                        @if (count($product->colorSizeQtys))
                        <p>Sebaiknya anda non aktifkan produk ini terlebih dahulu. Supaya tidak terjadi kesalahan penghitungan quantity</p>
                        <table class="table table-bordered table-hover" id="kt_table">
                            <thead>
                                <tr>
                                    <th>Warna</th>
                                    <th>Ukuran</th>
                                    <th>Qty Sistem Baru</th>
                                    <th>Qty Sistem</th>
                                    <th>Qty Booking</th>
                                    <th>Qty Fisik</th>
                                    <th width="120">{{ __('main.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->colorSizeQtys as $row)
                                @php
                                $qtyBooked = $product->bookeds()->where('color_id', $row->color_id)->where('size_id', $row->size_id)->sum('qty');
                                $qtySistem = $row->qty - $qtyBooked;
                                @endphp
                                <tr>
                                    <td>{{ $utils['options']['colors'][$row->color_id] }}</td>
                                    <td class="text-center">{{ $utils['options']['size'][$row->size_id] }}</td>
                                    <td>{!! Form::text('qty['.$row->color_id.']['.$row->size_id.']', $qtySistem, ['required', 'class'=>'form-control' ]); !!}</td>
                                    <td class="text-center">{{ $qtySistem }}</td>
                                    <td class="text-center">{{ $qtyBooked }}</td>
                                    <td class="text-center">{{ $row->qty }}</td>
                                    <td class="text-center">
                                        <a href="{{ route($controller.'.so.delete', $row->id) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md remove-list">
                                            <i class="la la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route($controller.'.so', $product->id) }}" class="btn btn-secondary">
                        {{ __('main.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection

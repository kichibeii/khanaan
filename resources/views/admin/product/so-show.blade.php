@extends('layouts.admin.dashboard')
@section('title', $title)
@section('icon', $icon)

@section('scripts')
<script src="/admin/plugins/plentz-jquery-maskmoney/dist/jquery.maskMoney.min.js" type="text/javascript"></script>
<script src="/js/admin/{{ $controller }}/additional-add.js" type="text/javascript"></script>
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

        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    {{ date('d/m/Y H:i', strtotime($so->tanggal)) }}
                </div>
            </div>
        </div>
    </div>

    <div class="kt-portlet__body">
        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table">
            <thead>
                <tr>
                    <th width="5">{{ __('main.no') }}</th>
                    <th>Warna</th>
                    <th>Size</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($so->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $utils['options']['colors'][$item->color_id] }}</td>
                    <td>{{ $utils['options']['size'][$item->size_id] }}</td>
                    <td>{{ numberFormat($item->qty) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <div class="row">
                <div class="col-12">
                    <a href="{{ route($controller.'.so', $product->id) }}" class="btn btn-secondary">
                        {{ __('main.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

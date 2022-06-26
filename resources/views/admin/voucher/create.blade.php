@extends('layouts.admin.dashboard')
@section('title', $title)
@section('icon', $icon)

@section('scripts')
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
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                {{ $utils['action'] }}
            </h3>
        </div>
    </div>
    
    {!! Form::open([ 'route'=>[$controller.'.store'], 'class' => 'kt-form', 'id'=>'kt_form' ]) !!}
    <div class="kt-portlet__body">
        <div class="kt-form kt-form--label-right">
            <div class="kt-form__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body">
                        <div class="form-group row">
                            {!! Form::label('nominal', 'Nominal', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-4 col-xl-4">
                                {!! Form::text('nominal', old('nominal'), ['required', 'class'=>'text-right is_money form-control'.( $errors->has('nominal') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('nominal')) <span class="form-text kt-font-danger"> {{ $errors->first('nominal') }} </span>@endif
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('total_print', 'Jumlah Cetak', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-4 col-xl-4">
                                {!! Form::text('total_print', old('total_print'), ['required', 'class'=>'is_money form-control'.( $errors->has('total_print') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('total_print')) <span class="form-text kt-font-danger"> {{ $errors->first('total_print') }} </span>@endif
                            </div>
                        </div>

                        @php
                        $start_date = old('start_date') !== null ? old('start_date') : now()->format('d-m-Y');
                        @endphp
                        <div class="form-group row">
                            {!! Form::label('start_date', 'Tanggal Mulai Berlaku', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-4 col-xl-4">
                                {!! Form::text('start_date', $start_date, ['required', 'class'=>'kt_datepicker form-control'.( $errors->has('start_date') ? ' is-invalid' : '' ), 'autocomplete'=>'off' ]); !!}
                                @if ($errors->has('start_date')) <span class="form-text kt-font-danger"> {{ $errors->first('start_date') }} </span>@endif
                            </div>
                        </div>


                        <div class="form-group row">
                            {!! Form::label('end_date', 'Tanggal Berakhir Berlaku', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-4 col-xl-4">
                                {!! Form::text('end_date', old('end_date'), ['required', 'class'=>'kt_datepicker form-control'.( $errors->has('end_date') ? ' is-invalid' : '' ), 'autocomplete'=>'off' ]); !!}
                                @if ($errors->has('end_date')) <span class="form-text kt-font-danger"> {{ $errors->first('end_date') }} </span>@endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <div class="row">
                <div class="col-3">
                </div>
                <div class="col-9">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route($controller.'.index') }}" class="btn btn-secondary">
                        {{ __('main.back') }} 
                    </a>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

@endsection
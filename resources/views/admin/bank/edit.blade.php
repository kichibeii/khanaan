@extends('layouts.admin.dashboard')
@section('title', $title)
@section('icon', $icon)

@section('scripts')
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
    
    {!! Form::open(['route' => [$controller.'.update', $bank->id], 'method'=>'PUT', 'class' => 'kt-form', 'id'=>'kt_form', 'enctype'=>"multipart/form-data"]) !!}
    <div class="kt-portlet__body">
        <div class="kt-form kt-form--label-right">
            <div class="kt-form__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body">
                        <div class="form-group row">
                            {!! Form::label('name', 'Nama Bank', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::text('name', $bank->name, ['required', 'class'=>'form-control'.( $errors->has('name') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('name')) <span class="form-text kt-font-danger"> {{ $errors->first('name') }} </span>@endif
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('account_number', 'Nomor Rekening', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::text('account_number', $bank->account_number, ['required', 'maxlength'=>20, 'class'=>'form-control'.( $errors->has('account_number') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('account_number')) <span class="form-text kt-font-danger"> {{ $errors->first('account_number') }} </span>@endif
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('owner_name', 'Nama Pemilik Rekening', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::text('owner_name', $bank->owner_name, ['required', 'maxlength'=>100, 'class'=>'form-control'.( $errors->has('owner_name') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('owner_name')) <span class="form-text kt-font-danger"> {{ $errors->first('owner_name') }} </span>@endif
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('branch', 'Cabang', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::text('branch', $bank->branch, ['required', 'maxlength'=>70, 'class'=>'form-control'.( $errors->has('branch') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('branch')) <span class="form-text kt-font-danger"> {{ $errors->first('branch') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('file', 'Upload Logo', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-5">
                                {!! Form::file('file', ['class'=>'form-control'.( $errors->has('file') ? ' form-control-danger' : '' ) ]); !!}
                                <div style="margin-top: 20px;">
                                    <img src="{{ route(config('imagecache.route'), ['template' => 'resize-medium', 'filename' => \App\Bank::getImage($bank) ]) }}" alt="" class="img-circle">
                                </div>
                                @if ($errors->has('file')) <span class="form-text kt-font-danger"> {{ $errors->first('file') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('status', 'Status', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-8">
                                <div class="kt-radio-inline">
                                    @php
                                    $optionSelected = $bank->status;
                                    @endphp
                                    @foreach (arrStatusActive() as $k => $v)
                                    <label class="kt-radio">
                                        <input type="radio" name="status" id="status_{{ $k }}" value="{{ $k }}" {{ $k == $optionSelected ? 'checked' : '' }}> {{ $v }}
                                        <span></span>
                                    </label>
                                    @endforeach
                                </div>
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
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
    
    {!! Form::open(['route' => [$controller.'.update', $color->id], 'method'=>'PUT', 'class' => 'kt-form', 'id'=>'kt_form']) !!}
    <div class="kt-portlet__body">
        <div class="kt-form kt-form--label-right">
            <div class="kt-form__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body">
                    <div class="form-group row">
                            {!! Form::label('color_hex', 'Color Hex', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-4 col-xl-4">
                                {!! Form::text('color_hex', $color->color_hex, ['required', 'maxlength'=>6, 'class'=>'text-lowercase form-control'.( $errors->has('code') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('color_hex')) <span class="form-text kt-font-danger"> {{ $errors->first('color_hex') }} </span>@endif
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('title', 'Nama', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::text('title', $color->title, ['required', 'maxlength'=>80, 'class'=>'form-control'.( $errors->has('title') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('title')) <span class="form-text kt-font-danger"> {{ $errors->first('title') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('group_color', 'Group Color', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-7">
                                <select class="form-control" multiple="multiple" name="group_color[]" id="group_color" required="">
                                    @foreach ($utils['options']['group_color'] as $k => $v)
                                    <option value="{{ $k }}" {{ in_array($k, $utils['options']['groupColorSelecteds']) ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                                
                                @if ($errors->has('group_color')) <span class="form-text kt-font-danger"> {{ $errors->first('group_color') }} </span>@endif
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            {!! Form::label('sort_order', 'Urutan', ['required', 'class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-3 col-xl-3">
                                {!! Form::number('sort_order', $color->sort_order, ['class'=>'form-control'.( $errors->has('sort_order') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('sort_order')) <span class="form-text kt-font-danger"> {{ $errors->first('sort_order') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('status', 'Status', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-8">
                                <div class="kt-radio-inline">
                                    @php
                                    $optionSelected = $color->status;
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
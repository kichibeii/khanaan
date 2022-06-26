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
    
    {!! Form::open([ 'route'=>[$controller.'.store'], 'class' => 'kt-form', 'id'=>'kt_form' ]) !!}
    <div class="kt-portlet__body">
        <div class="kt-form kt-form--label-right">
            <div class="kt-form__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body">
                        <div class="form-group row">
                            {!! Form::label('name', 'Nama', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::text('name', old('name'), ['required', 'class'=>'form-control'.( $errors->has('name') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('name')) <span class="form-text kt-font-danger"> {{ $errors->first('name') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('email', 'E-Mail', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-7">
                                {!! Form::email('email', old('email'), ['required', 'class'=>'form-control'.( $errors->has('email') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('email')) <span class="form-text kt-font-danger"> {{ $errors->first('email') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('role', 'Group User', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-7">
                                <select class="form-control" multiple="multiple" name="role[]" id="role" required="">
                                    @foreach ($utils['options']['roles'] as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                                
                                @if ($errors->has('role')) <span class="form-text kt-font-danger"> {{ $errors->first('role') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('username', 'Username', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-7">
                                {!! Form::text('username', old('username'), ['required', 'class'=>'form-control'.( $errors->has('username') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('username')) <span class="form-text kt-font-danger"> {{ $errors->first('username') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('password', 'Password', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-7">
                                {!! Form::password('password', ['required', 'class'=>'form-control'.( $errors->has('password') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('password')) <span class="form-text kt-font-danger"> {{ $errors->first('password') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('password_confirmation', 'Konfirmasi Password', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-7">
                                {!! Form::password('password_confirmation', ['required', 'class'=>'form-control'.( $errors->has('password_confirmation') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('password_confirmation')) <span class="form-text kt-font-danger"> {{ $errors->first('password_confirmation') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('status', 'Status', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-8">
                                <div class="kt-radio-inline">
                                    @php
                                    $optionSelected = old('status') !== null ? old('status') : 1;
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
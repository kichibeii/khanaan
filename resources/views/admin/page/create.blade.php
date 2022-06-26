@extends('layouts.admin.dashboard')
@section('title', $title)
@section('icon', $icon)

@section('scripts')
<script src="/admin/plugins/custom/ckeditor/ckeditor-classic.bundle.js" type="text/javascript"></script>
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
                            {!! Form::label('title', 'Judul', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::text('title', old('title'), ['required', 'class'=>'form-control'.( $errors->has('title') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('title')) <span class="form-text kt-font-danger"> {{ $errors->first('title') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('title_id', 'Judul (ID)', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::text('title_id', old('title_id'), ['required', 'class'=>'form-control'.( $errors->has('title_id') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('title_id')) <span class="form-text kt-font-danger"> {{ $errors->first('title_id') }} </span>@endif
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('slug', 'Slug', ['class'=>'text-lowercase col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::text('slug', old('slug'), ['required', 'class'=>'form-control'.( $errors->has('slug') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('slug')) <span class="form-text kt-font-danger"> {{ $errors->first('slug') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('description', 'Konten', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::textarea('description', old('description'), ['class'=>'form-control'.( $errors->has('description') ? ' is-invalid' : '' ), 'autocomplete'=>'off' ]); !!}
                                @if ($errors->has('description')) <span class="form-text kt-font-danger"> {{ $errors->first('description') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('description_id', 'Konten (ID)', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::textarea('description_id', old('description_id'), ['class'=>'form-control'.( $errors->has('description_id') ? ' is-invalid' : '' ), 'autocomplete'=>'off' ]); !!}
                                @if ($errors->has('description_id')) <span class="form-text kt-font-danger"> {{ $errors->first('description_id') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('sort_order', 'Urutan', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::number('sort_order', old('sort_order'), ['required', 'class'=>'form-control'.( $errors->has('sort_order') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('sort_order')) <span class="form-text kt-font-danger"> {{ $errors->first('sort_order') }} </span>@endif
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
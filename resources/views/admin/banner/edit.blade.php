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
    
    {!! Form::open(['route' => [$controller.'.update', $banner->id], 'method'=>'PUT', 'class' => 'kt-form', 'id'=>'kt_form', 'enctype'=>"multipart/form-data"]) !!}
    <div class="kt-portlet__body">
        <div class="kt-form kt-form--label-right">
            <div class="kt-form__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body">
                        <div class="form-group row">
                            {!! Form::label('title', 'Judul', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::text('title', $banner->title, ['required', 'class'=>'form-control'.( $errors->has('title') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('title')) <span class="form-text kt-font-danger"> {{ $errors->first('title') }} </span>@endif
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            {!! Form::label('title_id', 'Judul (ID)', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::text('title_id', $banner->title_id, ['required', 'class'=>'form-control'.( $errors->has('title_id') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('title_id')) <span class="form-text kt-font-danger"> {{ $errors->first('title_id') }} </span>@endif
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('url', 'URL', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::url('url', $banner->url, ['class'=>'form-control'.( $errors->has('url') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('url')) <span class="form-text kt-font-danger"> {{ $errors->first('url') }} </span>@endif
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('target', 'Target', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                            {!! Form::select('target', $utils['options']['target'], $banner->target, ['class'=>'form-control custom-select']); !!}
                                @if ($errors->has('target')) <span class="form-text kt-font-danger"> {{ $errors->first('target') }} </span>@endif
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            {!! Form::label('banner_type', 'Jenis', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::select('banner_type', $utils['options']['bannerType'], $banner->banner_type, ['class'=>'form-control']); !!}
                                @if ($errors->has('banner_type')) <span class="form-text kt-font-danger"> {{ $errors->first('banner_type') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('file', 'Upload Gambar', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-5">
                                {!! Form::file('file', ['class'=>'form-control'.( $errors->has('file') ? ' form-control-danger' : '' ) ]); !!}
                                <div style="margin-top: 20px;">
                                    <img src="{{ route(config('imagecache.route'), ['template' => 'resize-medium', 'filename' => $banner->image ]) }}" alt="" class="img-circle">
                                </div>
                                @if ($errors->has('file')) <span class="form-text kt-font-danger"> {{ $errors->first('file') }} </span>@endif
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
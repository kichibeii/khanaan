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
    
    {!! Form::open([ 'route'=>[$controller.'.store'], 'class' => 'kt-form', 'id'=>'kt_form', 'enctype'=>"multipart/form-data" ]) !!}
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
                            {!! Form::label('categories', 'Kategori', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::select('categories[]', $utils['options']['categories'], old('categories'), ['multiple', 'class'=>'form-control custom-select']); !!}
                                @if ($errors->has('categories')) <span class="form-text kt-font-danger"> {{ $errors->first('categories') }} </span>@endif
                            </div>
                        </div>
                        @php
                        $publishedOn = old('published_on') !== null ? old('published_on') : now()->format('d-m-Y H:i');
                        @endphp
                        <div class="form-group row">
                            {!! Form::label('published_on', 'Dipublish pada', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::text('published_on', $publishedOn, ['required', 'class'=>'kt_datetimepicker form-control'.( $errors->has('published_on') ? ' is-invalid' : '' ), 'autocomplete'=>'off' ]); !!}
                                @if ($errors->has('published_on')) <span class="form-text kt-font-danger"> {{ $errors->first('published_on') }} </span>@endif
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('preview', 'Preview Berita', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::textarea('preview', old('preview'), ['rows'=>4, 'class'=>'form-control'.( $errors->has('preview') ? ' is-invalid' : '' ), 'autocomplete'=>'off' ]); !!}
                                @if ($errors->has('preview')) <span class="form-text kt-font-danger"> {{ $errors->first('preview') }} </span>@endif
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('preview_id', 'Preview Berita (ID)', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::textarea('preview_id', old('preview_id'), ['rows'=>4, 'class'=>'form-control'.( $errors->has('preview_id') ? ' is-invalid' : '' ), 'autocomplete'=>'off' ]); !!}
                                @if ($errors->has('preview_id')) <span class="form-text kt-font-danger"> {{ $errors->first('preview_id') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('description', 'Konten Berita', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::textarea('description', old('description'), ['class'=>'form-control'.( $errors->has('description') ? ' is-invalid' : '' ), 'autocomplete'=>'off' ]); !!}
                                @if ($errors->has('description')) <span class="form-text kt-font-danger"> {{ $errors->first('description') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('description_id', 'Konten Berita (ID)', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::textarea('description_id', old('description_id'), ['class'=>'form-control'.( $errors->has('description_id') ? ' is-invalid' : '' ), 'autocomplete'=>'off' ]); !!}
                                @if ($errors->has('description_id')) <span class="form-text kt-font-danger"> {{ $errors->first('description_id') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('tags', 'Keyword', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::select('tags[]', [], old('tags'), ['id'=>'tags', 'required', 'multiple'=>'multiple', 'class'=>'form-control custom-select '.( $errors->has('tags') ? ' is-invalid' : '' ), 'autocomplete'=>'off']); !!}
                                <span class="form-text kt-font-info"> Gunakan koma (,) sebagai pemisah </span>
                                @if ($errors->has('tags')) <span class="form-text kt-font-danger"> {{ $errors->first('tags') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('file', 'Upload Gambar', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-5">
                                {!! Form::file('file', ['required', 'class'=>'form-control'.( $errors->has('file') ? ' form-control-danger' : '' ) ]); !!}
                                @if ($errors->has('file')) <span class="form-text kt-font-danger"> {{ $errors->first('file') }} </span>@endif
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
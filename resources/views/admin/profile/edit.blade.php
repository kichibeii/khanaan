@extends('layouts.admin.dashboard')
@section('title', $title)
@section('icon', $icon)

@section('scripts')
<script src="/js/admin/profile.js" type="text/javascript"></script>
@endsection

@section('content-subheader-toolbar')
<div class="kt-subheader__breadcrumbs">
    <a href="/" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route($controller.'.edit') }}" class="kt-subheader__breadcrumbs-link">
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
    
    {!! Form::open([ 'route'=>[$controller.'.update'], 'class' => 'kt-form', 'id'=>'kt_form', 'enctype'=>"multipart/form-data" ]) !!}
    <div class="kt-portlet__body">
        <div class="kt-form kt-form--label-right">
            <div class="kt-form__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body">
                        <div class="form-group row">
                            {!! Form::label('name', 'Nama', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::text('name', $user->name, ['required', 'class'=>'form-control'.( $errors->has('name') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('name')) <span class="form-text kt-font-danger"> {{ $errors->first('name') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('email', 'E-Mail', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-7">
                                {!! Form::email('email', $user->email, ['required', 'class'=>'form-control'.( $errors->has('email') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('email')) <span class="form-text kt-font-danger"> {{ $errors->first('email') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('file', 'Upload Foto', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-5">
                                {!! Form::file('file', ['class'=>'form-control'.( $errors->has('file') ? ' form-control-danger' : '' ) ]); !!}
                                <div style="margin-top: 20px;">
                                    <img src="{{ route(config('imagecache.route'), ['template' => 'resize-medium', 'filename' => \App\User::getImage(Auth::user()) ]) }}" alt="" class="img-circle">
                                </div>
                                @if ($errors->has('file')) <span class="form-text kt-font-danger"> {{ $errors->first('file') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('username', 'Username', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-7">
                                {!! Form::text('username', $user->username, ['required', 'class'=>'form-control'.( $errors->has('username') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('username')) <span class="form-text kt-font-danger"> {{ $errors->first('username') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('password', 'Password', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-7">
                                {!! Form::password('password', ['class'=>'form-control'.( $errors->has('password') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('password')) <span class="form-text kt-font-danger"> {{ $errors->first('password') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('password_confirmation', 'Konfirmasi Password', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-md-7">
                                {!! Form::password('password_confirmation', ['class'=>'form-control'.( $errors->has('password_confirmation') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('password_confirmation')) <span class="form-text kt-font-danger"> {{ $errors->first('password_confirmation') }} </span>@endif
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
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

@endsection
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
    
    {!! Form::open(['route' => [$controller.'.permission', $role->id], 'method'=>'PUT', 'class' => 'kt-form', 'id'=>'kt_form']) !!}
    <div class="kt-portlet__body">
        <div class="kt-form kt-form--label-right">
            <div class="kt-form__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body">
                        <div class="form-group row">
                            {!! Form::label('display_name', 'Nama', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                            <div class="col-lg-9 col-xl-6">
                                {!! Form::text('display_name', $role->display_name, ['required', 'readonly', 'class'=>'form-control'.( $errors->has('display_name') ? ' is-invalid' : '' ) ]); !!}
                                @if ($errors->has('display_name')) <span class="form-text kt-font-danger"> {{ $errors->first('display_name') }} </span>@endif
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('', 'Permission', ['class'=>'control-label col-md-3']); !!}
                            <div class="col-md-7">
                                @if (count($utils['options']['permissions']))
                                    @foreach ($utils['options']['permissions'] as $module => $modulePermissions)
                                        <div class="mb-10">
                                            <strong>{{ strtoupper(__('main.module_'.$module)) }}</strong>
                                            <div class="kt-checkbox-inline">
                                                @foreach ($modulePermissions as $permission)
                                                    @php
                                                        $checked = in_array($permission->id, $utils['options']['rolePermission']) ? true : false;
                                                    @endphp
                                                    
                                                    <label class="kt-checkbox kt-checkbox--tick kt-checkbox--success">
                                                        {!! Form::checkbox('permission['.$permission->id.']', 1, $checked, ['id'=>'check-'.$permission->id]); !!} {{$permission['operation']}}
                                                        <span></span>
                                                    </label>

                                                @endforeach
                                            </div>
                                            <br>
                                        </div>
                                    @endforeach
                                @endif
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
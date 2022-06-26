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
    </div>

    {!! Form::open([ 'route'=>[$controller.'.additionalStock.store', $product->id], 'class' => 'kt-form', 'id'=>'kt_form' ]) !!}
    <div class="kt-portlet__body">
        <div class="kt-form kt-form--label-right">
            <div class="kt-form__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body">
                        
                        <div class="row alert alert-success filter-box">
                            <div class="col-lg-4">
                                <div class="form-group row">
                                    {!! Form::label('color_id', 'Warna', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        <select name="color_id" id="color_id" class="form-control">
                                            @foreach($utils['options']['colors'] as $k => $v)
                                                <option data-role='fieldcontain' data-namecolor="{!! $v !!}" data-color="{!! $k !!}">
                                                    {!! $v !!}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group row">
                                    {!! Form::label('size_id', 'Ukuran', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        <select id="size_id" name="size_id" class="form-control">
                                            @foreach($utils['options']['size'] as $k => $v)
                                                <option data-namesize="{!! $v !!}" data-size="{!! $k !!}">
                                                {!! $v !!}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group row">
                                    {!! Form::label('qty', 'Qty', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                    {!! Form::text('qty', old('qty'), ['class'=>'is_money form-control' ]); !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                            <button type="button" class="btn btn-warning add-to-list">Add to List</button>
                            </div>
                        </div>
                        
                        <table class="table table-striped- table-bordered table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>Warna</th>
                                    <th>Size</th>
                                    <th>Qty</th>
                                    <th width="120">{{ __('main.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <tr hidden="" class="list-detail" data-tr="0-0" data-number="0">
                                    <td class="hidden"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route($controller.'.additionalStock', $product->id) }}" class="btn btn-secondary">
                        {{ __('main.back') }} 
                    </a>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection
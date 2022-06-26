@extends('layouts.admin.dashboard')
@section('title', $title)
@section('icon', $icon)

@section('styles-before')
<link href="/admin/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
<script src="/admin/plugins/custom/DataTables1-1-20/datatables.min.js" type="text/javascript"></script>
<script src="/js/admin/{{ $controller }}/list-datatable.js" type="text/javascript"></script>
@endsection

@section('content-subheader-toolbar')
<div class="kt-subheader__breadcrumbs">
    <a href="/" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route($controller.'.index') }}" class="kt-subheader__breadcrumbs-link">
        {{ $title }}                        
    </a>
</div>
@endsection

@section('content-dashboard')
<!--begin::Portlet-->
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Daftar {{ $title }}
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                @if (auth()->user()->can($controller.'-create'))
                    <a href="{{ route($controller.'.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i>
                        {{ __('main.add_new') }}
                    </a>
                    @endif
                </div>	
            </div>		
        </div>
    </div>
    
    <div class="kt-portlet__body">

        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table">
            <thead>
                <tr>
                    <th width="5">{{ __('main.no') }}</th>
                    <th>ID</th>
                    <th>{{ __('main.name') }}</th>
                    <th width="120">{{ __('main.actions') }}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
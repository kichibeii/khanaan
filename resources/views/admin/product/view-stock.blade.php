@extends('layouts.admin.dashboard')
@section('title', $title)
@section('icon', $icon)

@section('styles-before')
@endsection

@section('styles-after')
@endsection

@section('scripts')
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
    
    

    <div class="kt-portlet__body blog-content-2">
        
        <div class="blog-single-content bordered blog-container">
            <div class="blog-single-head">
                <h1 class="blog-single-head-title">{{ $product->title }}</h1>
            </div>
            <div class="blog-single-img">
                <div class="blog-single-desc">
                    @if (!count($utils['options']['colorSizes']))
                        <div class="alert alert-warning ">Stock kosong.</div>
                    @else
                        
                        @foreach ($utils['options']['colorSizes'] as $k => $v)
                            <br>
                            <h3>Warna: {!! $utils['options']['colors'][$k] !!}</h3>
                            <table id="table-{!! $k !!}" class="table table-bordered">
                                <thead>
                                <tr class="active">
                                    <th>Size</th>

                                    @foreach ($utils['options']['colorSizes'][$k] as $a => $b)
                                        <th id="{!! $k .'-'.$a !!}">{!! $utils['options']['size'][$a] !!}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Qty</td>
                                    @foreach ($utils['options']['colorSizes'][$k] as $c => $d)
                                        <td>{!! $d !!}</td>
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        @endforeach
                        
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <a href="{{ route($controller.'.index') }}" class="btn btn-secondary">
                {{ __('main.back') }} 
            </a>
        </div>
    </div>
</div>
@endsection
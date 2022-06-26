@extends('layouts.admin.dashboard')
@section('title', $title)
@section('icon', $icon)

@section('styles-before')
<link rel="stylesheet" href="/admin/plugins/jquery-ui/jquery-ui.min.css">
@endsection

@section('styles-after')

<style>
    .dropzone .dz-preview .dz-image {
        border-radius: 0px;
    }
</style>
@endsection

@section('scripts')
<script src="/admin/plugins/custom/ckeditor/ckeditor-classic.bundle.js" type="text/javascript"></script>
<script src="/admin/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="/admin/plugins/plentz-jquery-maskmoney/dist/jquery.maskMoney.min.js" type="text/javascript"></script>
<script>
    var action = 'add';
</script>
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
    <input type="hidden" id="color_checked" name="color_checked" value="{{ old('color_checked') }}">

    <div class="kt-portlet__body">
        <ul class="nav nav-tabs nav-pills" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="qty-images-tab" data-toggle="tab" href="#qty-images" role="tab" aria-controls="qty-images" aria-selected="false">Color, Qty & Images</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="discount-tab" data-toggle="tab" href="#discount" role="tab" aria-controls="discount" aria-selected="false">Discount</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="new-releases-tab" data-toggle="tab" href="#new-releases" role="tab" aria-controls="new-releases" aria-selected="false">New Releases</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="recomended-tab" data-toggle="tab" href="#recomended" role="tab" aria-controls="recomended" aria-selected="false">Recomended</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="kt-form kt-form--label-right">
                    <div class="kt-form__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    {!! Form::label('code', 'Kode Produk', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        {!! Form::text('code', old('code'), ['required', 'class'=>'form-control'.( $errors->has('code') ? ' is-invalid' : '' ) ]); !!}
                                        @if ($errors->has('code')) <span class="form-text kt-font-danger"> {{ $errors->first('code') }} </span>@endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('title', 'Nama Produk', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        {!! Form::text('title', old('title'), ['required', 'class'=>'form-control'.( $errors->has('title') ? ' is-invalid' : '' ) ]); !!}
                                        @if ($errors->has('title')) <span class="form-text kt-font-danger"> {{ $errors->first('title') }} </span>@endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('brand_id', 'Brand', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        {!! Form::select('brand_id', [], old('brand_id'), ['class'=>'form-control']); !!}
                                        @if ($errors->has('brand_id')) <span class="form-text kt-font-danger"> {{ $errors->first('brand_id') }} </span>@endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('size_id', __('main.sizes'), ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        {!! Form::select('size_id', [], old('size_id'), ['class'=>'form-control']); !!}
                                        @if ($errors->has('size_id')) <span class="form-text kt-font-danger"> {{ $errors->first('size_id') }} </span>@endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('categories', 'Kategori', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        {!! Form::select('categories[]', $utils['options']['categories'], old('categories'), ['multiple', 'class'=>'form-control custom-select']); !!}
                                        @if ($errors->has('categories')) <span class="form-text kt-font-danger"> {{ $errors->first('categories') }} </span>@endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('collections', 'Collection', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        {!! Form::select('collections[]', $utils['options']['collections'], old('collections'), ['multiple', 'class'=>'form-control custom-select']); !!}
                                        @if ($errors->has('collections')) <span class="form-text kt-font-danger"> {{ $errors->first('collections') }} </span>@endif
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
                                    {!! Form::label('price', 'Harga', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-3 col-xl-4">
                                        {!! Form::text('price', old('price'), ['required', 'class'=>'text-right is_money form-control'.( $errors->has('price') ? ' is-invalid' : '' ) ]); !!}
                                        @if ($errors->has('price')) <span class="form-text kt-font-danger"> {{ $errors->first('price') }} </span>@endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('weight', 'Berat (Gram)', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-3 col-xl-4">
                                        {!! Form::text('weight', old('weight'), ['required', 'class'=>'text-right is_money form-control'.( $errors->has('weight') ? ' is-invalid' : '' ) ]); !!}
                                        @if ($errors->has('weight')) <span class="form-text kt-font-danger"> {{ $errors->first('weight') }} </span>@endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('description', 'Deskripsi', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        {!! Form::textarea('description', old('description'), ['class'=>'form-control'.( $errors->has('description') ? ' is-invalid' : '' ), 'autocomplete'=>'off' ]); !!}
                                        @if ($errors->has('description')) <span class="form-text kt-font-danger"> {{ $errors->first('description') }} </span>@endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('description_id', 'Deskripsi (ID)', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
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
                                    {!! Form::label('file', 'Upload Gambar Utama ke-1', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-md-5">
                                        <input type="hidden" id="image-1" name="image[1]" value="">
                                        <div>
                                            <div class="dropzone main-image" data-id="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('file', 'Upload Gambar Utama ke-2', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-md-5">
                                        <input type="hidden" id="image-2" name="image[2]" value="">
                                        <div>
                                            <div class="dropzone main-image" data-id="2">
                                            </div>
                                        </div>
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
            
            <div class="tab-pane fade" id="qty-images" role="tabpanel" aria-labelledby="qty-images-tab">
            <div class="kt-form kt-form--label-right">
                    <div class="kt-form__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    {!! Form::label('colors', 'Warna', ['class'=>'col-xl-2 col-lg-2 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        
                                        <div class="kt-checkbox-list">
                                            @foreach ($utils['options']['colors'] as $color)
                                                @if (!in_array($color->id, $utils['options']['colorSelected']))
                                                <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                                    <input type="checkbox" id="color_{!! $color->id !!}" name="colors[]" value="{!! $color->id !!}" class="colors"> {!! $color->title !!}
                                                    <span></span>
                                                </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                @foreach ($utils['options']['colors'] as $color)
                                <div class="form-group row form-group-color d-none" id="form-group-{{ $color->id }}">
                                    <div class="col-lg-12 col-xl-12">
                                        <div class="kt-portlet">
                                            <div class="kt-portlet__head">
                                                <div class="kt-portlet__head-label">
                                                    <span class="kt-portlet__head-icon">
                                                        <i class="flaticon-squares"></i>
                                                    </span>
                                                    <h3 class="kt-portlet__head-title" style="color: #{{ $color->color_hex }}">
                                                        {{ $color->title }}
                                                    </h3>
                                                </div>
                                                <div class="kt-portlet__head-toolbar">
                                                    <div class="kt-portlet__head-actions">
                                                        <a class="btn btn-default btn-pill btn-sm btn-icon btn-icon-md" data-toggle="modal" data-target="#modalSize{!! $color->id !!}">
                                                            <i class="flaticon2-gear"></i>
                                                        </a>

                                                        <!-- Modal -->
                                                        <div class="modal" tabindex="-1" role="dialog" id="modalSize{!! $color->id !!}">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Pilih Ukuran Untuk Warna {!! $color->title !!}</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="kt-checkbox-list">
                                                                        @foreach ($utils['options']['size'] as $k => $v)
                                                                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                                                                <input type="checkbox" class="size_color_{!! $color->id !!}" value="{!! $k !!}" data-title="{!! $v !!}" {!! isset($arrColorSizeQty[$color->id][$k]) ? 'checked' : '' !!}> {!! $v !!}
                                                                                <span></span>
                                                                            </label>
                                                                        @endforeach
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="button" class="btn btn-primary btnCheckSize" data-id="{!! $color->id !!}" data-dismiss="modal">Save changes</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="kt-portlet__body">
                                                <table id="table-{{ $color->id }}" class="table table-bordered" >
                                                    <thead>
                                                        <tr class="active">
                                                            <th>Size</th>
                                                            @if (count($utils['options']['arrColorSizeQty']))
                                                                @foreach ($sizes as $k => $v)
                                                                    @if (isset($utils['options']['arrColorSizeQty'][$color->id]) && array_key_exists($k, $utils['options']['arrColorSizeQty'][$color->id]))
                                                                        <th id="{!! $color->id .'-'.$k !!}">{!! $v !!}</th>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Qty Awal</td>
                                                            @if (count($utils['options']['arrColorSizeQty']))
                                                                @foreach ($utils['options']['size'] as $k => $v)
                                                                    @if (isset($utils['options']['arrColorSizeQty'][$color->id]) && array_key_exists($k, $utils['options']['arrColorSizeQty'][$color->id]))
                                                                        <td><input type="text" class="form-control" name="size[{!! $color->id !!}][{!! $k !!}]" value="{!! $utils['options']['arrColorSizeQty'][$color->id][$k] !!}"></td>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div id="hidden-{!! $color->id !!}">
                                                    @if (isset($utils['options']['arrColorImage'][$color->id]))
                                                        @foreach ($utils['options']['arrColorImage'][$color->id] as $image)
                                                            <input name="images[{!! $color->id !!}][]" type="hidden" data-val="{!! $image->image !!}" value="{!! $image->image !!}">
                                                        @endforeach
                                                    @endif
                                                </div>

                                                <div id="upload-photo-{!! $color->id !!}" class="upload-photo dropzone dropzone-button" data-id="{!! $color->id !!}">
                                                    Upload Foto
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                
                
            </div>
            
            <div class="tab-pane fade" id="discount" role="tabpanel" aria-labelledby="discount-tab">
                <table id="table-discount" class="table table-bordered " data-add="1">
                    <thead>
                    <tr>
                        <th>Priority</th>
                        <th>Price</th>
                        <th>Date Start</th>
                        <th>Date End</th>
                        <th width="80"></th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <a href="javascript:;" class="add-discount d-none" data-add="1"></a>
            </div>
            
            <div class="tab-pane fade" id="new-releases" role="tabpanel" aria-labelledby="new-releases-tab">
                <table id="table-new_release" class="table table-bordered " data-add="1">
                    <thead>
                    <tr>
                        <th>Priority</th>
                        <th>Date Start</th>
                        <th>Date End</th>
                        <th width="80"></th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <a href="javascript:;" class="add-new_release d-none" data-add="1"></a>
            </div>
            
            <div class="tab-pane fade" id="recomended" role="tabpanel" aria-labelledby="recomended-tab">
                <table id="table-best_seller" class="table table-bordered " data-add="1">
                    <thead>
                    <tr>
                        <th>Priority</th>
                        <th>Date Start</th>
                        <th>Date End</th>
                        <th width="80"></th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <a href="javascript:;" class="add-best_seller d-none" data-add="1"></a>
            </div>
        </div>



        
    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route($controller.'.index') }}" class="btn btn-secondary">
                {{ __('main.back') }} 
            </a>
        </div>
    </div>
    {!! Form::close() !!}
</div>

@endsection
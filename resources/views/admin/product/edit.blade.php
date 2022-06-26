@extends('layouts.admin.dashboard')
@section('title', $title)
@section('icon', $icon)

@section('styles-before')
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
<script src="/admin/plugins/plentz-jquery-maskmoney/dist/jquery.maskMoney.min.js" type="text/javascript"></script>
<script>
    var action = 'edit';
</script>
<script src="/js/admin/{{ $controller }}/add.js" type="text/javascript"></script>

<script>
    var brandSelect = $('#brand_id');
    $.ajax({
        type: 'GET',
        url: '/brand/{{ $product->brand_id }}/get-detail'
    }).then(function (data) {
        var option = new Option(data.text, data.id, true, true);
        brandSelect.append(option).trigger('change');

        brandSelect.trigger({
            type: 'select2:select',
            params: {
                data: data
            }
        });
    });

    var sizeSelect = $('#size_id');
    $.ajax({
        type: 'GET',
        url: '/sizes/{{ $product->size_id }}/get-detail'
    }).then(function (data) {
        var option = new Option(data.text, data.id, true, true);
        sizeSelect.append(option).trigger('change');

        sizeSelect.trigger({
            type: 'select2:select',
            params: {
                data: data
            }
        });
    });
</script>
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
    
    {!! Form::open(['route' => [$controller.'.update', $product->id], 'method'=>'PUT', 'class' => 'kt-form', 'id'=>'kt_form', 'enctype'=>"multipart/form-data"]) !!}
    <input type="hidden" id="color_checked" name="color_checked" value="{{ old('color_checked') }}">

    <div class="kt-portlet__body">
        <ul class="nav nav-tabs nav-pills" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
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
                                        {!! Form::text('code', $product->code, ['required', 'class'=>'form-control'.( $errors->has('code') ? ' is-invalid' : '' ) ]); !!}
                                        @if ($errors->has('code')) <span class="form-text kt-font-danger"> {{ $errors->first('code') }} </span>@endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('title', 'Nama Produk', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        {!! Form::text('title', $product->title, ['required', 'class'=>'form-control'.( $errors->has('title') ? ' is-invalid' : '' ) ]); !!}
                                        @if ($errors->has('title')) <span class="form-text kt-font-danger"> {{ $errors->first('title') }} </span>@endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('brand_id', 'Brand', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        {!! Form::select('brand_id', [], $product->brand_id, ['class'=>'form-control']); !!}
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
                                        <select class="form-control" multiple="multiple" name="categories[]" id="categories" required="">
                                            @foreach ($utils['options']['categories'] as $k => $v)
                                            <option value="{{ $k }}" {{ in_array($k, $utils['options']['categoriesSelected']) ? 'selected' : '' }}>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('categories')) <span class="form-text kt-font-danger"> {{ $errors->first('categories') }} </span>@endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('collections', 'Collection', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" multiple="multiple" name="collections[]" id="collections" >
                                            @foreach ($utils['options']['collections'] as $k => $v)
                                            <option value="{{ $k }}" {{ in_array($k, $utils['options']['collectionsSelected']) ? 'selected' : '' }}>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('collections')) <span class="form-text kt-font-danger"> {{ $errors->first('collections') }} </span>@endif
                                    </div>
                                </div>
                                @php
                                $publishedOn = date('d-m-Y H:i', strtotime($product->published_on));
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
                                        {!! Form::text('price', numberFormat($product->price), ['required', 'class'=>'text-right is_money form-control'.( $errors->has('price') ? ' is-invalid' : '' ) ]); !!}
                                        @if ($errors->has('price')) <span class="form-text kt-font-danger"> {{ $errors->first('price') }} </span>@endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('weight', 'Berat (Gram)', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-3 col-xl-4">
                                        {!! Form::text('weight', numberFormat($product->weight), ['required', 'class'=>'text-right is_money form-control'.( $errors->has('weight') ? ' is-invalid' : '' ) ]); !!}
                                        @if ($errors->has('weight')) <span class="form-text kt-font-danger"> {{ $errors->first('weight') }} </span>@endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('description', 'Deskripsi', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        {!! Form::textarea('description', $product->description, ['class'=>'form-control'.( $errors->has('description') ? ' is-invalid' : '' ), 'autocomplete'=>'off' ]); !!}
                                        @if ($errors->has('description')) <span class="form-text kt-font-danger"> {{ $errors->first('description') }} </span>@endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('description_id', 'Deskripsi (ID)', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        {!! Form::textarea('description_id', $product->description_id, ['class'=>'form-control'.( $errors->has('description_id') ? ' is-invalid' : '' ), 'autocomplete'=>'off' ]); !!}
                                        @if ($errors->has('description_id')) <span class="form-text kt-font-danger"> {{ $errors->first('description_id') }} </span>@endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('tags', 'Keyword', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" multiple="multiple" name="tags[]" id="tags" required="">
                                            @foreach ($product->getTags() as $k => $v)
                                            <option value="{{ $v }}" selected>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                        <span class="form-text kt-font-info"> Gunakan koma (,) sebagai pemisah </span>
                                        @if ($errors->has('tags')) <span class="form-text kt-font-danger"> {{ $errors->first('tags') }} </span>@endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('file', 'Upload Gambar Utama ke-1', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-md-5">
                                        <input type="hidden" id="image-1" name="image[1]" value="">
                                        <div>
                                            <div class="dropzone main-image" data-id="1" data-image="{{ '/imagecache/product-show/' . $product->id.'/'.$product->image }}" data-product_id="{{ $product->id }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('file', 'Upload Gambar Utama ke-2', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-md-5">
                                        <input type="hidden" id="image-2" name="image[2]" value="">
                                        <div>
                                            <div class="dropzone main-image" data-id="2" data-image="{{ '/imagecache/product-show/' . $product->id.'/'.$product->image_second }}" data-product_id="{{ $product->id }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('status', 'Status', ['class'=>'col-xl-3 col-lg-3 col-form-label']); !!}
                                    <div class="col-md-8">
                                        <div class="kt-radio-inline">
                                            @php
                                            $optionSelected = $product->status;
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
            
            
            
            <div class="tab-pane fade" id="discount" role="tabpanel" aria-labelledby="discount-tab">
                <table id="table-discount" class="table table-bordered " data-add="{{ count($product->discounts) > 0 ? 0 : 1 }}">
                    <thead>
                    <tr>
                        <th>Priority</th>
                        <th>Price</th>
                        <th>Date Start</th>
                        <th>Date End</th>
                        <th width="80"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @if (count($product->discounts))
                            @foreach ($product->discounts as $index => $discount)
                                <tr id='{{ $index }}'>
                                    <td>
                                        <input type='number' min='0' class='form-control ' name='discounts[priority][]' value="{{ $discount->priority }}">
                                    </td>
                                    <td>
                                        <input type='text' class='form-control text-right is_money' name='discounts[price][]' value="{{ numberFormat($discount->price) }}">
                                    </td>
                                    <td>
                                        <input type='text' class='form-control kt_datetimepicker' name='discounts[date_start][]' value="{{ date('d-m-Y', strtotime($discount->date_start)) }}">
                                    </td>
                                    <td>
                                        <input type='text' class='form-control kt_datetimepicker' name='discounts[date_end][]' value="{{ !is_null($discount->date_end) ? date('d-m-Y', strtotime($discount->date_end)) : '' }}">
                                    </td>
                                    <td class='text-center'></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <a href="javascript:;" class="add-discount d-none" data-add="{{ count($product->discounts) > 0 ? 0 : 1 }}"></a>
            </div>
            
            <div class="tab-pane fade" id="new-releases" role="tabpanel" aria-labelledby="new-releases-tab">
                <table id="table-new_release" class="table table-bordered " data-add="{{ count($product->newReleases) > 0 ? 0 : 1 }}">
                    <thead>
                    <tr>
                        <th>Priority</th>
                        <th>Date Start</th>
                        <th>Date End</th>
                        <th width="80"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @if (count($product->newReleases))
                            @foreach ($product->newReleases as $index => $new)
                                <tr id='{{ $index }}'>
                                    <td>
                                        <input type='number' min='0' class='form-control ' name='new_releases[priority][]' value="{{ $new->priority }}">
                                    </td>
                                    <td>
                                        <input type='text' class='form-control kt_datetimepicker' name='new_releases[date_start][]' value="{{ date('d-m-Y', strtotime($new->date_start)) }}">
                                    </td>
                                    <td>
                                        <input type='text' class='form-control kt_datetimepicker' name='new_releases[date_end][]' value="{{ !is_null($new->date_end) ? date('d-m-Y', strtotime($new->date_end)) : '' }}">
                                    </td>
                                    <td class='text-center'></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <a href="javascript:;" class="add-new_release d-none" data-add="{{ count($product->newReleases) > 0 ? 0 : 1 }}"></a>
            </div>
            
            <div class="tab-pane fade" id="recomended" role="tabpanel" aria-labelledby="recomended-tab">
                <table id="table-best_seller" class="table table-bordered " data-add="{{ count($product->recomendeds) > 0 ? 0 : 1 }}">
                    <thead>
                    <tr>
                        <th>Priority</th>
                        <th>Date Start</th>
                        <th>Date End</th>
                        <th width="80"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @if (count($product->newReleases))
                            @foreach ($product->recomendeds as $index => $recomended)
                                <tr id='{{ $index }}'>
                                    <td>
                                        <input type='number' min='0' class='form-control ' name='best_sellers[priority][]' value="{{ $recomended->priority }}">
                                    </td>
                                    <td>
                                        <input type='text' class='form-control kt_datetimepicker' name='best_sellers[date_start][]' value="{{ date('d-m-Y', strtotime($recomended->date_start)) }}">
                                    </td>
                                    <td>
                                        <input type='text' class='form-control kt_datetimepicker' name='best_sellers[date_end][]' value="{{ !is_null($recomended->date_end) ? date('d-m-Y', strtotime($recomended->date_end)) : '' }}">
                                    </td>
                                    <td class='text-center'></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <a href="javascript:;" class="add-best_seller d-none" data-add="{{ count($product->recomendeds) > 0 ? 0 : 1 }}"></a>
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
</div>
@endsection
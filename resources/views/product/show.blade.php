@extends('layouts.veera')
@section('title', $utils['title'] )
@section('menuActive', 'shop')

@section('styles-after')
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
<style>
    .toast-message{
        font-size: 90%;
    }

    .product-desc ul{
        padding-left: 20px;
    }
    .product-desc ul li{
        list-style: circle!important;
    }
</style>
@endsection

@section('scripts')
<!--=== Revolution Slider Js ===-->
<script src="/assets/js/vendor/imagesloaded.pkgd.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(function () { //ready
    @if(session('success'))
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        toastr.success("{!! session('success') !!}");
    @endif

    @if(session('error'))
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        toastr.warning("{!! session('error') !!}", "{{ __('main.failed') }}");
    @endif
});

</script>
@endsection



@section('content')
@php
$idr = 1;
$currentCurrency = currentCurrency();
if ($currentCurrency == 'usd'){
    $idr = \App\Setting::getValue('dollar');
}
@endphp

<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2>{{ __('main.shop') }}</h2>
                    <nav class="page-breadcrumb">
                        <ul class="d-flex justify-content-center">
                            <li><a href="{{ route('home') }}">{{ __('main.home') }}</a></li>
                            <li><a href="{{ route('shop') }}" class="active">{{ __('main.shop') }}</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Header Wrapper -->

<!--== Start Single Product Page Wrapper ==-->
<div id="single-product-page" class="pt-90 pt-md-60 pt-sm-50 pb-92 pb-md-58 pb-sm-50">
    <div class="container-fluid">
        <div class="row">
            <!-- Start Single Product Thumbnail -->
            <div class="col-xl-7 col-lg-6">

                    @foreach ($utils['colors'] as $index => $color)
                    <div class="single-product-thumb-wrap tab-style-left p-0 pb-sm-30 pb-md-30 {{ $index > 0 ? '' : '' }}" data-color="{{ $color->id }}">
                        <!-- Product Thumbnail Large View -->
                        <div class="product-thumb-large-view " id="product-thumb-large-view-{{ $color->id }}">
                            <div class="product-thumb-carouselx vertical-tab" id="product-thumb-carousel-{{ $color->id }}">
                                @foreach ($utils['images'][$color->id] as $image)
                                <figure class="product-thumb-item">
                                    <img src="{{ route(config('imagecache.route'), ['template' => 'product-show', 'filename' => $product->id.'/'.$image ]) }}" alt="{{ $product->title }}"/>
                                </figure>
                                @endforeach
                            </div>

                            <!-- Product Thumb Button  -->
                            <div class="product-thumb-btns">
                                <button class="btn-zoom-popup" data-id="{{ $color->id }}"><i class="dl-icon-zoom-in"></i></button>
                            </div>
                        </div>

                        <!-- Product Thumbnail Nav -->
                        <div class="vertical-tab-nav" id="vertical-tab-nav-{{ $color->id }}">
                            @foreach ($utils['images'][$color->id] as $image)
                            <figure class="product-thumb-item">
                                <img src="{{ route(config('imagecache.route'), ['template' => 'product-show', 'filename' => $product->id.'/'.$image ]) }}" alt="{{ $product->title }}"/>
                            </figure>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

            </div>
            <!-- End Single Product Thumbnail -->

            <!-- Start Single Product Content -->
            <div class="col-xl-5 col-lg-6">
                <div class="single-product-content-wrapper">
                    <div class="single-product-details">
                        <h2 class="product-name">{{ $product->title }}</h2>
                        <div class="prices-stock-status d-flex align-items-center justify-content-between">
                            @php
                            $price = getPrice($currentCurrency, $idr, $product->price);
                            $oldPrice = getPrice($currentCurrency, $idr, $product->price);
                            if ($product->discount > 0){
                                $price = getPrice($currentCurrency, $idr, $product->discount);
                            }
                            @endphp
                            <div class="prices-group">
                                @if ($product->discount > 0)
                                <del class="old-price">{{ displayPrice($currentCurrency, $oldPrice) }}</del>
                                @endif
                                <span class="price">{{ displayPrice($currentCurrency, $price) }}</span>
                            </div>
                            <span class="stock-status"><i class="{{ $product->qty > 0 ? 'dl-icon-check-circle1' : '' }}"></i> {{ __('main.in_stock') }}</span>
                        </div>
                        <div class="product-desc">
                            {!! getTextLang($product, 'description') !!}

                        </div>


                        @if (!is_null($product->size_id))
                        <div class="find-store-delivery d-flex align-items-center justify-content-between">
                            <div></div>
                            <span><a href="" data-toggle="modal" data-target="#sizeModal"><i class="fa fa-female"></i> {{ __('main.size_guide') }}</a></span>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="sizeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ __('main.size_guide') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <img src="{{ route(config('imagecache.route'), ['template' => 'real', 'filename' => $product->size->getImage() ]) }}" alt="" class="img-fluid">
                                </div>
                            </div>
                            </div>
                        </div>
                        @endif


                        <div class="prod-configurable-content mt-38 mt-sm-24">
                            <div class="configurable-item">
                                <h5 class="configurable-name">{{ __('main.color') }}: <b><span id="color-title">{{ $utils['colors'][0]->title }}</span></b></h5>
                                <ul class="configurable-list nav">
                                    @foreach ($utils['colors'] as $index => $color)
                                    <li class="color-button" data-slick="0" data-title="{{ $color->title }}" data-id="{{ $color->id }}" data-toggle="tooltip" data-placement="top" title="{{ $color->title }}"><div style="width:100%;height:100%;background-color:#{{ $color->color_hex }}"></div></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="configurable-item">
                                <h5 class="configurable-name">{{ __('main.size') }}: <b><span id="size-title"></span></b></h5>
                                <ul class="configurable-list nav" id="list-size" style="height: 45px;">
                                </ul>
                            </div>
                        </div>
                        {!! Form::open([ 'route'=>['shop.addToCart', $product->slug], 'class' => 'kt-form', 'id'=>'kt_form', 'enctype'=>"multipart/form-data" ]) !!}
                        <div id="div-form">
                            <div class="quantity-btn-group  d-flex ">
                                <div class="pro-qty">
                                    <input type="text" id="quantity" name="qty" value="1" data-max="0"/>
                                </div>
                                <div class="list-btn-group">
                                    <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="color_id" id="color_id" value="0">
                                    <input type="hidden" name="size_id" id="size_id" value="0">
                                    <button class="btn btn-black" type="submit" id="button-submit">{{ __('main.add_to_cart') }}</button>
                                </div>
                            </div>
                            <div class="msg-qty text-danger d-none">{{ __('main.out_of_stock') }}</div>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    <div class="single-product-footer d-block d-sm-flex justify-content-between">
                        <div class="prod-footer-left mb-xs-26">
                            <span class="sku mb-6 d-block">{{ __('main.code') }}: {{ $product->code }}</span>
                            <ul class="prod-footer-list">
                                <li class="list-name">{{ __('main.categories') }}:</li>
                                {!! $utils['categories'] !!}
                            </ul>
                        </div>

                        <div class="prod-footer-right">
                            <dl class="social-share">
                                <dt>{{ __('main.share_with') }}</dt>
                                <dd><a href="#"><i class="fa fa-facebook"></i></a></dd>
                                <dd><a href="#"><i class="fa fa-twitter"></i></a></dd>
                                <dd><a href="#"><i class="fa fa-pinterest-p"></i></a></dd>
                                <dd><a href="#"><i class="fa fa-google-plus"></i></a></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Single Product Content -->
        </div>
    </div>
</div>
<!--== End Single Product Page Wrapper ==-->

@if (count($utils['relateds']))
<!--== Start Related Products Area ==-->
<section id="related-products-wrapper" class="pb-48 pb-md-18 pb-sm-8">
    <div class="container-fluid">
        <div class="row">
            <!-- Start Section title -->
            <div class="col-lg-8 m-auto text-center">
                <div class="section-title-wrap">
                    <h2>{{ __('main.related_products') }}</h2>
                </div>
            </div>
            <!-- End Section title -->
        </div>

        <div class="row products-on-column">
            @foreach ($utils['relateds'] as $productRelated)
            @php
            $price = getPrice($currentCurrency, $idr, $productRelated->price);
            $oldPrice = getPrice($currentCurrency, $idr, $productRelated->price);
            if ($productRelated->discount > 0){
                $price = getPrice($currentCurrency, $idr, $productRelated->discount);
            }
            @endphp
            <!-- Start Single Product -->
            <div class="col-sm-6 col-lg-3">
                <div class="single-product-wrap">
                    <!-- Product Thumbnail -->
                    <figure class="product-thumbnail">
                        <a href="{{ route('shop.show', $productRelated->slug) }}" class="d-block">
                            <img class="primary-thumb" src="{{ route(config('imagecache.route'), ['template' => 'product-list', 'filename' => $productRelated->getImage() ]) }}" alt="{{ $productRelated->title }}"/>
                            <img class="secondary-thumb" src="{{ route(config('imagecache.route'), ['template' => 'product-list', 'filename' => $productRelated->getImage(true) ]) }}" alt="{{ $productRelated->title }}"/>
                        </a>
                        <figcaption class="product-hvr-content">
                            @if ($productRelated->discount > 0)
                                <span class="product-badge sale">{{ __('main.sale') }}</span>
                            @else
                                @if (!is_null($productRelated->hot))
                                    @if (!is_null($productRelated->newRelease))
                                        <span class="product-badge">{{ __('main.new') }}</span>
                                    @else
                                        <span class="product-badge hot">{{ __('main.hot') }}</span>
                                    @endif
                                @elseif (!is_null($productRelated->newRelease))
                                    <span class="product-badge">{{ __('main.new') }}</span>
                                @endif
                            @endif
                        </figcaption>
                    </figure>

                    <!-- Product Details -->
                    <div class="product-details">
                        <h2 class="product-name"><a href="{{ route('shop.show', $productRelated->slug) }}">{{ $productRelated->title }}</a></h2>
                        <div class="product-prices">
                            @if ($productRelated->discount > 0)
                            <del class="oldPrice">{{ displayPrice($currentCurrency, $oldPrice) }}</del>
                            @endif
                            <span class="price">{{ displayPrice($currentCurrency, $price) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Single Product -->
            @endforeach
        </div>
    </div>
</section>
<!--== End Related Products Area ==-->
@endif

@endsection

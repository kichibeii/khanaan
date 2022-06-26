@extends('layouts.veera')
@section('title', $utils['title'] )
@section('menuActive', $utils['menuActive'])
@section('scripts')
<script>
$('body').on('click','.btn-search',function(){
    var search = $('#i-product');
    var val = search.val();
    var url = search.data('url');
    if(val){
        url = url+"&search="+val;
    }
    window.location.replace(url);
})
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

<!--== Start Shop Page Wrapper ==-->
<div id="shop-page-wrapper" class="pt-86 pt-md-56 pt-sm-46 pb-50 pb-md-20 pb-sm-10">
    <div class="container">
        <div class="row">
            <!-- Start Sidebar Area Wrapper -->
            <div class="col-lg-3 order-last order-lg-first mt-md-54 mt-sm-44">
                <div class="sidebar-area-wrapper">

                    <!-- Start Single Sidebar -->
                    <div class="single-sidebar-wrap">
                        <div class="input-group mb-5 d-none">
                            <input type="text" id="i-product" value="{{ $utils['search'] }}" data-url="{{ route($utils['baseUrl'], $utils['url']) }}" placeholder="Search by product" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" autocomplete="off">
                            <div class="input-group-prepend">
                                <button class="btn btn-primary btn-search" style="padding:0px 10px" type="button"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <h3 class="sidebar-title">{{ __('main.brand') }}</h3>
                        <div class="sidebar-body">
                            <ul class="sidebar-list">
                                @foreach ($utils['brands'] as $k => $v)
                                @php
                                $brandAppend = $utils['append'];
                                unset($brandAppend['brand_id']);
                                $brandAppend['brand_id'] = $k;
                                @endphp
                                <li><a class="{{ isset($utils['brandSelected']) && $utils['brandSelected']['id'] == $k ? 'active' : '' }}" href="{{ route($utils['baseUrl'], $brandAppend) }}">{{ $v }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- End Single Sidebar -->

                    <!-- Start Single Sidebar -->
                    <div class="single-sidebar-wrap">

                        <h3 class="sidebar-title">{{ __('main.categories') }}</h3>
                        <div class="sidebar-body">
                            <ul class="sidebar-list">
                                @foreach ($utils['categories'] as $k => $v)
                                @php
                                $catAppend = $utils['append'];
                                unset($catAppend['cat_id']);
                                $catAppend['cat_id'] = $k;
                                @endphp
                                <li><a class="{{ isset($utils['categorySelected']) && $utils['categorySelected']['id'] == $k ? 'active' : '' }}" href="{{ route($utils['baseUrl'], $catAppend) }}">{{ $v }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- End Single Sidebar -->

                    <!-- Start Single Sidebar -->
                    <div class="single-sidebar-wrap">
                        <h3 class="sidebar-title">{{ __('main.color') }}</h3>
                        <div class="sidebar-body">
                            <ul class="sidebar-list">
                                @foreach ($utils['colors'] as $k => $v)
                                @php
                                $colorAppend = $utils['append'];
                                unset($colorAppend['color_id']);
                                $colorAppend['color_id'] = $k;
                                @endphp
                                <li><a class="{{ isset($utils['colorSelected']) && $utils['colorSelected']['id'] == $k ? 'active' : '' }}" href="{{ route($utils['baseUrl'], $colorAppend) }}">{{ $v }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- End Single Sidebar -->

                    <!-- Start Single Sidebar -->
                    <div class="single-sidebar-wrap">
                        <h3 class="sidebar-title">{{ __('main.size') }}</h3>
                        <div class="sidebar-body">
                            <ul class="size-list">
                                @foreach ($utils['sizes'] as $k => $v)
                                @php
                                $sizeAppend = $utils['append'];
                                unset($sizeAppend['size_id']);
                                $sizeAppend['size_id'] = $k;
                                @endphp
                                <li><a class="{{ isset($utils['sizeSelected']) && $utils['sizeSelected']['id'] == $k ? 'active' : '' }}" href="{{ route('shop', $sizeAppend) }}">{{ $v }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- End Single Sidebar -->
                </div>
            </div>
            <!-- End Sidebar Area Wrapper -->

            <!-- Start Shop Page Product Area -->
            <div class="col-lg-9">
                <!-- Start Product Config Area -->
                <div class="product-config-area d-md-flex justify-content-between align-items-center">
                    <div class="product-config-left d-sm-flex">
                        <p>{{ __('main.showing') }} {{ $products->firstItem() }}–{{ $products->lastItem() }} {{ __('main.of') }} {{ $products->total() }} {{ __('main.results') }}</p>
                        <ul class="product-show-quantity nav mt-xs-14">
                            <li>{{ __('main.show') }}</li>
                            @foreach ($utils['arrLimit'] as $limit)
                            @php
                            $limitAppend = $utils['append'];
                            unset($limitAppend['limit']);
                            $limitAppend['limit'] = $limit;
                            @endphp
                            <li><a href="{{ route('shop', $limitAppend) }}" class="{{ $utils['append']['limit'] == $limit ? 'active' : '' }}">{{ $limit }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="product-config-right d-flex align-items-center mt-sm-14">
                        <ul class="product-view-mode">
                            <li data-viewmode="grid-view" class="active"><i class="fa fa-th"></i></li>
                            <li data-viewmode="list-view"><i class="fa fa-list"></i></li>
                        </ul>
                        <ul class="product-filter-sort">
                            <li class="dropdown-show sort-by">
                                <button class="arrow-toggle">{{ __('main.sort_by') }}</button>
                                <ul class="dropdown-nav">
                                    @php
                                    $orderAppend = $utils['append'];
                                    unset($orderAppend['order']);
                                    unset($orderAppend['order_by']);
                                    @endphp

                                    @php
                                    $orderAppend['order'] = 'published_on';
                                    $orderAppend['order_by'] = 'desc';
                                    @endphp
                                    <li><a href="{{ route('shop', $orderAppend) }}" class="{{ $utils['append']['order'] == 'published_on' && $utils['append']['order_by'] == 'desc' ? 'active' : '' }}">{{ __('main.sort_by_newest') }}</a></li>

                                    @php
                                    $orderAppend['order'] = 'price';
                                    $orderAppend['order_by'] = 'asc';
                                    @endphp
                                    <li><a href="{{ route('shop', $orderAppend) }}" class="{{ $utils['append']['order'] == 'price' && $utils['append']['order_by'] == 'asc' ? 'active' : '' }}">{{ __('main.sort_by_price_low_to_high') }}</a></li>

                                    @php
                                    $orderAppend['order'] = 'price';
                                    $orderAppend['order_by'] = 'desc';
                                    @endphp
                                    <li><a href="{{ route('shop', $orderAppend) }}" class="{{ $utils['append']['order'] == 'price' && $utils['append']['order_by'] == 'desc' ? 'active' : '' }}">{{ __('main.sort_by_price_high_to_low') }}</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- End Product Config Area -->

                @if (isset($utils['brandSelected']) || isset($utils['categorySelected']) || isset($utils['colorSelected']) || isset($utils['sizeSelected']))
                <div class="product-config-area mt-30">
                    Filter by:
                    @if (isset($utils['brandSelected']))

                        <span class="badge badge-pill badge-light">
                            Brand: {{ $utils['brandSelected']['title'] }}
                            @php
                            $brandAppend = $utils['append'];
                            unset($brandAppend['brand_id']);
                            @endphp
                            <a href="{{ route('shop', $brandAppend) }}">
                                <span aria-hidden="true">&times;</span>
                            </a>
                        </span>
                    @endif

                    @if (isset($utils['categorySelected']))

                        <span class="badge badge-pill badge-light">
                            Category: {{ $utils['categorySelected']['title'] }}
                            @php
                            $catAppend = $utils['append'];
                            unset($catAppend['action']);
                            @endphp
                            <a href="{{ route('shop', $catAppend) }}">
                                <span aria-hidden="true">&times;</span>
                            </a>
                        </span>
                    @endif
                    @if (isset($utils['colorSelected']))

                        <span class="badge badge-pill badge-light">
                            Color: {{ $utils['colorSelected']['title'] }}
                            @php
                            $colorAppend = $utils['append'];
                            unset($colorAppend['color_id']);
                            @endphp
                            <a href="{{ route('shop', $colorAppend) }}">
                                <span aria-hidden="true">&times;</span>
                            </a>
                        </span>
                    @endif
                    @if (isset($utils['sizeSelected']))

                        <span class="badge badge-pill badge-light">
                            Size: {{ $utils['sizeSelected']['title'] }}
                            @php
                            $sizeAppend = $utils['append'];
                            unset($sizeAppend['size_id']);
                            @endphp
                            <a href="{{ route('shop', $sizeAppend) }}">
                                <span aria-hidden="true">&times;</span>
                            </a>
                        </span>
                    @endif

                    @php
                    $allAppend = $utils['append'];
                    unset($allAppend['cat_id']);
                    unset($allAppend['color_id']);
                    unset($allAppend['size_id']);
                    unset($allAppend['search']);
                    @endphp
                    &nbsp;<a href="{{ route('shop', $allAppend) }}" class="clear-all-filter">
                        Clear all
                    </a>
                </div>
                @endif
                @if($utils['search'])
                <div class="product-config-area">
                    {{ __('main.search_by') }}: {{ $utils['search'] }}
                </div>
                @endif

                <!-- Start Product Wrapper -->
                <div class="shop-page-products-wrapper mt-44 mt-sm-30">
                    <div class="products-wrapper products-on-column">
                        <div class="row">
                            @if (count($products))
                            @foreach ($products as $product)
                            @php
                            $price = getPrice($currentCurrency, $idr, $product->price);
                            $oldPrice = getPrice($currentCurrency, $idr, $product->price);
                            if ($product->discount > 0){
                                $price = getPrice($currentCurrency, $idr, $product->discount);
                            }
                            @endphp
                            <!-- Start Single Product -->
                            <div class="col-lg-4 col-sm-6">
                                <div class="single-product-wrap">
                                    <!-- Product Thumbnail -->
                                    <figure class="product-thumbnail">
                                        <a href="{{ route('shop.show', $product->slug) }}" class="d-block">
                                            <img class="primary-thumb" src="{{ route(config('imagecache.route'), ['template' => 'product-list', 'filename' => $product->getImage() ]) }}" alt="{{ $product->title }}"/>
                                            <img class="secondary-thumb" src="{{ route(config('imagecache.route'), ['template' => 'product-list', 'filename' => $product->getImage(true) ]) }}" alt="{{ $product->title }}"/>
                                        </a>
                                        <figcaption class="product-hvr-content">
                                            @if ($product->discount > 0)
                                            <span class="product-badge sale">{{ __('main.sale') }}</span>
                                            @else
                                                @if (!is_null($product->hot))
                                                    @if (!is_null($product->newRelease))
                                                        <span class="product-badge">{{ __('main.new') }}</span>
                                                    @else
                                                        <span class="product-badge hot">{{ __('main.hot') }}</span>
                                                    @endif
                                                @elseif (!is_null($product->newRelease))
                                                    <span class="product-badge">{{ __('main.new') }}</span>
                                                @endif
                                            @endif
                                        </figcaption>
                                    </figure>

                                    <!-- Product Details -->
                                    <div class="product-details">
                                        <h2 class="product-name"><a href="{{ route('shop.show', $product->slug) }}">{{ $product->title }}</a></h2>
                                        <div class="product-prices">
                                            @if ($product->discount > 0)
                                            <del class="oldPrice">{{ displayPrice($currentCurrency, $oldPrice) }}</del>
                                            @endif
                                            <span class="price">{{ displayPrice($currentCurrency, $price) }}</span>
                                        </div>
                                        <div class="list-view-content">
                                            <p class="product-desc">{{ $product->description }}</p>

                                            <div class="list-btn-group mt-30 mt-sm-14">
                                                <a href="cart.html" class="btn btn-black">{{ __('main.add_to_cart') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Product -->
                            @endforeach
                            @else
                                <div class="col-lg-12">
                                    <div class="single-product-wrap">
                                        {{ __('main.product_not_found') }}
                                    </div>
                                </div>
                            @endif


                        </div>
                    </div>
                </div>
                <!-- End Product Wrapper -->

                <!-- Page Pagination Start  -->
                <div class="page-pagination-wrapper mt-70 mt-md-50 mt-sm-40">
                    {{ $products->appends($utils['append'])->links('vendor.pagination.veera') }}

                </div>
                <!-- Page Pagination End  -->
            </div>
            <!-- End Shop Page Product Area -->
        </div>
    </div>
</div>
<!--== End Shop Page Wrapper ==-->

@endsection

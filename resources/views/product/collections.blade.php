@extends('layouts.veera')
@section('title', $utils['title'] )
@section('menuActive', 'collections')
@section('scripts')

@endsection



@section('content')


<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2>{{ __('main.collections') }}</h2>
                    <nav class="page-breadcrumb">
                        <ul class="d-flex justify-content-center">
                            <li><a href="{{ route('home') }}">{{ __('main.home') }}</a></li>
                            <li><a href="{{ route('shop.collections') }}" class="active">{{ __('main.collections') }}</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Header Wrapper -->

@if (count($collections))
<!--== Start Shop Page Wrapper ==-->
<div id="shop-page-wrapper" class="special-category-banner layout-three">
    <div class="container-fluid p-0">
        <div class="special-category-banner-content no-margin">
            <div class="row no-gutters masonry-category">
                @foreach ($collections as $collection)
                <!-- Single Collection Start -->
                <div class="col-sm-6 col-lg-3">
                    <div class="single-special-banner">
                        <figure class="banner-thumbnail">
                            <a href="{{ route('shop.action', $collection->slug) }}"><img src="{{ route(config('imagecache.route'), ['template' => 'real', 'filename' => $collection->image ]) }}" class="banner-thumb" alt="{{ $collection->title }}"/></a>
                            <figcaption class="banner-cate-name text-center">
                                <a href="{{ route('shop.action', $collection->slug) }}" class="category-name">{{ $collection->title }}</a>
                            </figcaption>
                        </figure>
                    </div>
                </div>
                <!-- Single Collection End -->
                @endforeach

            </div>
        </div>
    </div>
</div>
<!--== End Shop Page Wrapper ==-->
@endif

@endsection

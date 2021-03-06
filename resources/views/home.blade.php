@extends('layouts.veera')
@section('menuActive', 'home')
@section('title', config('app.name'))
@section('scripts')
<!--=== Revolution Slider Js ===-->
<script src="/assets/js/revslider/jquery.themepunch.tools.min.js"></script>
<script src="/assets/js/revslider/jquery.themepunch.revolution.min.js"></script>

<!-- SLIDER REVOLUTION 5.0 EXTENSIONS  (Load Extensions only on Local File Systems !  The following part can be removed on Server for On Demand Loading) -->
<script src="/assets/js/revslider/extensions/revolution.extension.actions.min.js"></script>
<script src="/assets/js/revslider/extensions/revolution.extension.carousel.min.js"></script>
<script src="/assets/js/revslider/extensions/revolution.extension.kenburn.min.js"></script>
<script src="/assets/js/revslider/extensions/revolution.extension.layeranimation.min.js"></script>
<script src="/assets/js/revslider/extensions/revolution.extension.migration.min.js"></script>
<script src="/assets/js/revslider/extensions/revolution.extension.navigation.min.js"></script>
<script src="/assets/js/revslider/extensions/revolution.extension.parallax.min.js"></script>
<script src="/assets/js/revslider/extensions/revolution.extension.slideanims.min.js"></script>
<script src="/assets/js/revslider/extensions/revolution.extension.video.min.js"></script>


<script src="/assets/js/revslider/revslider-active.js"></script>
@endsection

@section('content')

<!--== Start Slider Area ==-->
<div class="slider-area-wrapper">
    <div id="rev_slider_6_1_wrapper" class="rev_slider_wrapper fullscreen-container" data-alias="home-04"
         data-source="gallery">
        <!-- START REVOLUTION SLIDER 5.4.7 fullscreen mode -->
        <div id="rev_slider_home_4" class="rev_slider fullscreenbanner" style="display:none;" data-version="5.4.7">
            <ul>

                @if (count($utils['slideshows']))
                    @foreach ($utils['slideshows'] as $index => $slideshow)
                        <!-- SLIDE  -->
                        <li data-index="rs-{{ $index }}" data-transition="random-premium" data-slotamount="default" data-hideafterloop="0"
                            data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="default"
                            data-thumb="{{ route(config('imagecache.route'), ['template' => 'slideshow-thumbnail', 'filename' => $slideshow->image ]) }}" data-rotate="0" data-saveperformance="off"
                            data-title="Slide">
                            <!-- MAIN IMAGE -->
                            <img src="{{ route(config('imagecache.route'), ['template' => 'slideshow', 'filename' => $slideshow->image ]) }}" alt="" data-bgposition="center center" data-bgfit="cover"
                                data-bgrepeat="no-repeat" data-bgparallax="3" class="rev-slidebg" data-no-retina>
                            <!-- LAYERS -->

                            <!-- LAYER NR. 1 -->
                            <div class="tp-caption     rev_group" id="slide-13-layer-1"
                                data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                                data-y="['middle','middle','middle','middle']" data-voffset="['-133','-133','-133','-133']"
                                data-width="845" data-height="400" data-whitespace="nowrap" data-type="group"
                                data-responsive_offset="on"
                                data-frames='[{"delay":10,"speed":300,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                                data-margintop="[0,0,0,0]" data-marginright="[0,0,0,0]" data-marginbottom="[0,0,0,0]"
                                data-marginleft="[0,0,0,0]" data-textAlign="['inherit','inherit','inherit','inherit']"
                                data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                                data-paddingleft="[0,0,0,0]"
                                style="z-index: 5; min-width: 845px; max-width: 845px; max-width: 400px; max-width: 400px; white-space: nowrap; font-size: 20px; line-height: 22px; font-weight: 400; color: #ffffff; letter-spacing: 0px;">
                                <!-- LAYER NR. 2 -->
                                <div class="tp-caption   tp-resizeme" id="slide-13-layer-2"
                                    data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                                    data-y="['top','top','top','top']" data-voffset="['0','0','0','0']" data-width="none"
                                    data-height="none" data-whitespace="nowrap" data-type="image" data-responsive_offset="on"
                                    data-frames='[{"delay":"+290","speed":1500,"frame":"0","from":"z:0;rX:0;rY:0;rZ:0;sX:0.9;sY:0.9;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                                    data-margintop="[0,0,0,0]" data-marginright="[0,0,0,0]" data-marginbottom="[0,0,0,0]"
                                    data-marginleft="[0,0,0,0]" data-textAlign="['inherit','inherit','inherit','inherit']"
                                    data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                                    data-paddingleft="[0,0,0,0]" style="z-index: 6;">
</div>

                                <!-- LAYER NR. 3 -->
                                <div class="tp-caption   tp-resizeme" id="slide-13-layer-3"
                                    data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                                    data-y="['top','top','top','top']" data-voffset="['303','303','303','303']"
                                    data-width="768" data-height="none" data-whitespace="normal" data-type="text"
                                    data-responsive_offset="on"
                                    data-frames='[{"delay":"+800","speed":2000,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","mask":"x:0px;y:[100%];s:inherit;e:inherit;","to":"o:1;","ease":"Power2.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                                    data-margintop="[0,0,0,0]" data-marginright="[0,0,0,0]" data-marginbottom="[0,0,0,0]"
                                    data-marginleft="[0,0,0,0]" data-textAlign="['center','center','center','center']"
                                    data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                                    data-paddingleft="[0,0,0,0]"
                                    style="z-index: 7; min-width: 768px; max-width: 768px; white-space: normal; font-size: 16px; line-height: 36px; font-weight: 400; color: #ffffff; letter-spacing: 0px;font-family:Poppins;">
                                    {{ getTextLang($slideshow, 'title') }}
                                </div>
                            </div>
                        </li>
                    @endforeach
                @endif


            </ul>
            <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
        </div>
    </div>
</div>
<!--== End Slider Area ==-->

<!--== Start Special Categories Banner ==-->
<div class="special-category-banner layout-four mt-sm-30 mt-md-30">
    <div class="container-fluid">
        <div class="special-category-banner-content">
            <div class="row">
                <!-- Single Banner Start -->
                <div class="col-sm-6 col-lg-4 order-1">
                    <div class="single-special-banner">
                        <figure class="banner-thumbnail">
                            <a href="{{ $utils['banners'][0]->url }}" target="{{ $utils['banners'][0]->target }}"><img src="{{ route(config('imagecache.route'), ['template' => 'banner', 'filename' => $utils['banners'][0]->image ]) }}" class="banner-thumb" alt="{{ $utils['banners'][0]->title }}"/></a>
                            <figcaption class="banner-cate-name text-center">
                                <a href="{{ $utils['banners'][0]->url }}" target="{{ $utils['banners'][0]->target }}" class="category-name">{{ getTextLang($utils['banners'][0], 'title') }}</a>
                            </figcaption>
                        </figure>
                    </div>
                </div>
                <!-- Single Banner End -->

                <!-- Single Banner Start -->
                <div class="col-md-12 col-lg-4 order-last order-lg-2">
                    <div class="row">
                        <div class="col-sm-6 col-lg-12">
                            <div class="single-special-banner">
                                <figure class="banner-thumbnail">
                                    <a href="{{ $utils['banners'][1]->url }}" target="{{ $utils['banners'][1]->target }}"><img src="{{ route(config('imagecache.route'), ['template' => 'banner-second', 'filename' => $utils['banners'][1]->image ]) }}"
                                                             class="banner-thumb"
                                                             alt="{{ $utils['banners'][1]->title }}"/></a>
                                    <figcaption class="banner-cate-name text-center">
                                        <a href="{{ $utils['banners'][1]->url }}" target="{{ $utils['banners'][1]->target }}" class="category-name">{{ getTextLang($utils['banners'][1], 'title') }}</a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-12">
                            <div class="single-special-banner mt-lg-16">
                                <figure class="banner-thumbnail">
                                    <a href="{{ $utils['banners'][2]->url }}" target="{{ $utils['banners'][2]->target }}"><img src="{{ route(config('imagecache.route'), ['template' => 'banner-second', 'filename' => $utils['banners'][2]->image ]) }}"
                                                             class="banner-thumb"
                                                             alt="{{ $utils['banners'][2]->title }}"/></a>
                                    <figcaption class="banner-cate-name text-center">
                                        <a href="{{ $utils['banners'][2]->url }}" target="{{ $utils['banners'][2]->target }}" class="category-name">{{ getTextLang($utils['banners'][2], 'title') }}</a>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Single Banner End -->

                <!-- Single Banner Start -->
                <div class="col-sm-6 col-lg-4 order-3">
                    <div class="single-special-banner">
                        <figure class="banner-thumbnail">
                            <a href="{{ $utils['banners'][3]->url }}" target="{{ $utils['banners'][3]->target }}"><img src="{{ route(config('imagecache.route'), ['template' => 'banner', 'filename' => $utils['banners'][3]->image ]) }}"
                                                     class="banner-thumb"
                                                     alt="{{ $utils['banners'][3]->title }}"/></a>
                            <figcaption class="banner-cate-name text-center">
                                <a href="{{ $utils['banners'][3]->url }}" target="{{ $utils['banners'][3]->target }}" class="category-name">{{ getTextLang($utils['banners'][3], 'title') }}</a>
                            </figcaption>
                        </figure>
                    </div>
                </div>
                <!-- Single Banner End -->
            </div>
        </div>
    </div>
</div>
<!--== End Special Categories Banner ==-->

@include('product.list', ['title'=>'New Arrival', 'products'=>$utils['products']['newReleases'], 'useBadge'=>false])
@include('product.list', ['title'=>__('main.sale'), 'products'=>$utils['products']['sales'], 'useBadge'=>false])
@include('product.list', ['title'=>'Best Seller', 'products'=>$utils['products']['bestSeller'], 'useBadge'=>false])

@if (count($utils['articles']))
<!--== Start Latest Blog Area ==-->
<section id="latest-blog-wrapper" class="pt-88 pt-md-58 pt-sm-50">
    <div class="container">
        <div class="row">
            <!-- Start Section title -->
            <div class="col-lg-8 m-auto text-center">
                <div class="section-title-wrap">
                    <h2> {{ __('main.latest_news') }}</h2>
                </div>
            </div>
            <!-- End Section title -->
        </div>

        <!-- Blog Content Wrapper -->
        <div class="blog-content-wrap">
            <div class="row">
                @foreach ($utils['articles'] as $article)
                <!-- Start Single Blog Item -->
                <div class="col-lg-4 col-sm-6">
                    <div class="single-blog-post-wrap">
                        <figure class="blog-post-thumbnail">
                            <a href="{{ route('blog.show', $article->slug) }}"><img src="{{ route(config('imagecache.route'), ['template' => 'article-list', 'filename' => $article->image ]) }}" alt="{{ getTextLang($article, 'title') }}"/></a>
                        </figure>
                        <div class="blog-post-content">
                            <div class="blog-meta">
                                <a href="{{ route('blog.show', $article->slug) }}" class="date">{{ dateFormatLang($article->published_on) }}</a>
                            </div>
                            <h2><a href="{{ route('blog.show', $article->slug) }}">{{ getTextLang($article, 'title') }}</a></h2>
                            <p>{!! \Illuminate\Support\Str::words(getTextLang($article, 'preview'), 25,'...')  !!}</p>
                            <a href="{{ route('blog.show', $article->slug) }}" class="btn btn-transparent">{{ __('main.read_more') }}</a>
                        </div>
                    </div>
                </div>
                <!-- End Single Blog Item -->
                @endforeach
            </div>
        </div>
    </div>
</section>
<!--== End Latest Blog Area ==-->
@endif

<!--== Start Call to Action Area  ==-->
<div class="call-to-action-wrapper mt-88 mt-md-58 mt-sm-48 d-none">
    <div class="container">
        <div class="call-action-content-wrapper">
            <div class="row">
                <!-- Start Single Call to Action Item -->
                <div class="col-lg-3 col-sm-6">
                    <div class="single-call-to-action-wrap d-flex">
                        <div class="action-icon">
                            <img src="assets/img/icons/home4/free-shipping.png" alt="{{ __('main.free_shipping_worldwide') }}"/>
                        </div>
                        <div class="action-info">
                            <h2>{{ __('main.free_shipping_worldwide') }}</h2>
                            <p>{{ __('main.over_order_100') }}</p>
                        </div>
                    </div>
                </div>
                <!-- End Single Call to Action Item -->

                <!-- Start Single Call to Action Item -->
                <div class="col-lg-3 col-sm-6">
                    <div class="single-call-to-action-wrap d-flex">
                        <div class="action-icon">
                            <img src="assets/img/icons/home4/buyer-protect.png" alt="{{ __('main.buyer_protection') }}"/>
                        </div>
                        <div class="action-info">
                            <h2>{{ __('main.buyer_protection') }}</h2>
                            <p>{{ __('main.safe_and_protect') }}</p>
                        </div>
                    </div>
                </div>
                <!-- End Single Call to Action Item -->

                <!-- Start Single Call to Action Item -->
                <div class="col-lg-3 col-sm-6">
                    <div class="single-call-to-action-wrap d-flex">
                        <div class="action-icon">
                            <img src="assets/img/icons/home4/money-back.png" alt="{{ __('main.buyer_protection') }}"/>
                        </div>
                        <div class="action-info">
                            <h2>{{ __('main.buyer_protection') }}</h2>
                            <p>{{ __('main.you_can_back_money_every_time') }}</p>
                        </div>
                    </div>
                </div>
                <!-- End Single Call to Action Item -->

                <!-- Start Single Call to Action Item -->
                <div class="col-lg-3 col-sm-6">
                    <div class="single-call-to-action-wrap d-flex">
                        <div class="action-icon">
                            <img src="assets/img/icons/home4/gift.png" alt="{{ __('main.promotion_gift_code') }}"/>
                        </div>
                        <div class="action-info">
                            <h2>{{ __('main.promotion_gift_code') }}</h2>
                            <p>{{ __('main.give_gifts_to_anyone') }}</p>
                        </div>
                    </div>
                </div>
                <!-- End Single Call to Action Item -->
            </div>
        </div>
    </div>
</div>
<!--== End Call to Action Area  ==-->

<!-- Start Banner Area // Home4  -->
<section class="banner-area-wrapper parallaxBg layout-two mt-88 mt-md-58 mt-sm-48" style="background-image: url({{ route(config('imagecache.route'), ['template' => 'banner-third', 'filename' => $utils['banners'][4]->image ]) }});">
    <div class="container-fluid">
        <div class="banner-content-wrap">
            <div class="row">
                <div class="col-sm-6 col-10 ml-auto">
                    <div class="banner-content">
                        <h4 class="d-none">{{ __('main.men_shirt_collection') }}</h4>
                        <h2>{{ $utils['banners'][4]->title }}</h2>
                        <h3 class="d-none">{{ __('main.only_2999') }}</h3>
                        <a href="{{ $utils['banners'][4]->url }}" target="{{ $utils['banners'][4]->target }}" class="btn btn-transparent">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Area // Home4 -->


@endsection

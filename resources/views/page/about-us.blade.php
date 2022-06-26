@extends('layouts.veera')
@section('title', $page->title)
@section('menuActive', 'about-us')
@section('scripts')

@endsection

@section('content')

<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper layout-two">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2>{{ getTextLang($page, 'title') }}</h2>
                    <nav class="page-breadcrumb">
                        <ul class="d-flex justify-content-center">
                            <li><a href="{{ route('home') }}">{{ __('main.home') }}</a></li>
                            <li><a href="{{ route('page.show', $page->slug) }}" class="active">{{ getTextLang($page, 'title') }}</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Header Wrapper -->

<!--== Start About Page Wrapper ==-->
<div id="about-page-wrapper" class="pt-90 pt-md-60 pt-sm-50 pb-50 pb-md-20 pb-sm-10">
    <div class="about-content-wrap mb-84 mb-md-54 mb-sm-46">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-sm-28 mb-md-24">
                    <div class="about-thumbnail">
                        <img src="/assets/img/love-fashion.jpg" alt="About Thumbnail"/>
                    </div>
                </div>

                <div class="col-lg-6 ml-auto my-auto">
                    <div class="about-content">
                        <h2>{{ __('main.khanaan_shamlan') }}</h2>
                        {!! getTextLang($page, 'description') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="why-work-us mb-90 mb-md-60 mb-sm-50">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-6 order-1 order-lg-0">
                    <div class="why-work-content">
                        <h2>{{ __('main.follow_us_on_social_media') }} :</h2>
                        <div class="footer-social-icons">
                            <a href="https://www.facebook.com/khanaanshop" target="_blank"><i class="fa fa-facebook"></i></a>
                            <a href="https://twitter.com/khanaan_shamlan" target="_blank"><i class="fa fa-twitter"></i></a>
                            <a href="https://www.instagram.com/khanaan.official" target="_blank"><i class="fa fa-instagram"></i></a>
                            <a href="https://wa.me/+6287875858002" target="_blank"><i class="fa fa-whatsapp"></i></a>

                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6 ml-auto order-0 order-lg-1">
                    <figure class="about-pic mb-sm-30 mb-md-30">
                        <img src="/assets/img/logo-big.png" class="w-100" alt="About Image"/>
                    </figure>
                </div>
            </div>
        </div>
    </div>


</div>
<!--== End About Page Wrapper ==-->

@endsection

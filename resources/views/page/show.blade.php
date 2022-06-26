@extends('layouts.veera')
@section('title', $page->title)

@section('menuActive', $page->slug)
@section('scripts')

@endsection

@section('content')


<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2>{{ $page->title }}</h2>
                    <nav class="page-breadcrumb">
                        <ul class="d-flex justify-content-center">
                            <li><a href="{{ route('home') }}">{{ __('main.home') }}</a></li>
                            <li><a href="{{ route('page.show', $page->slug) }}" class="active">{{ $page->title }}</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Header Wrapper -->

<!--== Start Page Wrapper ==-->
<div id="page-area-wrapper" class="mt-88 mt-md-60 mt-sm-50 mb-50 mb-md-20 mb-sm-10">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- General Question Faq Start-->
                <div class="single-subject-by-faq-wrap">

                    <h3>{{ $page->title }}</h3>
                    <div class="contact-info-content">
                    {!! $page->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Page Wrapper ==-->

@endsection

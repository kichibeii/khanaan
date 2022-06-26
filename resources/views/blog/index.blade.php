@extends('layouts.veera')
@section('title', __('main.article'))
@section('menuActive', 'blog')
@section('scripts')

@endsection

@section('content')


<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2>{{ __('main.article') }}</h2>
                    <nav class="page-breadcrumb">
                        <ul class="d-flex justify-content-center">
                            <li><a href="{{ route('home') }}">{{ __('main.home') }}</a></li>
                            <li><a href="{{ route('blog') }}" class="active">{{ __('main.article') }}</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Header Wrapper -->

@if (count($articles))
<!--== Start Blog Page Wrapper ==-->
<div id="blog-page-wrapper" class="pt-90 pt-md-60 pt-sm-50 pb-48 pb-md-20 pb-sm-10">
    <div class="container">
        <div class="blog-page-post-wrap">
            <div class="row masonry-category">
                @foreach ($articles as $article)
                <!-- Start Single Blog Item -->
                <div class="col-md-6 col-lg-4">
                    <div class="single-blog-post-wrap">
                        <figure class="blog-post-thumbnail">
                        <a href="{{ route('blog.show', $article->slug) }}"><img src="{{ route(config('imagecache.route'), ['template' => 'article-list', 'filename' => $article->image ]) }}" alt="{{ $article->title }}"/></a>
                        </figure>
                        <div class="blog-post-content">
                            <div class="blog-meta">
                                <a href="{{ route('blog.show', $article->slug) }}" class="date">{{ dateFormatLang($article->published_on) }}</a>
                            </div>
                            <h2><a href="{{ route('blog.show', $article->slug) }}">{{ getTextLang($article, 'title') }}</a></h2>
                            <p>{{ getTextLang($article, 'preview') }}</p>
                            <a href="{{ route('blog.show', $article->slug) }}" class="btn btn-transparent">{{ __('main.read_more') }}</a>
                        </div>
                    </div>
                </div>
                <!-- End Single Blog Item -->
                @endforeach

                
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Pagination Start  -->
                    <div class="page-pagination-wrapper mt-70 mt-md-50 mt-sm-40">
                        {{ $articles->links('vendor.pagination.veera') }}
                    </div>
                    <!-- Page Pagination End  -->
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Blog Page Wrapper ==-->
@endif

@endsection

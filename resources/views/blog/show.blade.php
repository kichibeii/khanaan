@extends('layouts.veera')
@section('title', __('main.article') . ' | ' . $article->title)
@section('menuActive', 'blog')
@section('scripts')
<script async charset="utf-8" src="//cdn.embedly.com/widgets/platform.js"></script>
<script>
    $( document ).ready(function() {
        console.log(1);
        document.querySelectorAll( 'oembed[url]' ).forEach( element => {

            // Create the <a href="..." class="embedly-card"></a> element that Embedly uses
            // to discover the media.
            const anchor = document.createElement( 'a' );

            anchor.setAttribute( 'href', element.getAttribute( 'url' ) );
            anchor.className = 'embedly-card';

            element.appendChild( anchor );
        } );
    });
    
</script>
@endsection

@section('styles-after')
<style type="text/css">
    .media {
        width: 100%;
    }
</style>
@endsection

@section('content')
<script>  
    window.fbAsyncInit = function () {
        FB.init({
            appId: '208353932600596', 
            status: false,
            cookie: false, 
            xfbml: true,
            version    : 'v2.7' // or v2.6, v2.5, v2.4, v2.3
        });
    };

    // Asynchronously
    (function (d) {
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) { return; }
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
    } (document));

</script>

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

<!--== Start Single Blog Page Wrapper ==-->
<div id="single-blog-page-wrapper" class="pt-90 pt-md-60 pt-sm-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 m-auto">
                <article class="single-blog-content">
                    <div class="blog-post-thumb mb-24">
                        <img src="{{ route(config('imagecache.route'), ['template' => 'article-list', 'filename' => $article->image ]) }}" alt="{{ getTextLang($article, 'title') }}"/>
                    </div>
                    <div class="blog-meta mb-0">
                        <a href="{{ route('blog.show', $article->slug) }}" class="date">{{ dateFormatLang($article->published_on) }}</a>
                    </div>

                    <h2>{{ getTextLang($article, 'title') }}</h2>
                    {!! getTextLang($article, 'description') !!}
                </article>

                <div class="comment-area-wrap mt-80 mt-md-40 mt-sm-40">
                    <h2>{{ __('main.comments') }}</h2>

                    <div class="comments-view-area">
                        <div class="fb-comments"  notify="true"  data-href="{{ route('blog.show', $article->slug) }}" data-include-parent="true" data-colorscheme="light" data-width="100%" style="width: 100%;"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Single Blog Page Wrapper ==-->

@if (count($utils['relateds']))

<!--== Start Related Post Area ==-->
<section id="latest-blog-wrapper" class="pt-92 pt-md-60 pt-sm-50 pb-48 pb-md-20 pb-sm-10">
    <div class="container">
        <div class="row">
            <!-- Start Section title -->
            <div class="col-lg-8 m-auto text-center">
                <div class="section-title-wrap">
                    <h2>{{ __('main.related_post') }}</h2>
                </div>
            </div>
            <!-- End Section title -->
        </div>

        <!-- Blog Content Wrapper -->
        <div class="blog-content-wrap">
            <div class="row">
                @foreach ($utils['relateds'] as $blog)
                <!-- Start Single Blog Item -->
                <div class="col-lg-4 col-sm-6">
                    <div class="single-blog-post-wrap">
                        <figure class="blog-post-thumbnail">
                            <a href="{{ route('blog.show', $blog->slug) }}"><img src="{{ route(config('imagecache.route'), ['template' => 'article-list', 'filename' => $blog->image ]) }}" alt="{{ getTextLang($blog, 'title') }}"/></a>
                        </figure>
                        <div class="blog-post-content">
                            <div class="blog-meta">
                                <a href="{{ route('blog.show', $blog->slug) }}" class="date">{{ dateFormatLang($blog->published_on) }}</a>
                            </div>
                            <h2><a href="{{ route('blog.show', $blog->slug) }}">{{ getTextLang($blog, 'title') }}</a></h2>
                            <p>{!! getTextLang($blog, 'preview') !!}</p>
                            <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-transparent">{{ __('main.read_more') }}</a>
                        </div>
                    </div>
                </div>
                <!-- End Single Blog Item -->
                @endforeach

            </div>
        </div>
    </div>
</section>
<!--== End Related Post Area ==-->

@endif

@endsection

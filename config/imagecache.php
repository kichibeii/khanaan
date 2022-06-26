<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Name of route
    |--------------------------------------------------------------------------
    |
    | Enter the routes name to enable dynamic imagecache manipulation.
    | This handle will define the first part of the URI:
    |
    | {route}/{template}/{filename}
    |
    | Examples: "images", "img/cache"
    |
    */

    'route' => "imagecache",

    /*
    |--------------------------------------------------------------------------
    | Storage paths
    |--------------------------------------------------------------------------
    |
    | The following paths will be searched for the image filename, submitted
    | by URI.
    |
    | Define as many directories as you like.
    |
    */

    'paths' => array(
        /*
        public_path('assets'),
        public_path('storage/images/users'),
        public_path('storage/images/logo'),
        public_path('storage/images/banks'),
        public_path('storage/images/slideshows'),
        public_path('storage/images/banners'),
        public_path('storage/images/articles'),
        public_path('storage/images/banners'),
        public_path('storage/images/products'),
        public_path('storage/images/dropdowns'),
        public_path('storage/images/ckeditor')
        */
        'assets',
        'storage/images/users',
        'storage/images/logo',
        'storage/images/banks',
        'storage/images/sizes',
        'storage/images/slideshows',
        'storage/images/banners',
        'storage/images/articles',
        'storage/images/banners',
        'storage/images/products',
        'storage/images/dropdowns',
        'storage/images/ckeditor'
    ),

    /*
    |--------------------------------------------------------------------------
    | Manipulation templates
    |--------------------------------------------------------------------------
    |
    | Here you may specify your own manipulation filter templates.
    | The keys of this array will define which templates
    | are available in the URI:
    |
    | {route}/{template}/{filename}
    |
    | The values of this array will define which filter class
    | will be applied, by its fully qualified name.
    |
    */

    'templates' => array(
        'small' => 'Intervention\Image\Templates\Small',
        //'medium' => 'Intervention\Image\Templates\Medium',
        'large' => 'Intervention\Image\Templates\Large',
        'medium' => 'App\Imagecache\Medium',
        'fit-medium' => 'App\Imagecache\FitMedium',
        'trim-medium' => 'App\Imagecache\TrimMedium',
        'resize-medium' => 'App\Imagecache\ResizeMedium',
        'banner' => 'App\Imagecache\Banner',
        'banner-second' => 'App\Imagecache\BannerSecond',
        'banner-third' => 'App\Imagecache\BannerThird',

        // SLIDHOW
        'slideshow' => 'App\Imagecache\Slideshow',
        'slideshow-logo' => 'App\Imagecache\SlideshowLogo',
        'slideshow-thumbnail' => 'App\Imagecache\SlideshowThumbnail',

        // PRODUCT
        'product-list' => 'App\Imagecache\ProductList',
        'product-show' => 'App\Imagecache\ProductDetail',
        
        // ARTICLE
        'article-list' => 'App\Imagecache\ArticleList',


        'real' => 'App\Imagecache\Real',
    ),

    /*
    |--------------------------------------------------------------------------
    | Image Cache Lifetime
    |--------------------------------------------------------------------------
    |
    | Lifetime in minutes of the images handled by the imagecache route.
    |
    */

    'lifetime' => 43200,

);

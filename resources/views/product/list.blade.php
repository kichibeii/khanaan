@if (count($products))
@php
$idr = 1;
$currentCurrency = currentCurrency();
if ($currentCurrency == 'usd'){
    $idr = \App\Setting::getValue('dollar');
}
@endphp
<!--== Start {{ $title }} Products Area ==-->
<section id="mustHave-products-area" class="pt-90 pt-md-60 pt-sm-50">
    <div class="container-fluid">
        <div class="row">
            <!-- Start Section title -->
            <div class="col-lg-8 m-auto text-center">
                <div class="section-title-wrap">
                    <h2>{{ $title }}</h2>
                </div>
            </div>
            <!-- End Section title -->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="products-wrapper">
                    <div class="product-carousel-wrap">
                        @foreach ($products as $product)

                        <!-- Start Single Product -->
                        <div class="single-product-wrap">
                            <!-- Product Thumbnail -->
                            <figure class="product-thumbnail">
                                <a href="{{ route('shop.show', $product->slug) }}" class="d-block">
                                    <img class="primary-thumb" src="{{ route(config('imagecache.route'), ['template' => 'product-list', 'filename' => $product->getImage() ]) }}" alt="{{ $product->title }}"/>
                                    <img class="secondary-thumb" src="{{ route(config('imagecache.route'), ['template' => 'product-list', 'filename' => $product->getImage(true) ]) }}" alt="{{ $product->title }}"/>
                                </a>
                                <figcaption class="product-hvr-content">
                                    @if ($useBadge)
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
                                    @endif
                                </figcaption>
                            </figure>

                            <!-- Product Details -->
                            @php
                            $price = getPrice($currentCurrency, $idr, $product->price);
                            $oldPrice = getPrice($currentCurrency, $idr, $product->price);
                            if ($product->discount > 0){
                                $price = getPrice($currentCurrency, $idr, $product->discount);
                            }
                            @endphp
                            <div class="product-details">
                                <h2 class="product-name"><a href="{{ route('shop.show', $product->slug) }}">{{ $product->title }}</a></h2>
                                <div class="product-prices">
                                    @if ($product->discount > 0)
                                    <del class="oldPrice">{{ displayPrice($currentCurrency, $oldPrice) }}</del>
                                    @endif
                                    <span class="price">{{ displayPrice($currentCurrency, $price) }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Product -->
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End {{ $title }} Products Area ==-->
@endif

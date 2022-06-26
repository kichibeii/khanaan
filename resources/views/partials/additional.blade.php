<!--== Start Search box Wrapper ==-->
<div class="modalSearchBox" id="search-box-popup">
    <div class="modaloverlay"></div>
    <div class="search-box-wrapper">
        <p>{{ __('main.start_search') }}</p>
        <div class="search-box-form">
            <form id="form-search" class="search-form-area">
                <input type="search"  name="search" id="search" placeholder="{{ __('main.search_by_product') }}" autocomplete="off" />
                <button type="submit" class="btn-search"><i class="dl-icon-search10"></i></button>
            </form>
        </div>
    </div>
</div>
<!--== End Search box Wrapper ==-->

<!--== Start Mini Cart Wrapper ==-->
<div class="mfp-hide modal-minicart" id="miniCart-popup">
    <div class="minicart-content-wrap">
        <h2>Shopping Cart</h2>
        <div class="minicart-product-list">
            <!-- Start Single Product -->
            <div class="single-product-item d-flex">
                <figure class="product-thumb">
                    <a href="single-product.html"><img src="/assets/img/products/prod-1-1.jpg" alt="Product"></a>
                </figure>
                <div class="product-details">
                    <h2 class="product-title"><a href="single-product.html">Stripe textured dress</a></h2>
                    <div class="prod-cal d-flex align-items-center">
                        <span class="quantity">1</span>
                        <span class="multiplication">&#215;</span>
                        <span class="price">$99.99</span>
                    </div>
                </div>
                <a href="#" class="remove-icon">&#215;</a>
            </div>
            <!-- End Single Product -->

            <!-- Start Single Product -->
            <div class="single-product-item d-flex">
                <figure class="product-thumb">
                    <a href="single-product.html"><img src="/assets/img/products/prod-2-1.jpg" alt="Product"></a>
                </figure>
                <div class="product-details">
                    <h2 class="product-title"><a href="single-product.html">Tassels embroidered dress</a></h2>
                    <div class="prod-cal d-flex align-items-center">
                        <span class="quantity">2</span>
                        <span class="multiplication">&#215;</span>
                        <span class="price">$39.29</span>
                    </div>
                </div>
                <a href="#" class="remove-icon">&#215;</a>
            </div>
            <!-- End Single Product -->

            <!-- Start Single Product -->
            <div class="single-product-item d-flex">
                <figure class="product-thumb">
                    <a href="single-product.html"><img src="/assets/img/products/prod-3-1.jpg" alt="Product"></a>
                </figure>
                <div class="product-details">
                    <h2 class="product-title"><a href="single-product.html">Open-knit sweater</a></h2>
                    <div class="prod-cal d-flex align-items-center">
                        <span class="quantity">1</span>
                        <span class="multiplication">&#215;</span>
                        <span class="price">33.29</span>
                    </div>
                </div>
                <a href="#" class="remove-icon">&#215;</a>
            </div>
            <!-- End Single Product -->

            <!-- Start Single Product -->
            <div class="single-product-item d-flex">
                <figure class="product-thumb">
                    <a href="single-product.html"><img src="/assets/img/products/prod-4-1.jpg" alt="Product"></a>
                </figure>
                <div class="product-details">
                    <h2 class="product-title"><a href="single-product.html">Open-knit sweater</a></h2>
                    <div class="prod-cal d-flex align-items-center">
                        <span class="quantity">1</span>
                        <span class="multiplication">&#215;</span>
                        <span class="price">33.29</span>
                    </div>
                </div>
                <a href="#" class="remove-icon">&#215;</a>
            </div>
            <!-- End Single Product -->
        </div>
        <div class="minicart-calculation-wrap d-flex justify-content-between align-items-center">
            <span class="cal-title">Subtotal:</span>
            <span class="cal-amount">£119.97</span>
        </div>
        <div class="minicart-btn-group mt-38">
            <a href="{{ route('cart') }}" class="btn btn-black ">View Cart</a>
            <a href="checkout.html" class="btn btn-black mt-10">checkout</a>
        </div>
    </div>
</div>
<!--== End Mini Cart Wrapper ==-->

<!--== Start Quick View Modal Wrapper ==-->
<div class="modal" id="quickViewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="dl-icon-close"></i></span>
            </button>
            <div class="modal-body">
                <div class="row">
                    <!-- Start Single Product Thumbnail -->
                    <div class="col-lg-5 col-md-6">
                        <div class="single-product-thumb-wrap p-0 pb-sm-30 pb-md-30">
                            <!-- Product Thumbnail Large View -->
                            <div class="quciview-product-thumb-carousel">
                                <figure class="product-thumb-item">
                                    <img src="/assets/img/products/prod-1-1.jpg" alt="Single Product"/>
                                </figure>
                                <figure class="product-thumb-item">
                                    <img src="/assets/img/products/prod-1-2.jpg" alt="Single Product"/>
                                </figure>
                                <figure class="product-thumb-item">
                                    <img src="/assets/img/products/prod-2-1.jpg" alt="Single Product"/>
                                </figure>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Product Thumbnail -->

                    <!-- Start Single Product Content -->
                    <div class="col-lg-7 col-md-6 m-auto">
                        <div class="single-product-content-wrapper">
                            <div class="single-product-details">
                                <h2 class="product-name">Open-knit sweater</h2>
                                <div class="prices-stock-status d-flex align-items-center justify-content-between">
                                    <div class="prices-group">
                                        <del class="old-price">$50.00</del>
                                        <span class="price">$40.00</span>
                                    </div>
                                    <span class="stock-status"><i class="dl-icon-check-circle1"></i> In  Stock</span>
                                </div>
                                <p class="product-desc">Ut enim added minim veniam, quis nostrud exercitation ullamco
                                    ommodo
                                    consequat. Duis aute irure dolor in reprehenderit dolore eu fugiat nulla pariatur.
                                    Excepteur
                                    sint occaecat cupidatat non proident. Lorem ipsum dolor sit amet, consectetur
                                    adipisicing
                                    elit. Ab dolorem eum labore minima possimus quaerat quod recusandae repellat sequi
                                    ut.</p>

                                <div class="quantity-btn-group d-flex">
                                    <div class="pro-qty">
                                        <input type="text" id="quantity" value="1"/>
                                    </div>
                                    <div class="list-btn-group">
                                        <a href="cart.html" class="btn btn-black">Add to Cart</a>
                                        <a href="wishlist.html" data-toggle="tooltip" data-placement="top"
                                           title="Add to wishlist"><i class="dl-icon-heart2"></i></a>
                                        <a href="compare.html" data-toggle="tooltip" data-placement="top"
                                           title="Add to Compare"><i class="dl-icon-compare2"></i></a>
                                    </div>
                                </div>

                                <div class="find-store-delivery">
                                    <a href="#"><i class="fa fa-map-marker"></i> Find store near you</a>
                                    <a href="#"><i class="fa fa-exchange"></i> Delivery and return</a>
                                </div>
                            </div>

                            <div class="single-product-footer mt-20 pt-20">
                                <div class="prod-footer-right">
                                    <dl class="social-share">
                                        <dt>Share with</dt>
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
    </div>
</div>
<!--== End Quick View Modal Wrapper ==-->
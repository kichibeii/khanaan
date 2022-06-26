<!--== Start Footer Section ===-->
<footer id="footer-area">
<div class="footer-widget-area pt-40 pb-28">
        <div class="container">
            <div class="footer-widget-content">
                <div class="row">
                    <!-- Start Footer Widget Item -->
                    <div class="col-md-12 col-lg-3">
                        <div class="footer-widget-item-wrap">
                            <div class="widget-body">
                                <div class="about-text pt-38 pt-sm-0 pt-md-0">
                                    <a href="/"><img src="/assets/img/logo.png" alt="Logo"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Footer Widget Item -->

                    <!-- Start Footer Widget Item -->
                    <div class="col-sm-3 col-lg-2">
                        <div class="footer-widget-item-wrap">
                            <h3 class="widget-title">{{ __('main.company') }}</h3>
                            <div class="widget-body">
                                <ul class="footer-list">
                                    <li><a href="{{ route('page.show', 'about-us') }}">{{ \App\Page::getTitleById(1) }}</a></li>
                                    <li><a href="{{ route('page.show', 'contact-us') }}">{{ \App\Page::getTitleById(5) }}</a></li>
                                    <li><a href="{{ route('blog') }}">{{ __('main.article') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Footer Widget Item -->

                    <div class="col-sm-3 col-lg-2">
                        <div class="footer-widget-item-wrap">
                            <h3 class="widget-title">{{ __('main.community') }}</h3>
                            <div class="widget-body">
                                <ul class="footer-list">
                                    <li><a href="https://www.facebook.com/khanaanshop" target="_blank">Facebook</a></li>
                                    <li><a href="https://twitter.com/khanaan_shamlan" target="_blank">Twitter</a></li>
                                    <li><a href="https://www.instagram.com/khanaan.official" target="_blank">Instagram</i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>


                    <!-- Start Footer Widget Item -->
                    <div class="col-sm-3 col-lg-2">
                        <div class="footer-widget-item-wrap">
                            <h3 class="widget-title">{{ __('main.legal') }}</h3>
                            <div class="widget-body">
                                <ul class="footer-list">
                                    <li><a href="{{ route('page.show', 'terms-and-conditions') }}">{{ __('main.terms_and_conditions') }}</a></li>
                                    <li><a href="{{ route('page.show', 'return-and-exchange') }}">{{ __('main.return_and_exchange') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Footer Widget Item -->

                    <!-- Start Footer Widget Item -->
                    <div class="col-sm-3 col-lg-3">
                        <div class="footer-widget-item-wrap">
                            <h3 class="widget-title">{{ __('main.contact_us') }}</h3>
                            <div class="widget-body">
                                <div class="contact-text">
                                    <a href="#">(+6221) 7667 772</a>
                                    <a href="#">info@khanaan.com</a>
                                    <p>Jl. Cipete Raya No. 7 Jakarta Selatan DKI Jakarta</p>
                                    <a href="{{ route('page.show', 'contact-us') }}" class="d-block mt-22"><u>Google Maps</u></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Footer Widget Item -->
                </div>
            </div>
        </div>
    </div>


    <!-- Start Footer Bottom Area -->
    <div class="footer-bottom-wrapper">
        <div class="container">
            <div class="row">

                <div class="col-md-5 col-lg-5 m-auto order-1">
                    <div class="copyright-text mt-sm-10">
                        <p>&copy; 2021 <a href="/">Khanaan</a>, All rights reserved.</p>
                    </div>

                </div>

                <div class="col-md-4 col-lg-4 m-auto text-center text-md-left order-3 order-md-2">
                <div class="footer-menu mb-sm-12">

                    </div>
                </div>

                <div class="col-md-3 col-lg-3 m-auto text-center text-md-right order-2 order-md-3">
                    <div class="footer-social-icons nav ">
                        <a href="https://www.facebook.com/khanaanshop" target="_blank"><i class="fa fa-facebook"></i></a>
                        <a href="https://twitter.com/khanaan_shamlan" target="_blank"><i class="fa fa-twitter"></i></a>
                        <a href="https://www.instagram.com/khanaan.official" target="_blank"><i class="fa fa-instagram"></i></a>
                        <a href="https://wa.me/+6287875858002" target="_blank"><i class="fa fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Bottom Area -->
</footer>
<!--== End Footer Section ===-->

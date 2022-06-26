<!--== Start Header Area ===-->
<header id="header-area" class="header-three sticky-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="header-content-wrapper d-flex align-items-center">
                    <div class="header-mainmenu-area d-none d-lg-block">
                        <!-- Start Main Menu -->
                        <nav id="mainmenu-wrap">
                            <ul class="nav mainmenu justify-content-center">
                                <li><a class="{{ trim($__env->yieldContent('menuActive')) == 'home' ? 'current' : '' }}" href="{{ route('home') }}">Home</a></li>
                                <li><a class="{{ trim($__env->yieldContent('menuActive')) == 'shop' ? 'current' : '' }}" href="{{ route('shop') }}">{{ __('main.shop') }}</a></li>
    
                                <li><a class="{{ trim($__env->yieldContent('menuActive')) == 'collections' ? 'current' : '' }}" href="{{ route('shop.collections') }}">{{ __('main.collections') }}</a></li>
                                <li><a class="{{ trim($__env->yieldContent('menuActive')) == 'sale' ? 'current' : '' }}" href="{{ route('shop.action', 'sale') }}">{{ __('main.sale') }}</a></li>
                                <li><a class="{{ trim($__env->yieldContent('menuActive')) == 'blog' ? 'current' : '' }}" href="{{ route('blog') }}">{{ __('main.article') }}</a></li>
                            </ul>
                        </nav>
                        <!-- End Main Menu -->
                    </div>

                    <div class="header-left-area d-flex align-items-center">
                        <!-- Start Logo Area -->
                        <div class="logo-area">
                            <a href="{{ route('home') }}"><img src="/assets/img/logo.png" alt="Logo"/></a>
                        </div>
                        <!-- End Logo Area -->
                    </div>

                    <div class="header-right-area d-flex justify-content-end align-items-center">
                        <button class="search-icon animate-modal-popup" data-mfp-src="#search-box-popup"><i
                                class="dl-icon-search1"></i></button>
                        <ul class="user-area">
                            <li class="dropdown-show">
                                <button><i class="fa fa-user"></i></button>
                                <ul class="dropdown-nav">
                                    <li><a href="my-account.html">My Account</a></li>
                                    <li><a href="my-account.html">Lost Password</a></li>
                                </ul>
                            </li>
                        </ul>
                        <button class="mini-cart-icon modalActive" data-mfp-src="#miniCart-popup">
                            <i class="dl-icon-cart1"></i>
                            <span class="cart-count">4</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!--== End Header Area ===-->
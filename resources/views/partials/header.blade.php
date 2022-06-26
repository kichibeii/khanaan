<!--== Start Header Area Two ===-->
<header id="header-area" class="header-two">
<div class="preheader-area-wrapper pb-sm-0">
        <div class="container-fluid">
            <div class="preheader-contant-wrap d-flex justify-content-center justify-content-md-between  align-items-center flex-wrap flex-md-nowrap">
                <div class="preheader-left-area mb-0 mb-sm-14">
                    <a href="#"><i class="fa fa-envelope-o"></i> info@khanaan.com</a>
                    <a href="#"><i class="fa fa-clock-o"></i> 10.00 am - 20.00 pm</a>
                </div>

                <div class="preheader-middle-area d-none ">
                    <p class="m-0">Discount 75% akhir bulan ini! Shop now</p>
                </div>

                <div class="preheader-right-area d-flex justify-content-end">
                    <ul class="switcher language-switcher">
                        <li class="dropdown-show">
                            @php
                                $langId = LaravelLocalization::getCurrentLocale();
                                $lang = arrLanguage()[$langId];
                            @endphp
                            <button class="language-switch-btn arrow-toggle"><img class="img-lang" src="{{ $lang['img'] }}" alt="{{ $lang['title'] }}"> {{ __('main.language') }}</button>
                            <input type="hidden" id="language-active" value="{{ $langId }}">

                            <ul class="dropdown-nav">
                                @foreach(arrLanguage() as $k => $v)
                                <li><a href="{{ LaravelLocalization::getLocalizedURL($k) }}"><img class="img-lang" src="{{ $v['img'] }}" alt=""> {{ $v['title'] }}</a></li>
                                @endforeach
                            </ul>

                        </li>
                    </ul>
                    <input type="hidden" id="currency-active" value="{{ currentCurrency() }}">
                    <ul class="switcher currency-switcher">
                        <li class="dropdown-show">

                                <button class="currency-switch-btn arrow-toggle" type="button">
                                    ({{ strtoupper(currentCurrency()) }}) {{ __('main.currency') }}
                                </button>
                                <ul class="dropdown-nav">
                                    <li>
                                        <a href="#" class="currency" data-value="usd">USD</a>
                                    </li>
                                    <li><a href="#" class="currency" data-value="idr">IDR</a></li>
                                </ul>

                        </li>
                    </ul>
                    <form action="/set-currency" method="POST" id="form-currency">
                        @csrf
                        <input type="hidden" name="url" value="{{ \Request::getRequestUri() }}">
                        <input type="hidden" id="currency-now"  name="currency" value="idr">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Bottom Area Start -->
    <div class="header-bottom-area sticky-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-content-wrapper d-flex align-items-center">
                        <div class="header-left-area d-flex align-items-center">
                            <!-- Start Logo Area -->
                            <div class="logo-area">
                                <a href="{{ route('home') }}"><img src="/assets/img/logo.png" alt="Logo"/></a>
                            </div>
                            <!-- End Logo Area -->
                        </div>

                        <div class="header-mainmenu-area d-none d-lg-block">
                            <!-- Start Main Menu -->
                            <nav id="mainmenu-wrap">
                                <ul class="nav mainmenu justify-content-center">
                                    <li><a class="{{ trim($__env->yieldContent('menuActive')) == 'home' ? 'current' : '' }}" href="{{ route('home') }}">{{ __('main.home') }}</a></li>
                                    <li><a class="{{ trim($__env->yieldContent('menuActive')) == 'shop' ? 'current' : '' }}" href="{{ route('shop') }}">{{ __('main.shop') }}</a></li>

                                    <li><a class="{{ trim($__env->yieldContent('menuActive')) == 'collections' ? 'current' : '' }}" href="{{ route('shop.collections') }}">{{ __('main.collections') }}</a></li>
                                    <li><a class="{{ trim($__env->yieldContent('menuActive')) == 'sale' ? 'current' : '' }}" href="{{ route('shop.action', 'sale') }}">{{ __('main.sale') }}</a></li>
                                    <li><a class="{{ trim($__env->yieldContent('menuActive')) == 'blog' ? 'current' : '' }}" href="{{ route('blog') }}">{{ __('main.article') }}</a></li>
                                </ul>
                            </nav>
                            <!-- End Main Menu -->
                        </div>

                        <div class="header-right-area d-flex justify-content-end align-items-center">
                            <button class="search-icon animate-modal-popup" data-mfp-src="#search-box-popup"><i
                                    class="dl-icon-search1"></i></button>
                            <ul class="user-area">
                                <li class="dropdown-show">
                                    @guest
                                    <a class="user-reg" href="{{ route('register') }}"><i class="fa fa-user"></i></a>
                                    @else
                                    <button><i class="fa fa-user"></i></button>
                                    <ul class="dropdown-nav">

                                        <li><a href="{{ route('profile') }}">{{ __('main.my_account') }}</a></li>
                                        <li class="d-none"><a href="{{ route('confirm-payment') }}">{{ __('main.confirm_payment') }}</a></li>
                                        <li><a href="{{ route('track-order') }}">{{ __('main.track_order') }}</a></li>
                                        <li><a href="{{ route('purchase') }}">{{ __('main.history_order') }}</a></li>
                                        <li>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                    @endif
                                </li>
                            </ul>

                            <a href="{{ route('cart') }}" class="mini-cart-icon">
                                <i class="dl-icon-cart1"></i>
                                <span class="cart-count">{{ Cart::count() }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Bottom Area End -->
</header>
<!--== End Header Area Two ===-->

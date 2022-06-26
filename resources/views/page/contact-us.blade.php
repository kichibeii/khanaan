@extends('layouts.veera')
@section('title', $page->title)
@section('menuActive', 'contact-us')
@section('scripts')
<!--=== Ajax Mail Js ===-->
<script src="/assets/js/ajax-mail.js"></script>
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

<!--== Start Contact Page Wrapper ==-->
<div id="contact-page-wrapper" class="pt-90 pt-md-60 pt-sm-50 pb-50 pb-md-20 pb-sm-10">
    <div class="contact-page-top-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-6">
                    <div class="contact-page-form-wrap contact-method">
                        <h3>{{ __('main.get_in_touch') }}</h3>

                        <div class="contact-form-wrap">
                            <form action="{{ route('page.store-contact') }}" method="post" id="contact-form">
                                <div class="single-input-item">
                                    <input type="text" name="name" placeholder="{{ __('main.your_name') }} *" required />
                                </div>

                                <div class="single-input-item">
                                    <input type="email" name="email" placeholder="{{ __('main.email_address') }} *" required />
                                </div>

                                <div class="single-input-item">
                                    <input type="text" name="phone" placeholder="{{ __('main.your_phone') }} *" required />
                                </div>

                                <div class="single-input-item">
                                    <textarea name="con_message" id="con_message" cols="30" rows="7" placeholder="{{ __('main.message') }} *" required></textarea>
                                </div>

                                <button class="btn btn-black">{{ __('main.send_message') }}</button>

                                <div class="form-message"></div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 col-md-6 mt-sm-50">
                    <div class="contact-info-wrapper contact-method">
                        <h3>{{ __('main.contact_info') }}</h3>

                        <div class="contact-info-content">
                            <div class="single-contact-info d-none">
                                <h4>{{ __('main.workshop') }}</h4>
                                <p>Jl. H. Muhi II No. 13
                                    <br> Pondok Pinang, Jakarta Selatan
                                    <br>DKI Jakarta</p>
                            </div>

                            <div class="single-contact-info">
                                <h4>{{ __('main.store') }}</h4>
                                <p>Jl. Cipete Raya No. 7
                                <br>Jakarta Selatan<br />DKI Jakarta</p>
                                <div class="d-none">
                                <br>
                                <p>Jl. Pinang Mas ut 14<br />Pondok Indah, Jakarta Selatan<br />DKI Jakarta</p>

                                <br>
                                <p>Metro Dept. Store<br />Pondok Indah Mall I (PIM I) <br />Jakarta Selatan</p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="contact-page-map-area mt-90 mt-md-60 mt-sm-50">
        <div class="map-area-wrapper">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d991.4757583242296!2d106.80484994799417!3d-6.276478432945483!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1926644167d%3A0x372acc77d7d43e78!2sKhanaan%20Boutique!5e0!3m2!1sen!2sid!4v1608372595784!5m2!1sen!2sid" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
        </div>
    </div>
    <br>
</div>
<!--== End Contact Page Wrapper ==-->

@endsection

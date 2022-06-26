@extends('layouts.veera')

@section('content')
<!-- Start Page Header Wrapper -->
<div class="page-header-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="page-header-content">
                    <h2>Verify</h2>
                    <nav class="page-breadcrumb">
                        <ul class="d-flex justify-content-center">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('verification.notice') }}" class="active">Verify E-mail Address</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page Header Wrapper -->

<!-- Start My Account Wrapper -->
<div id="my-account-page-wrapper" class="pt-88 pt-md-58 pt-sm-48 pb-50 pb-md-20 pb-sm-10">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End My Account Wrapper -->

@endsection

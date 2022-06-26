@extends('layouts.admin.login')
@section('title', 'Login')
@section('styles-before')
<link rel="stylesheet" href="/css/admin/admin-login.css">
@endsection

@section('content')
  <div class="kt-grid kt-grid--ver kt-grid--root">
    <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v3 kt-login--signin" id="kt_login">
      <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url(/assets/admin/media/bg/bg-3.jpg);">
        <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
          <div class="kt-login__container">
            <div class="kt-login__logo">
              <a href="#">
                <img src="/assets/admin/media/logos/logo-5.png">
              </a>
            </div>
            <div class="kt-login__signin">
              <div class="kt-login__head">
                <h3 class="kt-login__title">Sign In To Admin</h3>
              </div>
              <form class="kt-form" method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="input-group">
                  <input class="form-control is-invalid @error('username') is-invalid @enderror" type="text" placeholder="Username" name="username" value="{{ old('email') }}" required autocomplete="email" autofocus>
                  @error('username')
                      <span class="error invalid-feedback" role="alert">
                          {{ $message }}
                      </span>
                  @enderror
                </div>
                <div class="input-group">
                  <input class="form-control @error('password') is-invalid @enderror" type="password" placeholder="Password" name="password" required autocomplete="current-password">
                  @error('password')
                      <span class="error invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                <div class="row kt-login__extra">
                  <div class="col">
                    <label class="kt-checkbox">
                      <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> Ingat saya
                      <span></span>
                    </label>
                  </div>
                  <div class="col kt-align-right">
                    <a href="javascript:;" id="kt_login_forgot" class="kt-login__link">Lupa Password ?</a>
                  </div>
                </div>
                <div class="kt-login__actions">
                  <button id="kt_login_signin_submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Sign In</button>
                </div>
              </form>
            </div>

            <div class="kt-login__forgot">
              <div class="kt-login__head">
                <h3 class="kt-login__title">Forgotten Password ?</h3>
                <div class="kt-login__desc">Enter your email to reset your password:</div>
              </div>
              <form class="kt-form" action="">
                <div class="input-group">
                  <input class="form-control" type="text" placeholder="Email" name="email" id="kt_email" autocomplete="off">
                </div>
                <div class="kt-login__actions">
                  <button id="kt_login_forgot_submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Request</button>&nbsp;&nbsp;
                  <button id="kt_login_forgot_cancel" class="btn btn-light btn-elevate kt-login__btn-secondary">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="/js/admin-login.js"></script>
@endsection

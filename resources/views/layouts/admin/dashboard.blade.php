@extends('layouts.admin.metronic')

@section('content')
<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
	<div class="kt-header-mobile__logo">
  <a href="/" class="a-logo">
    <h4>Fuel <span>Monitoring</span></h4>
		</a>
	</div>
	<div class="kt-header-mobile__toolbar">
					<button class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
		
		<button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
	</div>
</div>
  

  <!-- Start Page -->
  <div class="kt-grid kt-grid--hor kt-grid--root">
    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
			@include('partials.admin.sidebar')
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
				@include('partials.admin.header')
				<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
					@include('partials.admin.subheader')
					<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
						@yield('content-dashboard')
					</div>
        </div>
        @include('partials.admin.footer')
			</div>
    </div>
  </div>
  <!-- End Page -->
@endsection
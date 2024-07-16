<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>A2 COFFEE & EATRY</title>

    @include('mobile.layouts.partials.head')
</head>
<body>
	<div class="site-content">
		<!-- Preloader Start -->
		<div class="loader-mask">
			<div class="circle">
			</div>
		</div>
		<!-- Preloader End -->
		<!-- Header Start -->
        @if (request()->routeIs('mobile.checkout'))
		    @include('mobile.layouts.partials.navbar-back')
        @else
		    @include('mobile.layouts.partials.navbar')
        @endif
		<!-- Header End -->
		<!--Homepage 1 Screen Start -->
		@yield('content')
		<!--Homepage 1 Screen End -->
		<!--SideBar Setting Menu -->
		{{-- @include('mobile.layouts.partials.sidebar') --}}
		<!--SideBar Setting Menu -->
		<!--Bottom TabBar Section Start -->
        @if (!request()->routeIs('mobile.checkout'))
		    @include('mobile.layouts.partials.bottom-nav')
        @endif
		<!--Bottom TabBar Section End -->
		<!-- pwa install app popup Start -->
		{{-- <div class="offcanvas offcanvas-bottom addtohome-popup theme-offcanvas" tabindex="-1" id="offcanvas" aria-modal="true" role="dialog">
			<button type="button" class="btn-close text-reset popup-close-home" data-bs-dismiss="offcanvas" aria-label="Close"></button>
			<div class="offcanvas-body small">
				<img class="logo-popup" src="assets/images/splash-screen/logo.png" alt="logo">
				<p class="title font-w600">Zoop Store</p>
				<p>Install Zoop Retail Store  Multipurpose eCommerce Mobile App Template to your home screen for easy access, just like any other app</p>
				<a href="javascript:void(0)" class="theme-btn install-app btn-inline addhome-btn" id="installApp">Add to Home Screen</a>
			</div>
		</div> --}}
		<!-- pwa install app popup End -->
	</div>

    @include('mobile.layouts.partials.foot')
</body>
</html>

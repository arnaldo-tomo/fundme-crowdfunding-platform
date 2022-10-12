<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="description" content="@yield('description_custom'){{$settings->description}}">
    <meta name="keywords" content="{{$settings->keywords}}" />
    <link rel="shortcut icon" href="{{asset('public/img/favicon.png')}}" />

	<title>@section('title')@show @if( isset( $settings->title ) ){{{$settings->title}}}@endif</title>

	@include('includes.css_general')

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Raleway:100,600' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	@yield('css')

@if($settings->color_default <> '')
<style>
::selection{ background-color: {{$settings->color_default}}; }
::moz-selection{ background-color: {{$settings->color_default}}; }
::webkit-selection{ background-color: {{$settings->color_default}}; }

body a {
    color: {{$settings->color_default}};
}
.color-default {
   color: {{$settings->color_default}};
}
a:hover, a:focus {
    color: {{$settings->color_default}};
}
.navbar-inverse .navbar-nav > li > a.log-in,
.navbar-inverse .navbar-nav > li > a:hover.log-in {
    background-color: {{$settings->color_default}};
}
.navbar-inverse .navbar-nav > .open > .dropdown-menu > li > a:hover,
.navbar-inverse .navbar-nav > .open > .dropdown-menu > li > a:focus,
.dropdown-menu li.active > a {
  background-color: {{$settings->color_default}};
}
.subtitle-color {
    color: {{$settings->color_default}};
}
.boxSuccess {
  background-color: {{$settings->color_default}};
  border-color: {{$settings->color_default}};
}
.btn-main,
.btn-main:hover,
.btn-main:active,
.btn-main:focus {
  background-color: {{$settings->color_default}};
  border-color: {{$settings->color_default}};
}
.line:after {
    background-color: {{$settings->color_default}};
}
.form-control:focus {
  border-color: {{$settings->color_default}};
  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px {{$settings->color_default}};
  box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px {{$settings->color_default}};
}
}
.line-login:after {
    background-color: {{$settings->color_default}};
}
.fa-loader {
    color: {{$settings->color_default}};
}
a:hover.btn-trans,
a:focus.btn-trans,
a:active.btn-trans {
    background-color: {{$settings->color_default}};
    border: 2px solid {{$settings->color_default}};
}
.tooltip-inner {
  background-color: {{$settings->color_default}};
}
.tooltip.top .tooltip-arrow {
  border-top-color: {{$settings->color_default}};
}
.tooltip.top-left .tooltip-arrow {
  border-top-color: {{$settings->color_default}};
}
.tooltip.top-right .tooltip-arrow {
  border-top-color: {{$settings->color_default}};
}
.tooltip.right .tooltip-arrow {
  border-right-color: {{$settings->color_default}};
}
.tooltip.left .tooltip-arrow {
  border-left-color: {{$settings->color_default}};
}
.tooltip.bottom .tooltip-arrow {
  border-bottom-color: {{$settings->color_default}};
}
.tooltip.bottom-left .tooltip-arrow {
  border-bottom-color: {{$settings->color_default}};
}
.tooltip.bottom-right .tooltip-arrow {
  border-bottom-color: {{$settings->color_default}};
}
.headerModal {
	background-color: {{$settings->color_default}};
}
.active-navbar {
	box-shadow: 0 4px {{$settings->color_default}};
	-moz-box-shadow: 0 4px {{$settings->color_default}};
	-webkit-box-shadow: 0 4px {{$settings->color_default}};
}
button:hover.btn-trans,
button:focus.btn-trans,
button:active.btn-trans {
    background-color: {{$settings->color_default}};
    border: 2px solid {{$settings->color_default}};
}
</style>
@endif

	<!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

</head>
<body>

	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/{{config('fb_app.lang')}}/sdk.js#xfbml=1&version=v2.8&appId={{config('fb_app.id')}}";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

	<div class="popout font-default"></div>
	<div class="wrap-loader">
		<i class="fa fa-cog fa-spin fa-3x fa-fw cog-loader"></i>
		<i class="fa fa-cog fa-spin fa-3x fa-fw cog-loader-small"></i>
	</div>
	@include('includes.navbar')

		@yield('content')

			@include('includes.footer')

		@include('includes.javascript_general')

	@yield('javascript')

	<script type="text/javascript">

	Cookies.set('cookieBanner');

		$(document).ready(function() {
    if (Cookies('cookieBanner'));
    else {
    	$('.showBanner').fadeIn();
        $("#close-banner").click(function() {
            $(".showBanner").slideUp(50);
            Cookies('cookieBanner', true);
        });
    }
});
	</script>
<div id="bodyContainer"></div>
</body>
</html>

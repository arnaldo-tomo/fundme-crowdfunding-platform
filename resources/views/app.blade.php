<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="description" content="@yield('description_custom'){{$settings->description}}">
		<meta name="keywords" content="{{ $settings->keywords }}" />
		<link rel="shortcut icon" href="{{ asset('public/img/favicon.png') }}" />
		<title>@section('title')@show @if(isset($settings->title)){{$settings->title}}@endif</title>

		@include('includes.css_general')

		@laravelPWA

		@yield('css')

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
		@include('includes.navbar')

<main role="main" @if (request()->path() != '/')class="padding-top-78"@endif>
		@yield('content')

			@include('includes.footer')
</main>

		@include('includes.javascript_general')

	@yield('javascript')

<div id="bodyContainer"></div>
</body>
</html>

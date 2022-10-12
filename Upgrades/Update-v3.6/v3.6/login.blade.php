<?php $settings = App\Models\AdminSettings::first(); ?>
@extends('app')

@section('title')
{{ trans('auth.login') }} -
@stop

@section('css')
<link href="{{ asset('public/plugins/iCheck/all.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
      <div class="container wrap-jumbotron position-relative">
        <h1 class="title-site">{{ trans('auth.login') }}</h1>
        <p class="subtitle-site"><strong>{{$settings->title}}</strong></p>
      </div>
    </div>

<div class="container margin-bottom-40">

	<div class="row">
<!-- Col MD -->
<div class="col-md-12">

	<h2 class="text-center line position-relative">{{ trans('misc.welcome_back') }}</h2>

	<div class="login-form">

		@include('errors.errors-forms')

					@if (session('login_required'))
			<div class="alert alert-danger" id="dangerAlert">
            		<i class="glyphicon glyphicon-alert myicon-right"></i> {{ session('login_required') }}
            		</div>
            	@endif

          	<form action="{{ url('login') }}" method="post" name="form" id="signup_form">

          		<input type="hidden" name="_token" value="{{ csrf_token() }}">

              @if($settings->captcha == 'on')
                @captcha
              @endif

            <div class="form-group has-feedback">

              <input type="text" class="form-control login-field custom-rounded" value="{{ old('email') }}" name="email" id="username_or_email" placeholder="{{ trans('auth.email') }}" title="{{ trans('auth.email') }}" autocomplete="off">
              <span class="glyphicon glyphicon-user form-control-feedback"></span>
             </div>


            <div class="form-group has-feedback">
              <input type="password" class="form-control login-field custom-rounded" name="password" id="password" placeholder="{{ trans('auth.password') }}" title="{{ trans('auth.password') }}" autocomplete="off">
              <span class="glyphicon glyphicon-lock form-control-feedback"></span>
         </div>

         <div class="row margin-bottom-15">
     	<div class="col-xs-7">
     		<div class="checkbox icheck margin-zero">
				<label class="margin-zero">
					<input @if( old('remember') ) checked="checked" @endif id="keep_login" class="no-show" name="remember" type="checkbox" value="1">
					<span class="keep-login-title">{{ trans('auth.remember_me') }}</span>
			</label>
		</div>
     	</div>

     	<div class="col-xs-5">
     		<label class="btn-block">
		   <a href="{{url('/password/reset')}}" class="label-terms recover">{{ trans('auth.forgot_password') }}</a>
		</label>
     	</div>
     </div><!-- row -->

           <button type="submit" id="buttonSubmit" class="btn btn-block btn-lg btn-main custom-rounded btn-auth">{{ trans('auth.sign_in') }}</button>

           @if($settings->facebook_login == 'on' && $settings->registration_active == 'on' || $settings->google_login == 'on' && $settings->registration_active == 'on')
           <div class="login-link auth-social" id="twitter-btn-text">

           @if($settings->facebook_login == 'on')
               <div class="facebook-login auth-social" id="twitter-btn">
                 <a href="{{url('oauth/facebook')}}" class="btn btn-block btn-lg fb-button custom-rounded" style="color:white;"><i class="fa fa-facebook-square myicon-right"></i> {{trans('misc.sign_in_with')}} Facebook</a>
               </div>
             @endif

             @if($settings->google_login == 'on')
                 <div class="google-login auth-social">
                   <a href="{{url('oauth/google')}}" class="btn btn-block btn-lg btn-default custom-rounded"><img src="{{url('public/img/google.svg')}}" width="18" height="18" style="vertical-align: text-bottom;" class="myicon-right"> {{trans('misc.sign_in_with')}} Google</a>
                 </div>
               @endif
             </div>
             @endif

          </form>

     </div><!-- Login Form -->

 </div><!-- /COL MD -->

</div><!-- ROW -->

 </div><!-- row -->

 <!-- container wrap-ui -->

@endsection

@section('javascript')
	<script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}"></script>

	<script type="text/javascript">

	$('#username_or_email').focus();

	$(document).ready(function(){
	  $('input').iCheck({
	  	checkboxClass: 'icheckbox_square-red',
    	radioClass: 'iradio_square-red',
	    increaseArea: '20%' // optional
	  });
	});

	@if (count($errors) > 0)
    	scrollElement('#dangerAlert');
    @endif
</script>
@endsection

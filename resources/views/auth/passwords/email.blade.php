@extends('app')

@section('title') {{trans('auth.password_recover')}} -@endsection

@section('content')
  <div class="py-5 bg-primary bg-sections">
    <div class="btn-block text-center text-white position-relative">
      <h1>{{ trans('auth.password_recover') }}</h1>
      <p>{{$settings->title}}</p>
    </div>
  </div><!-- container -->

<div class="py-5 bg-white text-center">
<div class="container margin-bottom-40">

	<div class="row">
<!-- Col MD -->
<div class="col-md-12">
	<div class="d-flex justify-content-center">

        <form action="{{ url('password/email') }}" method="post" class="login-form text-left @if (count($errors) > 0)vivify shake @endif" name="form" id="signup_form">

          @csrf

          @if($settings->captcha == 'on')
            @captcha
          @endif

          @include('errors.errors-forms')

          @if (session('status'))
  						<div class="alert alert-success">
  							{{ session('status') }}
  						</div>
      			@endif

          <div class="form-group">
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
            <input type="email" class="form-control" required value="{{ old('email') }}" name="email" id="email" placeholder="{{ trans('auth.email') }}" title="{{ trans('auth.email') }}" autocomplete="off">
          </div>
          </div>

          <button type="submit" class="btn btn-block mt-2 py-2 btn-primary font-weight-light">{{ trans('auth.send') }}</button>
          @if ($settings->captcha == 'on')
            <small class="btn-block text-center">{{trans('misc.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{trans('misc.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{trans('misc.terms')}}</a></small>
          @endif
          <div class="text-center mt-2">
            <a href="{{url('login')}}"><i class="fa fa-long-arrow-alt-left"></i> {{ trans('auth.back') }}</a>
          </div>
        </form>
     </div><!-- Login Form -->
   </div><!-- /COL MD -->
  </div><!-- ./ -->
  </div><!-- ./ -->
</div>
 <!-- container wrap-ui -->

@endsection

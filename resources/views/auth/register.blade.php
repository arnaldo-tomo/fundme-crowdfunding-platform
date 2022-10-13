@extends('app')

@section('title') {{trans('auth.sign_up')}} -@endsection

@section('content')
  <div class="py-5 bg-primary bg-sections">
    <div class="btn-block text-center text-white position-relative">
      <h1>{{ trans('auth.sign_up') }}</h1>
      <p>{{$settings->title}}</p>
    </div>
  </div><!-- container -->

<div class="py-5 bg-white text-center">
<div class="container margin-bottom-40">

	<div class="row">
<!-- Col MD -->
<div class="col-md-12">
	<div class="d-flex justify-content-center">

        <form action="{{ url('register') }}" method="post" class="login-form text-left @if (count($errors) > 0)vivify shake @endif" name="form" id="signup_form">

          @csrf

          @if($settings->captcha == 'on')
            @captcha
          @endif

          @include('errors.errors-forms')

          @if (session('login_required'))
      			<div class="alert alert-danger" id="dangerAlert">
            		<i class="fa fa-warning"></i> {{ session('login_required') }}
            		</div>
              @endif

              @if (session('notification'))
      						<div class="alert alert-success text-center">
      							<i class="fa fa-check-circle mr-1"></i> {{ session('notification') }}
      						</div>
          		@endif

          @if($settings->facebook_login == 'on')
              <div class="mb-2">
                <a href="{{url('oauth/facebook')}}" class="btn btn-block btn-lg fb-button no-hover text-white"><i class="fab fa-facebook mr-2"></i> {{trans('misc.sign_up_with')}} Facebook</a>
              </div>
            @endif

            @if($settings->google_login == 'on')
                <div class="mb-2">
                  <a href="{{url('oauth/google')}}" class="btn btn-block btn-lg google-button no-hover"><img src="{{url('public/img/google.svg')}}" width="18" height="18" class="mr-2 align-text-bottom"> {{trans('misc.sign_up_with')}} Google</a>
                </div>
              @endif

              <div class="form-group">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control" required value="{{ old('name') }}" name="name" placeholder="{{ trans('auth.full_name') }}" title="{{ trans('users.full_name') }}">
              </div>
              </div>

          <div class="form-group">
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
            <input type="email" class="form-control" required value="{{ old('email') }}" name="email" placeholder="{{ trans('auth.email') }}" title="{{ trans('auth.email') }}">
          </div>
          </div>

          <div class="form-group">
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-key"></i></span>
            <input type="password" class="form-control" required name="password" id="password" placeholder="{{ trans('auth.password') }}" title="{{ trans('auth.password') }}" autocomplete="off">
          </div>
          </div>

          <div class="form-group">
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
            <input type="password" class="form-control" required name="password_confirmation" placeholder="{{ trans('auth.confirm_password') }}" title="{{ trans('auth.confirm_password') }}" autocomplete="off">
          </div>
          </div>

          <div class="form-group">
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-globe"></i></span>
              <select required class="form-select" name="countries_id">
                <option value="">{{ trans('misc.select_your_country') }}</option>
                @foreach (App\Models\Countries::orderBy('country_name')->get() as $country )
                    <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                  @endforeach
              </select>
            </div>
          </div>

          <div class="form-check my-1">
           <input type="checkbox" required @if( old('agree_gdpr') ) checked="checked" @endif class="form-check-input" name="agree_gdpr" id="customControlInline" value="1">
           <label class="form-check-label" for="customControlInline">{{ trans('admin.i_agree_gdpr') }}
           @if($settings->link_privacy != '')
             <a href="{{$settings->link_privacy}}" target="_blank">{{ trans('admin.privacy_policy') }}</a>
           @endif
           </label>

         </div>
          <button type="submit" class="btn btn-block mt-2 py-2 btn-primary font-weight-light">{{ trans('auth.sign_in') }}</button>
          @if ($settings->captcha == 'on')
            <small class="btn-block text-center">{{trans('misc.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{trans('misc.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{trans('misc.terms')}}</a></small>
          @endif
          <div class="text-center mt-2">
            <a href="{{url('login')}}">{{ trans('auth.already_have_an_account') }}</a>
          </div>
        </form>
     </div><!-- Login Form -->
   </div><!-- /COL MD -->
  </div><!-- ./ -->
  </div><!-- ./ -->
</div>
 <!-- container wrap-ui -->

@endsection

@section('javascript')
  @if (session('notification'))
	<script type="text/javascript">
    	$('#signup_form, #dangerAlert').remove();
</script>
  @endif
@endsection

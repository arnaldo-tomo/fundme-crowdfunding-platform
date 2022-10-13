@extends('app')

@section('title') {{ trans('auth.password') }} - @endsection

@section('content')
<div class="jumbotron mb-0 bg-sections text-center">
      <div class="container position-relative">
        <h1>{{ trans('auth.password') }}</h1>
        <p class="mb-0">
          {{ trans('misc.password_desc') }}
        </p>
      </div>
    </div>

<div class="container py-5">

  <div class="row">

    <div class="col-md-3">
      @include('users.navbar-settings')
    </div>

		<!-- Col MD -->
		<div class="col-md-9">
			@if (session('notification'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            		{{ session('notification') }}
            		</div>
            	@endif

            	 @if (session('incorrect_pass'))
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
            		{{ session('incorrect_pass') }}
            		</div>
            	@endif

			@include('errors.errors-forms')

		<!-- ***** FORM ***** -->
       <form action="{{ url('account/password') }}" method="post" name="form">

          	<input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-floating mb-3">
             <input type="password" required class="form-control" id="input-oldpassword" name="old_password" placeholder="{{ trans('misc.old_password') }}">
             <label for="input-oldpassword">{{ trans('misc.old_password') }}</label>
           </div>

           <div class="form-floating mb-3">
            <input type="password" required class="form-control" id="input-password" name="password" placeholder="{{ trans('misc.new_password') }}">
            <label for="input-password">{{ trans('misc.new_password') }}</label>
          </div>

           <button type="submit" id="buttonSubmit" class="btn btn-block btn-lg btn-primary no-hover">{{ trans('misc.save_changes') }}</button>
       </form><!-- ***** END FORM ***** -->

		</div><!-- /COL MD -->
    </div><!-- / Wrap -->
 </div><!-- container -->

 <!-- container wrap-ui -->
@endsection

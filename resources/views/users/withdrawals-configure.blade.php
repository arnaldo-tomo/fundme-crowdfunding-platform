@extends('app')

@section('title') {{ trans('misc.withdrawals') }} {{ trans('misc.configure') }} - @endsection

@section('content')
<div class="jumbotron mb-0 bg-sections text-center">
      <div class="container position-relative">
        <h1>{{ trans('misc.withdrawals') }} {{ trans('misc.configure') }}</h1>
        <p class="mb-0">
          <strong>{{ trans('misc.default_withdrawal') }}</strong>: @if (auth()->user()->payment_gateway == '') {{trans('misc.unconfigured')}} @else {{auth()->user()->payment_gateway}} @endif
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
			@if (session('success'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            		{{ session('success') }}
            		</div>
            	@endif

              @if (session('error'))
        			<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    		{{ session('error') }}
                    		</div>
                    	@endif

			@include('errors.errors-forms')

      <h6 class="mb-2">PayPal</h6>
    <!-- ***** FORM ***** -->
       <form action="{{ url('withdrawals/configure/paypal') }}" method="post" class="mb-5">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-floating mb-3">
             <input type="email" class="form-control" id="input-paypal" value="{{auth()->user()->paypal_account}}" name="email_paypal" placeholder="{{ trans('misc.email_paypal') }}">
             <label for="input-paypal">{{ trans('admin.paypal_account') }}</label>
           </div>

           <div class="form-floating mb-3">
            <input type="email" class="form-control" id="input-confirm_email" name="email_paypal_confirmation" placeholder="{{ trans('misc.confirm_email') }}">
            <label for="input-confirm_email">{{ trans('misc.confirm_email') }}</label>
          </div>

           <button type="submit" class="btn w-100 btn-lg btn-primary no-hover">{{ trans('admin.save') }}</button>
       </form><!-- ***** END FORM ***** -->

       <h6 class="mb-2">{{ trans('misc.bank_transfer') }}</h6>
    <!-- ***** FORM ***** -->
        <form action="{{ url('withdrawals/configure/bank') }}" method="post" name="form" class="mb-5">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-floating mb-3">
             <textarea class="form-control" placeholder="{{ trans('misc.bank_details') }}" name="bank" id="input-bank_details" style="height: 100px">{{ auth()->user()->bank }}</textarea>
             <label for="input-bank_details">{{ trans('misc.bank_details') }}</label>
           </div>

            <button type="submit" class="btn w-100 btn-lg btn-primary no-hover">{{ trans('admin.save') }}</button>
        </form><!-- ***** END FORM ***** -->

		</div><!-- /COL MD -->
    </div><!-- / Wrap -->
 </div><!-- container -->

 <!-- container wrap-ui -->
@endsection

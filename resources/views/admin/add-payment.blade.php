@extends('admin.layout')

@section('css')
<link href="{{ asset('public/js/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/js/select2/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('misc.add_payment') }}</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			@if (session('success_message'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check2 me-1"></i>	{{ session('success_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
                </div>
              @endif

              @include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form method="POST" action="{{ url('panel/admin/payment/add') }}" enctype="multipart/form-data">
             @csrf

						 <div class="row mb-3">
 		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.campaign') }}</label>
 		          <div class="col-sm-10">
 		            <select name="campaign" class="form-select select2">
 									<option value="">{{trans('misc.select_one')}}</option>
									@foreach(App\Models\Campaigns::where('status', 'active')->where('finalized','0')->orderBy('id')->get() as $campaign)
	                    <option value="{{$campaign->id}}">ID #{{ $campaign->id }} - {{ $campaign->title }}</option>
	                    @endforeach
 		           </select>
 		          </div>
 		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.donation') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ old('donation') }}" name="donation" type="number" class="form-control">
		          </div>
		        </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('auth.full_name') }}</label>
              <div class="col-sm-10">
                <input value="{{ old('full_name') }}" name="full_name" type="text" class="form-control">
              </div>
            </div>

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('auth.email') }}</label>
		          <div class="col-sm-10">
	                <input value="{{ old('email') }}" name="email" type="email" class="form-control">
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.country') }}</label>
		          <div class="col-sm-10">
		            <select name="country" class="form-select select2">
									<option value="">{{trans('misc.select_one')}}</option>
									@foreach (App\Models\Countries::orderBy('country_name')->get() as $country)
										<option value="{{$country->country_name}}">{{ $country->country_name }}</option>
									@endforeach
		           </select>
		          </div>
		        </div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.postal_code') }}</label>
              <div class="col-sm-10">
                <input value="{{ old('postal_code') }}" name="postal_code" type="text" class="form-control">
              </div>
            </div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.transaction_id') }}</label>
              <div class="col-sm-10">
                <input value="{{ old('transaction_id') }}" name="transaction_id" type="text" class="form-control">
              </div>
            </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.payment_gateway') }}</label>
		          <div class="col-sm-10">
		            <select name="payment_gateway" class="form-select select2">
									<option value="">{{trans('misc.select_one')}}</option>
									@foreach (PaymentGateways::where('enabled', '1')->where('type', '<>', 'bank')->orderBy('type')->get() as $payment)
										<option value="{{$payment->name}}">{{ $payment->name }}</option>
									@endforeach
		           </select>
		          </div>
		        </div>


						<div class="row mb-3">
		          <div class="col-sm-10 offset-sm-2">
		            <button type="submit" class="btn btn-dark mt-3 px-5">{{ __('admin.save') }}</button>
		          </div>
		        </div>

		       </form>

				 </div><!-- card-body -->
 			</div><!-- card  -->
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection

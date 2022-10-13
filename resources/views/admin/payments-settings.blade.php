@extends('admin.layout')

@section('css')
<link href="{{ asset('public/js/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/js/select2/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('misc.payment_settings') }}</span>
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

					 <form method="POST" action="{{ url('panel/admin/payments') }}" enctype="multipart/form-data">
             @csrf

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.currency_code') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $settings->currency_code }}" name="currency_code" type="text" class="form-control">
		          </div>
		        </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.currency_symbol') }}</label>
              <div class="col-sm-10">
                <input value="{{ $settings->currency_symbol }}" name="currency_symbol" type="text" class="form-control">
                <small class="d-block">{{ __('admin.notice_currency') }}</small>
              </div>
            </div>

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.fee_donation') }}</label>
		          <div class="col-sm-10">
		            <select name="fee_donation" class="form-select">
                  @for ($i=1; $i <= 50; ++$i)
                    <option @if ($settings->fee_donation == $i) selected="selected" @endif value="{{$i}}">{{$i}}%</option>
                    @endfor
		           </select>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.currency_position') }}</label>
		          <div class="col-sm-10">
		            <select name="currency_position" class="form-select">
                  <option @if ($settings->currency_position == 'left') selected="selected" @endif value="left">{{$settings->currency_symbol}}99 - {{trans('admin.left')}}</option>
                  <option @if ($settings->currency_position == 'right') selected="selected" @endif value="right">99{{$settings->currency_symbol}} {{trans('admin.right')}}</option>
		           </select>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.decimal_format') }}</label>
		          <div class="col-sm-10">
		            <select name="decimal_format" class="form-select">
                  <option @if ($settings->decimal_format == 'dot') selected="selected" @endif value="dot">1,999.95</option>
                  <option @if ($settings->decimal_format == 'comma') selected="selected" @endif value="comma">1.999,95</option>
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

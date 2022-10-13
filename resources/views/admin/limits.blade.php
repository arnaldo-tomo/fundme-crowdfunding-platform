@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.general_settings') }}</span>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ trans('admin.limits') }}</span>
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

					 <form method="POST" action="{{ url('panel/admin/settings/limits') }}" enctype="multipart/form-data">
             @csrf

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.result_request_campaigns') }}</label>
		          <div class="col-sm-10">
		            <select name="result_request_campaigns" class="form-select">
									<option @if( $settings->result_request == 3 ) selected="selected" @endif value="3">3</option>
									<option @if( $settings->result_request == 6 ) selected="selected" @endif value="6">6</option>
									<option @if( $settings->result_request == 9 ) selected="selected" @endif value="9">9</option>
									<option @if( $settings->result_request == 12 ) selected="selected" @endif value="12">12</option>
									<option @if( $settings->result_request == 15 ) selected="selected" @endif value="15">15</option>
									<option @if( $settings->result_request == 18 ) selected="selected" @endif value="18">18</option>
									<option @if( $settings->result_request == 21 ) selected="selected" @endif value="21">21</option>
									<option @if( $settings->result_request == 24 ) selected="selected" @endif value="24">24</option>
									<option @if( $settings->result_request == 27 ) selected="selected" @endif value="27">27</option>
									<option @if( $settings->result_request == 30 ) selected="selected" @endif value="30">30</option>
									<option @if( $settings->result_request == 33 ) selected="selected" @endif value="33">33</option>
									<option @if( $settings->result_request == 37 ) selected="selected" @endif value="37">37</option>
									<option @if( $settings->result_request == 40 ) selected="selected" @endif value="40">40</option>
		           </select>
		          </div>
		        </div><!-- end row -->

						<div class="row mb-3">
             <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.file_size_allowed') }}</label>
             <div class="col-sm-10">
               <select name="file_size_allowed" class="form-select">
                  <option @if ($settings->file_size_allowed == 1024) selected="selected" @endif value="1024">1 MB</option>
                  <option @if ($settings->file_size_allowed == 2048) selected="selected" @endif value="2048">2 MB</option>
                  <option @if ($settings->file_size_allowed == 3072) selected="selected" @endif value="3072">3 MB</option>
                  <option @if ($settings->file_size_allowed == 4096) selected="selected" @endif value="4096">4 MB</option>
                  <option @if ($settings->file_size_allowed == 5120) selected="selected" @endif value="5120">5 MB</option>
                  <option @if ($settings->file_size_allowed == 10240) selected="selected" @endif value="10240">10 MB</option>
                  <option @if ($settings->file_size_allowed == 15360) selected="selected" @endif value="15360">15 MB</option>
                  <option @if ($settings->file_size_allowed == 20480) selected="selected" @endif value="20480">20 MB</option>
                  <option @if ($settings->file_size_allowed == 30720) selected="selected" @endif value="30720">30 MB</option>
                  <option @if ($settings->file_size_allowed == 40960) selected="selected" @endif value="40960">40 MB</option>
                  <option @if ($settings->file_size_allowed == 51200) selected="selected" @endif value="51200">50 MB</option>
                  <option @if ($settings->file_size_allowed == 102400) selected="selected" @endif value="102400">100 MB</option>
              </select>

              <small class="d-block w-100">
                {{ trans('admin.upload_max_filesize_info') }} <strong><?php echo str_replace('M', 'MB', ini_get('upload_max_filesize')) ?></strong>
              </small>
             </div>
           </div><!-- end row -->

					 <div class="row mb-3">
						 <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.min_campaign_amount') }}</label>
						 <div class="col-sm-10">
							 <input value="{{ $settings->min_campaign_amount }}" name="min_campaign_amount" type="number" min="1" autocomplete="off" class="form-control">
						 </div>
					 </div><!-- end row -->

					 <div class="row mb-3">
						 <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.max_campaign_amount') }}</label>
						 <div class="col-sm-10">
							 <input value="{{ $settings->max_campaign_amount }}" name="max_campaign_amount" type="number" min="1" autocomplete="off" class="form-control">
						 </div>
					 </div><!-- end row -->

					 <div class="row mb-3">
						 <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.min_donation_amount') }}</label>
						 <div class="col-sm-10">
							 <input value="{{ $settings->min_donation_amount }}" name="min_donation_amount" type="number" min="1" autocomplete="off" class="form-control">
						 </div>
					 </div><!-- end row -->

					 <div class="row mb-3">
						 <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.max_donation_amount') }}</label>
						 <div class="col-sm-10">
							 <input value="{{ $settings->max_donation_amount }}" name="max_donation_amount" type="number" min="1" autocomplete="off" class="form-control">
						 </div>
					 </div><!-- end row -->


            <div class="row mb-3">
              <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-dark mt-3 px-5">{{ __('admin.save') }}</button>
              </div>
            </div><!-- end row -->

		       </form>

				 </div><!-- card-body -->
 			</div><!-- card  -->
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection

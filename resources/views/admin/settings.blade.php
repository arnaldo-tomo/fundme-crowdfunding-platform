@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.general_settings') }}</span>
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

      <form method="POST" action="{{ url('panel/admin/settings') }}" enctype="multipart/form-data">
        @csrf

       <div class="row mb-3">
         <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.name_site') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->title }}" name="title" class="form-control">
         </div>
       </div><!-- end row -->

			 <div class="row mb-3">
         <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.welcome_text') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->welcome_text }}" name="welcome_text" class="form-control">
         </div>
       </div><!-- end row -->

			 <div class="row mb-3">
         <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.welcome_subtitle') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->welcome_subtitle }}" name="welcome_subtitle" class="form-control">
         </div>
       </div><!-- end row -->

			 <div class="row mb-3">
				 <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.description') }}</label>
				 <div class="col-sm-10">
					 <textarea class="form-control" name="description" rows="4">{{ $settings->description }}</textarea>
				 </div>
			 </div>

			 <div class="row mb-3">
         <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.email_no_reply') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->email_no_reply }}" name="email_no_reply" class="form-control">
         </div>
       </div><!-- end row -->

			 <div class="row mb-3">
         <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.email_admin') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->email_admin }}" name="email_admin" class="form-control">
         </div>
       </div><!-- end row -->

			 <div class="row mb-3">
         <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.keywords') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->keywords }}" name="keywords" class="form-control">
         </div>
       </div><!-- end row -->

       <div class="row mb-3">
         <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.link_terms') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->link_terms }}" name="link_terms" class="form-control">
         </div>
       </div><!-- end row -->

       <div class="row mb-3">
         <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.link_privacy') }}</label>
         <div class="col-sm-10">
           <input type="text" value="{{ $settings->link_privacy }}" name="link_privacy" class="form-control">
           <small class="d-block"></small>
         </div>
       </div><!-- end row -->

			 <div class="row mb-3">
				 <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.date_format') }}</label>
				 <div class="col-sm-10">
					 <select name="date_format" class="form-select">
						 <option @if( $settings->date_format == 'M d, Y' ) selected="selected" @endif value="M d, Y"><?php echo date('M d, Y'); ?></option>
							 <option @if( $settings->date_format == 'd M, Y' ) selected="selected" @endif value="d M, Y"><?php echo date('d M, Y'); ?></option>
						 <option @if( $settings->date_format == 'Y-m-d' ) selected="selected" @endif value="Y-m-d"><?php echo date('Y-m-d'); ?></option>
							 <option @if( $settings->date_format == 'm/d/Y' ) selected="selected" @endif  value="m/d/Y"><?php echo date('m/d/Y'); ?></option>
								 <option @if( $settings->date_format == 'd/m/Y' ) selected="selected" @endif  value="d/m/Y"><?php echo date('d/m/Y'); ?></option>
					</select>
				 </div>
			 </div>

       <div class="row mb-3">
				 <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.default_language') }}</label>
				 <div class="col-sm-10">
					 <select name="default_language" class="form-select">
						 @foreach (App\Models\Languages::orderBy('name')->get() as $language)
 							<option @if ($language->abbreviation == env('DEFAULT_LOCALE')) selected="selected" @endif value="{{$language->abbreviation}}">{{ $language->name }}</option>
 						@endforeach
					</select>
					<small class="d-block">{{ __('misc.default_language_info') }}</small>
				 </div>
			 </div>

			 <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('admin.new_registrations') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="registration_active" @if ($settings->registration_active == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('misc.auto_approve_campaigns') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="auto_approve_campaigns" @if ($settings->auto_approve_campaigns == '1') checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('admin.email_verification') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="email_verification" @if ($settings->email_verification == '1') checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

       <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">Captcha</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="captcha" @if ($settings->captcha == 'on') checked="checked" @endif value="1" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('misc.captcha_on_donations') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="captcha_on_donations" @if ($settings->captcha_on_donations == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('misc.facebook_login') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="facebook_login" @if ($settings->facebook_login == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->

			 <fieldset class="row mb-3">
         <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('misc.google_login') }}</legend>
         <div class="col-sm-10">
           <div class="form-check form-switch form-switch-md">
            <input class="form-check-input" type="checkbox" name="google_login" @if ($settings->google_login == 'on') checked="checked" @endif value="on" role="switch">
          </div>
         </div>
       </fieldset><!-- end row -->


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

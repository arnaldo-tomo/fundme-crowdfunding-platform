@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">Progressive Web Apps (PWA)</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			@if (session('success_message'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check2 me-1"></i>	{{ session('success_message') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                  <i class="bi bi-x-lg"></i>
                </button>
                </div>
              @endif

              @include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					<div class="row">
						<div class="col-sm-10 offset-sm-2">
							<div class="alert alert-info py-2">
							 <i class="bi-info-circle me-2"></i> {{ __('misc.alert_pwa_https') }}
							</div>
						</div>
					</div>

					 <form method="POST" action="{{ url('panel/admin/pwa') }}" enctype="multipart/form-data">
						 @csrf

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('misc.pwa_short_name') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ env('PWA_SHORT_NAME') }}" name="PWA_SHORT_NAME" type="text" class="form-control">
		          </div>
		        </div>

						<div class="row">
							<div class="col-sm-10 offset-sm-2">
								<div class="alert alert-info py-2">
								 PNG ICONS for the PWA <a class="text-dark text-decoration-underline" href="https://maskable.app/editor" target="_blank">https://maskable.app/editor <i class="bi-box-arrow-up-right ms-1"></i></a>
								</div>
							</div>
						</div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">72x72 Maskable Icon</label>
              <div class="col-lg-5 col-sm-10">
                <div class="input-group mb-1">
                  <input accept="image/*" name="files[PWA_ICON_72]" type="file" class="form-control custom-file rounded-pill">
                </div>
              </div>
            </div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">96x96 Icon</label>
              <div class="col-lg-5 col-sm-10">
                <div class="input-group mb-1">
                  <input accept="image/*" name="files[PWA_ICON_96]" type="file" class="form-control custom-file rounded-pill">
                </div>
              </div>
            </div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">128x128 Icon</label>
              <div class="col-lg-5 col-sm-10">
                <div class="input-group mb-1">
                  <input accept="image/*" name="files[PWA_ICON_128]" type="file" class="form-control custom-file rounded-pill">
                </div>
              </div>
            </div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">144x144 Icon</label>
              <div class="col-lg-5 col-sm-10">
                <div class="input-group mb-1">
                  <input accept="image/*" name="files[PWA_ICON_144]" type="file" class="form-control custom-file rounded-pill">
                </div>
              </div>
            </div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">152x152 Icon</label>
              <div class="col-lg-5 col-sm-10">
                <div class="input-group mb-1">
                  <input accept="image/*" name="files[PWA_ICON_152]" type="file" class="form-control custom-file rounded-pill">
                </div>
              </div>
            </div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">384x384 Icon</label>
              <div class="col-lg-5 col-sm-10">
                <div class="input-group mb-1">
                  <input accept="image/*" name="files[PWA_ICON_384]" type="file" class="form-control custom-file rounded-pill">
                </div>
              </div>
            </div>

						<div class="row mb-3">
              <label class="col-sm-2 col-form-label text-lg-end">512x512 Icon</label>
              <div class="col-lg-5 col-sm-10">
                <div class="input-group mb-1">
                  <input accept="image/*" name="files[PWA_ICON_512]" type="file" class="form-control custom-file rounded-pill">
                </div>
              </div>
            </div>

					<hr/>

					<div class="row">
						<div class="col-sm-10 offset-sm-2">
							<div class="alert alert-info py-2">
							 PNG SPLASH screens for the PWA <a class="text-dark text-decoration-underline" href="https://appsco.pe/developer/splash-screens" target="_blank">https://appsco.pe/developer/splash-screens <i class="bi-box-arrow-up-right ms-1"></i></a>
							</div>
						</div>
					</div>

					<div class="row mb-3">
						<label class="col-sm-2 col-form-label text-lg-end">640x1136</label>
						<div class="col-lg-5 col-sm-10">
							<div class="input-group mb-1">
								<input accept="image/*" name="files[PWA_SPLASH_640]" type="file" class="form-control custom-file rounded-pill">
							</div>
						</div>
					</div>

					<div class="row mb-3">
						<label class="col-sm-2 col-form-label text-lg-end">750x1334</label>
						<div class="col-lg-5 col-sm-10">
							<div class="input-group mb-1">
								<input accept="image/*" name="files[PWA_SPLASH_750]" type="file" class="form-control custom-file rounded-pill">
							</div>
						</div>
					</div>

					<div class="row mb-3">
						<label class="col-sm-2 col-form-label text-lg-end">1125x2436</label>
						<div class="col-lg-5 col-sm-10">
							<div class="input-group mb-1">
								<input accept="image/*" name="files[PWA_SPLASH_1125]" type="file" class="form-control custom-file rounded-pill">
							</div>
						</div>
					</div>

					<div class="row mb-3">
						<label class="col-sm-2 col-form-label text-lg-end">1242x2208</label>
						<div class="col-lg-5 col-sm-10">
							<div class="input-group mb-1">
								<input accept="image/*" name="files[PWA_SPLASH_1242]" type="file" class="form-control custom-file rounded-pill">
							</div>
						</div>
					</div>

					<div class="row mb-3">
						<label class="col-sm-2 col-form-label text-lg-end">1536x2048</label>
						<div class="col-lg-5 col-sm-10">
							<div class="input-group mb-1">
								<input accept="image/*" name="files[PWA_SPLASH_1536]" type="file" class="form-control custom-file rounded-pill">
							</div>
						</div>
					</div>

					<div class="row mb-3">
						<label class="col-sm-2 col-form-label text-lg-end">1668x2224</label>
						<div class="col-lg-5 col-sm-10">
							<div class="input-group mb-1">
								<input accept="image/*" name="files[PWA_SPLASH_1668]" type="file" class="form-control custom-file rounded-pill">
							</div>
						</div>
					</div>

					<div class="row mb-3">
						<label class="col-sm-2 col-form-label text-lg-end">2048x2732</label>
						<div class="col-lg-5 col-sm-10">
							<div class="input-group mb-1">
								<input accept="image/*" name="files[PWA_SPLASH_2048]" type="file" class="form-control custom-file rounded-pill">
							</div>
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

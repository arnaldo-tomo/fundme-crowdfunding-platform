@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.theme') }}</span>
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

					 <form method="post" action="{{{ url('panel/admin/theme') }}}" enctype="multipart/form-data">
             @csrf

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Logo</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img class="bg-secondary" src="{{url('/public/img/logo.png')}}" style="width:100px">
                </div>

                <div class="input-group mb-1">
                  <input name="logo" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 150x50 px (PNG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.logo_footer') }}</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img/watermark.png')}}" style="width:100px">
                </div>

                <div class="input-group mb-1">
                  <input name="logo_footer" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 150x50 px (PNG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Favicon</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img/favicon.png')}}">
                </div>

                <div class="input-group mb-1">
                  <input name="favicon" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 48x48 px (PNG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.index_image_bottom') }}</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img/cover.jpg')}}" style="width:200px">
                </div>

                <div class="input-group mb-1">
                  <input name="index_image_bottom" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 1280x850 px (JPG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Slider 1</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img/slider-1.jpg')}}" style="width:200px">
                </div>

                <div class="input-group mb-1">
                  <input name="slider_1" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 1600x900 px (JPG)</small>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Slider 2</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img/slider-2.jpg')}}" style="width:200px">
                </div>

                <div class="input-group mb-1">
                  <input name="slider_2" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 1600x900 px (JPG)</small>
		          </div>
		        </div>

						<div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Slider 3</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img/slider-3.jpg')}}" style="width:200px">
                </div>

                <div class="input-group mb-1">
                  <input name="slider_3" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 1600x900 px (JPG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">Avatar default</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/avatar/default.jpg')}}" style="width:180px">
                </div>

                <div class="input-group mb-1">
                  <input name="avatar" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 180x180 px (JPG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{trans('misc.image_category')}}</label>
		          <div class="col-lg-5 col-sm-10">
                <div class="d-block mb-2">
                  <img src="{{url('/public/img-category/default.jpg')}}" style="width:200px">
                </div>

                <div class="input-group mb-1">
                  <input name="img_category" type="file" class="form-control custom-file rounded-pill">
                </div>
                <small class="d-block">{{ __('misc.recommended_size') }} 457x359 px (JPG)</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ __('misc.default_link_color') }}</label>
		          <div class="col-sm-10">
                <input type="color" name="color" class="form-control form-control-color" value="{{ $settings->color_default }}">
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

@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <a class="text-reset" href="{{ url('panel/admin/blog') }}">{{ __('misc.blog') }}</a>
			<i class="bi-chevron-right me-1 fs-6"></i>
			<span class="text-muted">{{ __('admin.edit') }}</span>
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

					 <form method="post" action="{{ url('panel/admin/blog/update') }}" enctype="multipart/form-data">
						 <input type="hidden" name="id" value="{{ $data->id }}">
             @csrf

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.title') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $data->title }}" name="title" type="text" class="form-control">
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.tags') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $data->tags }}" name="tags"  type="text" class="form-control">
		          </div>
		        </div>

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.thumbnail') }}</label>
		          <div class="col-sm-10">
								<div class="input-group mb-1">
                  <input name="thumbnail" type="file" accept="image/*" class="form-control custom-file">
                </div>
								<small class="d-block">{{ __('misc.recommended_size') }} 650x430</small>
		          </div>
		        </div>

            <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('admin.content') }}</label>
		          <div class="col-sm-10">
                <textarea class="form-control" name="content" rows="4" id="content">{{ $data->content }}</textarea>
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

@section('javascript')
<script src="{{ asset('public/js/ckeditor-init.js') }}?v={{$settings->version}}" type="text/javascript"></script>
@endsection

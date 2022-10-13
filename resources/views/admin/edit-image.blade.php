@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <a class="text-reset" href="{{ url('panel/admin/images') }}">{{ __('misc.images') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ __('admin.edit') }}</span>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <span class="text-muted">{{ $data->title }}</span>
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

    @include('errors.errors-forms')

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

					 <form class="form-horizontal" method="POST" action="{{ url('panel/admin/images/update') }}" enctype="multipart/form-data">
             @csrf
             <input type="hidden" name="id" value="{{$data->id}}">

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-label text-lg-end">{{ trans('admin.title') }}</label>
		          <div class="col-sm-10">
		            <input value="{{ $data->title }}" name="title" type="text" class="form-control">
		          </div>
		        </div>

		        <div class="row mb-3">
		          <label class="col-sm-2 col-form-labe text-lg-end">{{ trans('misc.category') }}</label>
		          <div class="col-sm-10">
		            <select name="categories_id" class="form-select">
                  @foreach (Categories::where('mode','on')->orderBy('name')->get() as $category)
                      <option @if( $data->categories_id == $category->id ) selected="selected" @endif value="{{$category->id}}">{{ $category->name }}</option>
                    @endforeach
		           </select>
		          </div>
		        </div>

            <fieldset class="row mb-3">
              <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('admin.status') }}</legend>
              <div class="col-sm-10">
                <div class="form-check form-switch form-switch-md">
                 <input class="form-check-input" type="checkbox" name="status" @if ($data->status == 'active') checked="checked" @endif value="active" role="switch">
               </div>
              </div>
            </fieldset><!-- end row -->

            <fieldset class="row mb-3">
              <legend class="col-form-label col-sm-2 pt-0 text-lg-end">{{ trans('misc.featured') }}</legend>
              <div class="col-sm-10">
                <div class="form-check form-switch form-switch-md">
                 <input class="form-check-input" type="checkbox" name="featured" @if ($data->featured == 'yes') checked="checked" @endif value="yes" role="switch">
               </div>
              </div>
            </fieldset><!-- end row -->

						<div class="row mb-3">
		          <div class="col-sm-10 offset-sm-2">
		            <button type="submit" class="btn btn-dark mt-3 px-5 me-2">{{ __('admin.save') }}</button>
                <a href="{{ url('photo', $data->id) }}" target="_blank" class="btn btn-link text-reset mt-3 px-3 e-none text-decoration-none">{{ __('admin.view') }} <i class="bi-box-arrow-up-right ms-1"></i></a>
		          </div>
		        </div>

		       </form>

				 </div><!-- card-body -->
 			</div><!-- card  -->
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
